<?php

namespace App;

use React\Socket\ConnectionInterface;

class ConnectionsPool
{
    protected $connections;

    public function __construct()
    {
        $this->connections = new \SplObjectStorage();
    }

    public function add(ConnectionInterface $connection)
    {
        $connection->write("Hello\n");
        $connection->write('Enter your name:');
        $this->initEvents($connection);
       
    }
    
    private function initEvents(ConnectionInterface $connection)
    {
        $this->setConnectionName($connection, '');

        $connection->on('data', function ($data) use ($connection) {
            $name = $this->getConnectionName($connection);
            
            if (empty($name)) {
                $this->addNewMember($connection, $data);
                return;
            }

            $this->sendAll("$name: $data", $connection);
        });

        $connection->on('close', function () use ($connection) {
            $name = $this->getConnectionName($connection);
            $this->connections->detach($connection);
            $this->sendAll("A $name leaves the chat\n", $connection);
        });
    }

    private function addNewMember(ConnectionInterface $connection, $name)
    {
        $name = str_replace(["\n", "\r"], '', $name);
        var_dump($name);
        $this->setConnectionName($connection, $name);
        $this->sendAll("User $name joins the chat\n", $connection);
    }

    private function getConnectionName(ConnectionInterface $connection)
    {
        return $this->connections->offsetGet($connection);
    }

    private function setConnectionName(ConnectionInterface $connection, $name)
    {
        $this->connections->offsetSet($connection, $name);
    }

    private function sendAll($message, ConnectionInterface $except)
    {
        foreach ($this->connections as $connection) {
            if ($connection !== $except) {
                $connection->write($message);
            }
        }
    }
}
