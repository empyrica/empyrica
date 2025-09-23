<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories;

use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesCmRepositoryInterface;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\Synchronizers\TransactionHistorySynchronizer;

class TransactionHistoryRepository implements FuturesCmRepositoryInterface
{
    use TransactionHistorySynchronizer;
}
