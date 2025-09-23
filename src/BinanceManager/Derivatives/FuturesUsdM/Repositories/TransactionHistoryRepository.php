<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories;

use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesUmRepositoryInterface;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\Synchronizers\TransactionHistorySynchronizer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TransactionHistoryRepository implements FuturesUmRepositoryInterface
{
    use TransactionHistorySynchronizer;

    /**
     * @param Connector $connector
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        private readonly Connector $connector,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }
}
