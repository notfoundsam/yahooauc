<?php

namespace Fuel\Tasks;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
// use MyApp\Chat;

/**
* Convert old DB
* Don't run if you have BD created through migration
*/
class Web_socket
{
    public static function run()
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new \Chat()
                )
            ),
            9090
        );

        $server->run();
    }
}
