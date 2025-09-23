<?php

namespace Empiriq\BinanceApiConnector\Spot\Spot;

use Empiriq\BinanceApiConnector\Common\Helpers\Sanitizer;
use Empiriq\BinanceApiConnector\Common\Helpers\Serializer;
use Empiriq\BinanceApiConnector\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceApiConnector\Common\Interfaces\SignerInterface;
use Empiriq\BinanceApiConnector\Common\Interfaces\Streams\SpotStreamInterface;
use Empiriq\BinanceApiConnector\Common\Interfaces\TransportInterface;
use Empiriq\BinanceApiConnector\Common\Signers\NullSigner;
use Empiriq\BinanceApiConnector\Spot\Spot\Clients\RestApi;
use Empiriq\BinanceApiConnector\Spot\Spot\Clients\WebsocketApi;
use Empiriq\BinanceApiConnector\Spot\Spot\Clients\WebsocketStreams;
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

readonly class SpotTransportFactory
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
        private string $restApiUri = 'https://api.binance.com', // testnet https://testnet.binance.vision/api
        private string $websocketApiUri = 'wss://ws-api.testnet.binance.vision/ws-api/v3', // testnet wss://ws-api.testnet.binance.vision/ws-api/v3
        private string $websocketStreamsUri = 'wss://stream.binance.com:9443/ws', // testnet ???
        private SerializerInterface & NormalizerInterface & DenormalizerInterface & EncoderInterface & DecoderInterface $serializer = new Serializer(),
        private SanitizerInterface $sanitizer = new Sanitizer(),
        private float $resolverTimeout = 10,
        private LoggerInterface $logger = new Logger('SPOT', [
            new StreamHandler('php://stderr', Level::Debug),
        ]),
    ) {
    }

    /**
     * @param SpotStreamInterface[] $streams
     * @return TransportInterface
     */
    public function createTransport(array $streams): TransportInterface
    {
        return new SpotTransport(
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
