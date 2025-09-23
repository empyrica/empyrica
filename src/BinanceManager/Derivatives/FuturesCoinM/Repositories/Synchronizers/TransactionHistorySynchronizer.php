<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\Synchronizers;

use React\Promise\PromiseInterface;

use function React\Promise\resolve;

/**
 * @internal
 */
trait TransactionHistorySynchronizer
{
    public function __synchronize(): PromiseInterface
    {
        return resolve([]);
    }
}
