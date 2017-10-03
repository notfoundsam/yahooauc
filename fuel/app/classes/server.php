<?php

// namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Server implements MessageComponentInterface
{
    protected $clients;
    protected $raspberry;
    protected $task;
    protected $clients_id;


    public function __construct($task)
    {
        $this->task = $task;
        $this->clients = new \SplObjectStorage;
        $this->raspberry = null;
        $this->clients_id = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $query = $conn->WebSocket->request->getQuery()->toArray();

        if (isset($query['key']) && $query['key'] == 'py_secret_key')
        {
            // Store the new connection to send messages to later
            $this->raspberry = $conn;
            echo "Raspberry connection! ({$conn->resourceId})\n";
        }
        else if (isset($query['key']) && $query['key'] == 'web_secret_key')
        {
            // Store the new connection to send messages to later
            $this->clients->attach($conn);
            $this->clients_id[] = $conn->resourceId;
            echo "New connection! ({$conn->resourceId})\n";
        }
        else
        {
            $conn->send("An error has occurred");
            echo "An error has occurred\n";
            $conn->close();
        }
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // $this->task->stopCallback();
        // call_user_func($this->task->);
        // $numRecv = count($this->clients) - 1;
        // echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
        //     , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        if ($from->resourceId == $this->raspberry->resourceId)
        {
            foreach ($this->clients as $client) {
                $client->send($msg);
            }
        }
        else
        {
            $this->raspberry->send($msg);
        }

        
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
