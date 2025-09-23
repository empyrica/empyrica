<?php

namespace Empiriq\BinanceApiConnector\Derivatives\FuturesUsdM\Clients;

use Empiriq\BinanceApiConnector\Common\Clients\Websocket\ResponseResolver;
use Empiriq\BinanceApiConnector\Common\Interfaces\ClientInterface;
use Empiriq\BinanceApiConnector\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\EventInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Handles WebSocket connections to Binance Coin Margined Futures market streams.
 *
 * Aggregates multiple FuturesUmStreamInterface implementations into a single WebSocket connection,
 * deserializes incoming messages into FuturesUmEvent objects, and dispatch via EventDispatcher.
 *
 * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/websocket-market-streams
 */
final class WebsocketStreams extends ResponseResolver implements ClientInterface
{
    /**
     * @param EventDispatcherInterface $dispatcher
     * @param string $uri
     * @param DecoderInterface&SerializerInterface&NormalizerInterface&EncoderInterface&DenormalizerInterface $serializer
     * @param LoggerInterface $logger
     * @param SanitizerInterface $sanitizer
     * @param float $resolverTimeout
     */
    public function __construct(
        protected EventDispatcherInterface $dispatcher,
        protected string $uri,
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
        return isset($data['e']) ? $data : null;
    }

    protected static function getEventType(): string
    {
        return EventInterface::class;
    }
}
