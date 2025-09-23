<?php

namespace Empiriq\BinanceManager\Spot\Spot\Repositories;

use Empiriq\BinanceApiConnector\Connector;
use Empiriq\BinanceManager\Common\Interfaces\Repositories\SpotRepositoryInterface;
use Empiriq\BinanceManager\Spot\Spot\Repositories\Synchronizers\TransactionHistorySynchronizer;
use Psr\EventDispatcher\EventDispatcherInterface;

class TransactionHistoryRepository implements SpotRepositoryInterface
{
    use TransactionHistorySynchronizer;

    /**
     * @param Connector $connector
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    public function __construct(
        private readonly Connector $connector,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }
}
