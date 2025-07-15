<?php

namespace Empiriq\BinanceApiConnector\Derivatives\FuturesUsdM\Clients;

use Empiriq\BinanceApiConnector\Common\Clients\Websocket\ResponseResolver;
use Empiriq\BinanceApiConnector\Common\Interfaces\ClientInterface;
use Empiriq\BinanceApiConnector\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceApiConnector\Common\Interfaces\SignerInterface;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\EventInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/websocket-api-general-info
 */
final class WebsocketApi extends ResponseResolver implements ClientInterface
{
    /**
     * @param EventDispatcherInterface $dispatcher
     * @param string $apiKey
     * @param SignerInterface $signer
     * @param string $uri Testnet by default, the main: wss://ws-fapi.binance.com/ws-fapi/v1
     * @param float $resolverTimeout
     * @param SerializerInterface & NormalizerInterface & DenormalizerInterface & EncoderInterface & DecoderInterface $serializer
     * @param SanitizerInterface $sanitizer
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected EventDispatcherInterface $dispatcher,
        protected string $uri,
        protected string $apiKey,
        protected SignerInterface $signer,
        protected SerializerInterface & NormalizerInterface & DenormalizerInterface & EncoderInterface & DecoderInterface $serializer,
        protected LoggerInterface $logger,
        protected SanitizerInterface $sanitizer,
        protected float $resolverTimeout,
    ) {
    }

    protected static function extractRawResponse(array $data): ?array
    {
        return isset($data['id']) ? $data : null;
    }

    protected static function extractRawEvent(array $data): ?array
    {
        return $data['event'] ?? null;
    }

    protected static function getEventType(): string
    {
        return EventInterface::class;
    }
}
