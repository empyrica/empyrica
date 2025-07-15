<?php

namespace Empiriq\BinanceApiConnector\Derivatives\FuturesCoinM\Streams;

use Empiriq\BinanceApiConnector\Common\Interfaces\Streams\FuturesCoinMStreamInterface;
use Empiriq\BinanceApiConnector\Derivatives\FuturesCoinM\FuturesCoinMTransport;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\UserData\SubscribeResponse;
use React\EventLoop\Loop;
use React\Promise\PromiseInterface;

readonly class UserDataStream implements FuturesCoinMStreamInterface
{
    public function subscribe(FuturesCoinMTransport $transport): PromiseInterface
    {
        if ($transport->isLoggedIn()) {
            return $transport->userDataStreamSubscribe();
        }

        return $transport->createListenKey()->then(function (SubscribeResponse $response) use ($transport) {
            Loop::addPeriodicTimer(30 * 60, function () use ($transport, $response) {
                $transport->updateListenKey($response->result->listenKey);
            });

            return $transport->subscribe([$response->result->listenKey]);
        });
    }
}
