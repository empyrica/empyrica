<?php

namespace App\Common;

use React\Socket\ConnectionInterface;
use SplObjectStorage;

readonly class Connection
{
    /**
     * @param ConnectionInterface $connection
     * @param SplObjectStorage $clients
     */
    public function __construct(
        private ConnectionInterface $connection,
        private SplObjectStorage $clients
    ) {
        $this->connection->write("Welcome to Empiriq Terminal!\n");
        $this->connection->on('data', [$this, '__data']);
        $this->connection->on('end', [$this, '__end']);
        $this->connection->on('close', [$this, '__close']);
        $this->connection->on('error', [$this, '__error']);
    }

    /**
     * Пришли данные
     * @param $data
     * @return void
     */
    public function __data($data): void
    {
        var_dump('client data');
    }

    /**
     * Клиент закрыл соединение (корректно)
     * @return void
     */
    public function __end(): void
    {
        var_dump('__end');
    }

    /**
     * Соединение полностью закрыто (и корректно, и при обрыве)
     * @return void
     */
    public function __close(): void
    {
        $this->connection->removeListener('data', [$this, '__data']);
        $this->connection->removeListener('close', [$this, '__close']);
        $this->clients->detach($this);
    }

    /**
     * Ошибка соединения
     * @param $data
     * @return void
     */
    public function __error($data): void
    {
        var_dump($data);
    }
}
