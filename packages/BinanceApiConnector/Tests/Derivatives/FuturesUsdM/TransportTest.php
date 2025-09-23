<?php

namespace Empiriq\BinanceApiConnector\Tests\Derivatives\FuturesUsdM;

use _PHPStan_2d0955352\Nette\Neon\Exception;
use Bramus\Monolog\Formatter\ColoredLineFormatter;
use Empiriq\BinanceApiConnector\Common\Helpers\Serializer;
use Empiriq\BinanceApiConnector\Common\Signers\HmacSigner;
use Empiriq\BinanceApiConnector\Connector;
use Empiriq\BinanceApiConnector\Derivatives\FuturesUsdM\FuturesUsdMTransportFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

use function React\Async\await;

class TransportTest extends TestCase
{
    public function testAllMethods(): void
    {
        $this->assertTrue(true);
    }
}
