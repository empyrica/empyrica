<?php

namespace App\Common;

use Empiriq\Empiriq\Kernel;
use Empiriq\Runner\Commands\Server\RunCommand;
use React\Socket\ConnectionInterface;
use React\Socket\ServerInterface;
use React\Socket\SocketServer;
use SplObjectStorage;
use Symfony\Component\Console\Application;

class Runner extends Application
{
    private ServerInterface $server;
    private SplObjectStorage $clients;

    /**
     * @param Kernel $kernel
     */
    public function __construct(
        private Kernel $kernel,
    ) {
        $this->server = new SocketServer('0.0.0.0:2009', []);
        $this->clients = new SplObjectStorage();
        $this->kernel->boot();


        $this->add(new RunCommand());
        $this->setDefaultCommand('run', true);
//        $this->application->setDispatcher();
//        $this->application->setSignalsToDispatchEvent();
        $this->server->on('connection', [$this, '__connection']);
        $this->server->on('close', [$this, '__close']);
        $this->server->on('error', [$this, '__error']);
    }

    /**
     * Когда к серверу подключился новый клиент
     * @param ConnectionInterface $connection
     * @return void
     */
    public function __connection(ConnectionInterface $connection): void
    {
        $this->clients->attach(new Connection($connection, $this->clients));
    }

    /**
     * Когда сервер закрыт
     * @param $data
     * @return void
     */
    public function __close($data): void
    {
        var_dump($data);
    }

    /**
     * Когда произошла ошибка
     * @param $data
     * @return void
     */
    public function __error($data): void
    {
        var_dump($data);
    }
}
