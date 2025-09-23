<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories;

use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesCmRepositoryInterface;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\Synchronizers\OrderHistorySynchronizer;

class OrderHistoryRepository implements FuturesCmRepositoryInterface
{
    use OrderHistorySynchronizer;
}
