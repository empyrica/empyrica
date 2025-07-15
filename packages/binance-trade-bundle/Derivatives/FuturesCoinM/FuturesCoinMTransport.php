<?php

namespace Empiriq\BinanceApiConnector\Derivatives\FuturesCoinM;

use Empiriq\BinanceApiConnector\Common\Exceptions\Configuration\ConfigurationException;
use Empiriq\BinanceApiConnector\Common\Interfaces\Streams\FuturesCoinMStreamInterface;
use Empiriq\BinanceApiConnector\Common\Interfaces\TransportInterface;
use Empiriq\BinanceApiConnector\Derivatives\FuturesCoinM\Clients\RestApi;
use Empiriq\BinanceApiConnector\Derivatives\FuturesCoinM\Clients\WebsocketApi;
use Empiriq\BinanceApiConnector\Derivatives\FuturesCoinM\Clients\WebsocketStreams;
use Empiriq\BinanceApiConnector\Derivatives\FuturesCoinM\Methods\AccountMethods;
use Empiriq\BinanceApiConnector\Derivatives\FuturesCoinM\Methods\AuthenticationMethods;
use Empiriq\BinanceApiConnector\Derivatives\FuturesCoinM\Methods\GeneralMethods;
use Empiriq\BinanceApiConnector\Derivatives\FuturesCoinM\Methods\MarketDataMethods;
use Empiriq\BinanceApiConnector\Derivatives\FuturesCoinM\Methods\MarketStreamMethods;
use Empiriq\BinanceApiConnector\Derivatives\FuturesCoinM\Methods\TradingMethods;
use Empiriq\BinanceApiConnector\Derivatives\FuturesCoinM\Methods\UserDataStreamMethods;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\General\TimeResponse;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\Account\AccountStatusResponse;
use React\Promise\PromiseInterface;

use function React\Promise\all;

readonly class FuturesCoinMTransport implements TransportInterface
{
    use GeneralMethods;
    use MarketDataMethods;
    use AuthenticationMethods;
    use TradingMethods;
    use AccountMethods;
    use UserDataStreamMethods;
    use MarketStreamMethods;

    /**
     * @param FuturesCoinMStreamInterface[] $streams
     * @param RestApi $restApi
     * @param WebsocketApi $websocketApi
     * @param WebsocketStreams $websocketStreams
     */
    public function __construct(
        private array $streams,
        private RestApi $restApi,
        private WebsocketApi $websocketApi,
        private WebsocketStreams $websocketStreams,
    ) {
        foreach ($this->streams as $stream) {
            if (!$stream instanceof FuturesCoinMStreamInterface) {
                throw new ConfigurationException('Invalid stream');
            }
        }
    }

    public function run(): PromiseInterface
    {
        return all([
            $this->websocketApi->run()->then(function () {
                return $this->time();
            })->then(function (TimeResponse $response) {
                $this->restApi->calculateTimeOffset($response->result->serverTime);
                $this->websocketApi->calculateTimeOffset($response->result->serverTime);
                return $this;
            })->then(function () {
                return $this->websocketApi->canLogIn() ? $this->sessionLogon() : null;
            })->then(function (?AccountStatusResponse $response) {
                $this->websocketApi->setLoggedIn((bool)$response);
                return $this;
            }),
            $this->websocketStreams->run(),
        ])
            ->then(fn() => all(array_map(fn(FuturesCoinMStreamInterface $stream) => $stream->subscribe($this), $this->streams)))
            ->then(fn() => $this);
    }

    public function shutdown(): void
    {
        $this->websocketApi->shutdown();
        $this->websocketStreams->shutdown();
    }

    public function isLoggedIn(): bool
    {
        return $this->websocketApi->isLoggedIn();
    }
}
