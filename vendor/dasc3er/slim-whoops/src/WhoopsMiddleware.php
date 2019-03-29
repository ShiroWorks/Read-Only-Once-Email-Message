<?php

namespace Dasc3er\Slim\Whoops;

use Whoops\Util\Misc;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\JsonResponseHandler;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class WhoopsMiddleware
{
    protected $container;
    protected $editor;

    public function __construct(\Slim\Container $container, string $editor = null)
    {
        $this->container = $container;
        $this->setEditor($editor);
    }

    /**
     * Invoke middleware.
     *
     * @param ServerRequestInterface $request  PSR7 request object
     * @param ResponseInterface      $response PSR7 response object
     * @param callable               $next     Next middleware callable
     *
     * @return ResponseInterface PSR7 response object
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $settings = $this->container['settings'];

        if (!empty($settings['displayErrorDetails'])) {
            // Enable PrettyPageHandler with editor options
            $prettyPageHandler = new PrettyPageHandler();

            if (!empty($this->editor)) {
                $prettyPageHandler->setEditor($this->editor);
            }

            $environment = $this->container['environment'];
            // Add more information to the PrettyPageHandler
            $prettyPageHandler->addDataTable('Slim Application', [
                'Application Class' => get_class($next),
                'Script Name' => $environment->get('SCRIPT_NAME'),
                'Request URI' => $environment->get('REQUEST_URI') ?: '-',
            ]);

            $port = $request->getUri()->getPort();
            $prettyPageHandler->addDataTable('Slim Application (Request)', array(
                'Accept Charset' => $request->getHeader('ACCEPT_CHARSET') ?: '-',
                'Content Charset' => $request->getContentCharset() ?: '-',
                'Path' => $request->getUri()->getPath(),
                'Query String' => $request->getUri()->getQuery() ?: '-',
                'HTTP Method' => $request->getMethod(),
                'Base URL' => (string) $request->getUri(),
                'Scheme' => $request->getUri()->getScheme(),
                'Port' => isset($port) ? $port : 'Default ('.$this->standardPort($request->getUri()->getScheme()).')',
                'Host' => $request->getUri()->getHost(),
            ));

            // Set Whoops to default exception handler
            $whoops = new \Whoops\Run();
            $whoops->pushHandler($prettyPageHandler);

            // Enable JsonResponseHandler when request is AJAX
            if (Misc::isAjaxRequest()) {
                $whoops->pushHandler(new JsonResponseHandler());
            }

            $whoops->register();

            // Override the default Slim error handler
            $this->container['errorHandler'] = function () use ($whoops) {
                return new WhoopsErrorHandler($whoops);
            };

            // Add the Whoops istance to the Slim Container
            $this->container['whoops'] = $whoops;
        }

        return $next($request, $response);
    }

    public function getEditor()
    {
        return $this->editor;
    }

    public function setEditor($editor)
    {
        if(is_string($editor)){
            $this->editor = $editor;
        }
    }

    /**
     * Returns the standard port of the inserted scheme.
     *
     * @param string $scheme URI Scheme
     *
     * @return int
     */
    protected function standardPort(string $scheme)
    {
        $scheme = strtolower($scheme);

        if ($scheme === 'http') {
            return 80;
        } elseif ($scheme === 'https') {
            return 443;
        }

        return null;
    }
}
