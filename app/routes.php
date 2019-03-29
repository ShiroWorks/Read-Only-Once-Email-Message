<?php

$app->get('/', function ($request, $response, $args){
    
    $this->view->render($response, 'home.twig');
})->setName('home');

$app->post('/post', function ($request, $response, $args) {
 $params = $request->getParams();
 $hash = md5(uniqid(true));
 $message = $this->db->prepare("INSERT INTO messages (hash, message) VALUES (:hash, :message)");

 $message->execute([
     'hash' => $hash,
     'message' => $params['message'],
 ]);
    $this->mail->sendMessage($this->config->get('services.mailgun.domain'), [
        'from' => 'noreply@xyz.com',
        'to' => $params['email'],
        'subject' => 'New message from xyz!',
        'html' => $this->view->fetch('email/message.twig', [
            'hash' => $hash,
        ]),
    ]);


    return $response->withRedirect($this->router->pathFor('home'));
})->setName('send');

$app->get('/message/{hash}', function ($request, $response, $args){
    $message = $this->db->prepare("SELECT message FROM messages WHERE hash = :hash; DELETE FROM messages WHERE hash = :hash;" );
    $message->execute([
        'hash' => $args['hash'],
    ]);
    $message = $message->fetch(PDO::FETCH_OBJ);

    return $this->view->render($response, 'message/show.twig', [
        'message' => $message,
    ]);
})->setName('message');