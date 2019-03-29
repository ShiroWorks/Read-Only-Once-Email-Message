# Slim Whoops

[Whoops](https://github.com/filp/whoops) middleware for Slim micro framework.

Built on top of [zeuxisoo/php-slim-whoops](https://github.com/zeuxisoo/php-slim-whoops), with PSR-4 autoloading and support for Slim 3.

## Installation

This package is installable and autoloadable via Composer as [dasc3er/slim-whoops](https://packagist.org/packages/dasc3er/slim-whoops).

```bash
php composer.phar require dasc3er/slim-whoops
```

## Usage

Add the middleware to the Slim application.

```php
$container = $app->getContainer();

$app->add(new \dasc3er\Slim\Whoops\WhoopsMiddleware($container));
```

You may specify your favorite editor or IDE to use with the Whoops library.

```php
$container = $app->getContainer();
$editor = 'sublime';

$app->add(new \dasc3er\Slim\Whoops\WhoopsMiddleware($container, $editor));
```

## Options

The middleware will override the defualt Slim error handling with Whoops when the Slim option `displayErrorDetails` is set to `true`.

```php
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true, // Enable/Disable error reporting
    ]
]);
```

## Testing

To run the test cases, execute via command line the following line.

```bash
php vendor/bin/phpunit
```

## License

Code released under the [**BSD 2-Clause License**](https://github.com/Dasc3er/slim-whoops/blob/master/LICENSE).
