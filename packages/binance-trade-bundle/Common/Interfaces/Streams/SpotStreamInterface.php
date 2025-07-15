<?php

namespace Empiriq\BinanceApiConnector\Common\Interfaces\Streams;

use Empiriq\BinanceApiConnector\Spot\Spot\SpotTransport;
use React\Promise\PromiseInterface;

interface SpotStreamInterface
{
    public function subscribe(SpotTransport $transport): PromiseInterface;

    //todo public function gertSymbols(): array; // common contract for repository
}
