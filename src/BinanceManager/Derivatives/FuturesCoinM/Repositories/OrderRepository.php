<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories;

use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesCmRepositoryInterface;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\Synchronizers\OrderSynchronizer;

class OrderRepository implements FuturesCmRepositoryInterface
{
    use OrderSynchronizer;
}
