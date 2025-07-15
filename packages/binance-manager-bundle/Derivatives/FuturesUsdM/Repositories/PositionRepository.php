<?php

namespace Empiriq\BinanceManager\Derivatives\FuturesUsdM\Repositories;

use Empiriq\BinanceApiConnector\Connector;
use Empiriq\BinanceManager\Common\Interfaces\Repositories\FuturesUmRepositoryInterface;
use Empiriq\BinanceManager\Derivatives\FuturesUsdM\Repositories\Synchronizers\PositionSynchronizer;
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
