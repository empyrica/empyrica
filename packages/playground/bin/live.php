#!/usr/bin/env php
<?php

use Bramus\Monolog\Formatter\ColoredLineFormatter;
use Empiriq\BinanceApiConnector\Common\Helpers\Sanitizer;
use Empiriq\BinanceApiConnector\Common\Helpers\Serializer;
use Empiriq\BinanceApiConnector\Common\Signers\HmacSigner;
use Empiriq\BinanceApiConnector\Connector;
use Empiriq\BinanceApiConnector\Derivatives\FuturesUsdM\Clients\RestApi;
use Empiriq\BinanceApiConnector\Derivatives\FuturesUsdM\Clients\WebsocketApi;
use Empiriq\BinanceApiConnector\Derivatives\FuturesUsdM\Clients\WebsocketStreams;
use Empiriq\BinanceApiConnector\Derivatives\FuturesUsdM\FuturesUsdMTransport;
use Empiriq\BinanceApiConnector\Derivatives\FuturesUsdM\Streams\TradeStream;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcher;

use function React\Async\await;

require __DIR__ . '/../vendor/autoload.php';

$apiKey = '4bbd70a3c5657b4f574dae1622c4449a7b5893fab147d6d9ec098c8be524f6df';
$signer = new HmacSigner('6422295ea0671998ef386c3faf9ab457ee013ae76502d50d48795888189ee831');
$restApiUri = 'https://testnet.binancefuture.com';
$webSocketApiUri = 'wss://testnet.binancefuture.com/ws-fapi/v1';
$webSocketMarketStreamsUri = 'wss://fstream.binancefuture.com/ws';

$dispatcher = new EventDispatcher();
$handler = new StreamHandler('php://stdout', Level::Debug);
$handler->setFormatter(new ColoredLineFormatter());
$logger = new Logger('FUTURES_USD_M', [$handler]);
$serializer = new Serializer();
$sanitizer = new Sanitizer();
$resolverTimeout = 10;
$transports = [];
$transports[] = new FuturesUsdMTransport(
    [
        new TradeStream(['BTCUSDT']),
        // new UserDataStream(),
    ],
    new RestApi(
        'https://fapi.binance.com', // testnet https://testnet.binancefuture.com,
        $apiKey,
        $signer,
        $serializer,
        $logger,
        $sanitizer,
        $resolverTimeout,
    ),
    new WebsocketApi(
        $dispatcher,
        'wss://ws-fapi.binance.com/ws-fapi/v1', // testnet wss://testnet.binancefuture.com/ws-fapi/v1
        $apiKey,
        $signer,
        $serializer,
        $logger,
        $sanitizer,
        $resolverTimeout,
    ),
    new WebsocketStreams(
        $dispatcher,
        'wss://fstream.binance.com/ws', // testnet wss://fstream.binancefuture.com/ws
        $serializer,
        $logger,
        $sanitizer,
        $resolverTimeout,
    )
);
$connector = new Connector(
    $transports,
    new Logger('CONNECTOR', [$handler])
);
$connector->run()->then(function (Connector $connector) {
    var_dump(await($connector->futuresUsdM()->accountBalanceV2()));
    return null;
});
