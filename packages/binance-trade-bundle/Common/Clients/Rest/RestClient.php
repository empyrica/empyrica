<?php

namespace Empiriq\BinanceApiConnector\Common\Clients\Rest;

use DateTime;
use DateTimeZone;
use Empiriq\BinanceApiConnector\Common\Exceptions\Configuration\ConfigurationException;
use Empiriq\BinanceApiConnector\Common\Exceptions\Network\DisconnectedException;
use Empiriq\BinanceApiConnector\Common\Exceptions\RuntimeException;
use Empiriq\BinanceApiConnector\Common\Exceptions\Serialization\SerializationException;
use Empiriq\BinanceApiConnector\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceApiConnector\Common\Interfaces\SignerInterface;
use Empiriq\BinanceApiConnector\Common\Signers\NullSigner;
use Empiriq\BinanceContracts\Common\PermissionInterface;
use Exception;
use Psr\Log\LoggerInterface;
use React\Http\Browser;
use React\Http\Message\Response;
use React\Promise\PromiseInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerBaseException;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Throwable;

use function React\Promise\reject;

abstract class RestClient
{
    protected Browser $client;
    protected string $apiKey = '';
    protected SignerInterface $signer;
    protected NormalizerInterface & DenormalizerInterface & EncoderInterface & DecoderInterface $serializer;
    protected LoggerInterface $logger;
    protected SanitizerInterface $sanitizer;
    private int $timeOffsetMs = 0;

    /**
     * @template T of object
     * @param string $method
     * @param string $path
     * @param PermissionInterface $permission
     * @param class-string<T> $type
     * @param mixed|null $payload
     * @param int|null $recvWindow
     *
     * @return PromiseInterface<T>
     */
    public function send(string $method, string $path, PermissionInterface $permission, string $type, mixed $payload = null, ?int $recvWindow = null): PromiseInterface
    {
        try {
            $id = bin2hex(random_bytes(8));
            $headers = [];
            $params = [];
            if (!is_null($payload)) {
                $params = (array)$this->serializer->normalize($payload);
            }
            if (!is_null($recvWindow)) {
                $params['recvWindow'] = $recvWindow;
            }
            if ($permission->requiresApiKey()) {
                $headers['X-MBX-APIKEY'] = $this->getApiKey();
            }
            if ($permission->requiresSignature()) {
                $params['timestamp'] = $this->calculateTimestamp();
                $params['signature'] = $this->getSigner()->createSignature($params);
            }
            $this->logger->info(sprintf('Sending request (id: %s) %s %s', $id, $method, $path), $this->sanitizer->sanitize($params));

            return $this->client->request($method, $path, $headers, http_build_query($params))->then(function (Response $response) use ($id, $type) {
                $data = [
                    'id' => $id,
                    'status' => 200,
                    'result' => $this->serializer->decode($response->getBody()->getContents(), JsonEncoder::FORMAT),
                ];
                $this->logger->info(sprintf('Received response (id: %s)', $id), $data['result'] ?? []);

                return $this->serializer->denormalize($data, $type);
            });
        } catch (ConfigurationException $exception) {
            $this->logger->error(sprintf('Failed to send request (id: %s query: %s %s) %s', $id, $method, $path, $exception->getMessage()));
            return reject($exception);
        } catch (DisconnectedException $exception) {
            $this->logger->error(sprintf('Failed to send request (id: %s query: %s %s) %s', $id, $method, $path, $exception->getMessage()));
            return reject($exception);
        } catch (SerializerBaseException $exception) {
            $this->logger->error(sprintf('Serialization failed for request (id: %s query: %s %s) %s', $id, $method, $path, $exception->getMessage()));
            return reject(new SerializationException($exception->getMessage(), $exception->getCode(), $exception));
        } catch (Throwable $exception) {
            $this->logger->error(sprintf('Unexpected error while sending request (id: %s query: %s %s) %s', $id, $method, $path, $exception->getMessage()));
            return reject(new RuntimeException($exception->getMessage(), $exception->getCode(), $exception));
        }
    }

    public function calculateTimeOffset(int $serverTime): void
    {
        $this->timeOffsetMs = $serverTime - (int)(new DateTime('now', new DateTimeZone('UTC')))->format('Uv');
    }

    /**
     * @throws Exception
     */
    private function calculateTimestamp(): int
    {
        return (int)(new DateTime('now', new DateTimeZone('UTC')))->format('Uv') + $this->timeOffsetMs;
    }

    private function getSigner(): SignerInterface
    {
        return $this->signer ??= new NullSigner();
    }

    private function getApiKey(): string
    {
        if (!$this->apiKey) {
            throw new ConfigurationException('ApiKey required');
        }
        return $this->apiKey;
    }
}
