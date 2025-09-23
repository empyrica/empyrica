<?php

namespace Empiriq\BinanceApiConnector\Spot\Spot\Streams;

use Empiriq\BinanceApiConnector\Common\Interfaces\Streams\SpotStreamInterface;
use Empiriq\BinanceApiConnector\Spot\Spot\SpotTransport;
use React\Promise\PromiseInterface;

/**
 * Represents a binance stream for spot market messages.
 *
 * Constructs a stream name like "btcusdt@depth" based on the given symbol.
 */
readonly class DepthStream implements SpotStreamInterface
{
    /**
     * @param string[] $symbols The trading pair symbol (e.g. "BTCUSDT"). Case-insensitive.
     */
    public function __construct(
        private array $symbols
    ) {
    }

    public function subscribe(SpotTransport $transport): PromiseInterface
    {
        return $transport->subscribe(array_map(fn(string $symbol): string => strtolower($symbol) . '@depth', $this->symbols));
    }
}
