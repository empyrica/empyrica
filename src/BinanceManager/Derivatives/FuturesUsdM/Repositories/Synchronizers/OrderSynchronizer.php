<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\Synchronizers;

use React\Promise\PromiseInterface;

use function React\Promise\resolve;

/**
 * @internal
 */
trait OrderSynchronizer
{
    public function __synchronize(): PromiseInterface
    {
        return resolve([]);
    }
}
