<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories;

use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesUmRepositoryInterface;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\Synchronizers\PositionHistorySynchronizer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PositionHistoryRepository implements FuturesUmRepositoryInterface
{
    use PositionHistorySynchronizer;

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
