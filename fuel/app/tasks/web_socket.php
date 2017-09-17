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
    private $server = null;

    public function run()
    {

        try
        {
            $is_run = \Cache::get('raspberry.server');
        }
        catch (\CacheNotFoundException $e)
        {
            $is_run = false;
        }

        \Cache::set('raspberry.server', true, 80);

        if ($is_run === false)
        {
            $this->server = IoServer::factory(
                new HttpServer(
                    new WsServer(
                        new \Server($this)
                    )
                ),
                9090
            );

            $this->server->run();
        }
    }

    public function stopCallback()
    {
        \Cache::delete('raspberry.server');
        $this->server->loop->stop();
    }
}
