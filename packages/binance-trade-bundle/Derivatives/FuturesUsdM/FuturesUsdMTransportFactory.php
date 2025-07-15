<?php

namespace Empiriq\BinanceApiConnector\Derivatives\FuturesUsdM;

use Empiriq\BinanceApiConnector\Common\Helpers\Sanitizer;
use Empiriq\BinanceApiConnector\Common\Helpers\Serializer;
use Empiriq\BinanceApiConnector\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceApiConnector\Common\Interfaces\SignerInterface;
use Empiriq\BinanceApiConnector\Common\Interfaces\Streams\FuturesUsdMStreamInterface;
use Empiriq\BinanceApiConnector\Common\Interfaces\TransportInterface;
use Empiriq\BinanceApiConnector\Common\Signers\NullSigner;
use Empiriq\BinanceApiConnector\Derivatives\FuturesUsdM\Clients\RestApi;
use Empiriq\BinanceApiConnector\Derivatives\FuturesUsdM\Clients\WebsocketApi;
use Empiriq\BinanceApiConnector\Derivatives\FuturesUsdM\Clients\WebsocketStreams;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class FuturesUsdMTransportFactory
{
    /**
     * @param EventDispatcherInterface $dispatcher
     * @param string $apiKey
     * @param SignerInterface $signer
     * @param string $restApiUri
     * @param string $websocketApiUri
     * @param string $websocketStreamsUri
     * @param DecoderInterface&SerializerInterface&NormalizerInterface&EncoderInterface&DenormalizerInterface $serializer
     * @param SanitizerInterface $sanitizer
     * @param float $resolverTimeout
     * @param LoggerInterface $logger
     */
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private string $apiKey = '',
        private SignerInterface $signer = new NullSigner(),
        private string $restApiUri = 'https://fapi.binance.com', // testnet https://testnet.binancefuture.com
        private string $websocketApiUri = 'wss://ws-fapi.binance.com/ws-fapi/v1', // testnet wss://testnet.binancefuture.com/ws-fapi/v1
        private string $websocketStreamsUri = 'wss://fstream.binance.com/ws', // testnet wss://fstream.binancefuture.com/ws
        private SerializerInterface & NormalizerInterface & DenormalizerInterface & EncoderInterface & DecoderInterface $serializer = new Serializer(),
        private SanitizerInterface $sanitizer = new Sanitizer(),
        private float $resolverTimeout = 10,
        private LoggerInterface $logger = new Logger('FUTURES_USD_M', [
            new StreamHandler('php://stderr', Level::Debug),
        ]),
    ) {
    }

    /**
     * @param FuturesUsdMStreamInterface[] $streams
     * @return TransportInterface
     */
    public function createTransport(array $streams): TransportInterface
    {
        return new FuturesUsdMTransport(
            $streams,
            new RestApi(
                $this->restApiUri,
                $this->apiKey,
                $this->signer,
                $this->serializer,
                $this->logger,
                $this->sanitizer,
                $this->resolverTimeout,
            ),
            new WebsocketApi(
                $this->dispatcher,
                $this->websocketApiUri,
                $this->apiKey,
                $this->signer,
                $this->serializer,
                $this->logger,
                $this->sanitizer,
                $this->resolverTimeout,
            ),
            new WebsocketStreams(
                $this->dispatcher,
                $this->websocketStreamsUri,
                $this->serializer,
                $this->logger,
                $this->sanitizer,
                $this->resolverTimeout,
            )
        );
    }
}
