<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories;

// тут есть коммисии
use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesCmRepositoryInterface;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\Synchronizers\TradeHistorySynchronizer;

class TradeHistoryRepository implements FuturesCmRepositoryInterface
{
    use TradeHistorySynchronizer;
}
