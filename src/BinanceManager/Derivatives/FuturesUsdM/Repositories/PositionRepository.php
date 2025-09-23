<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories;

use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesUmRepositoryInterface;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\Synchronizers\PositionSynchronizer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PositionRepository implements FuturesUmRepositoryInterface
{
    use PositionSynchronizer;

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
