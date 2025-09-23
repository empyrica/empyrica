<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories;

use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesCmRepositoryInterface;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\Synchronizers\PositionHistorySynchronizer;

class PositionHistoryRepository implements FuturesCmRepositoryInterface
{
    use PositionHistorySynchronizer;
}
