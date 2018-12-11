<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
//use application\controllers\Live_chat;

    require dirname(__DIR__) . '/../vendor/autoload.php';
    //require __DIR__.'/src/Chat.php';
    require __DIR__.'/../../application/third_party/src/Chat.php';

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Chat()
            )
        ),
        12000
    );

    $server->run();