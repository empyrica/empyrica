<?php

namespace Empiriq\BinanceApiConnector\Common\Signers;

use Empiriq\BinanceApiConnector\Common\Exceptions\Configuration\ConfigurationException;
use Empiriq\BinanceApiConnector\Common\Interfaces\SignerInterface;

readonly class NullSigner implements SignerInterface
{
    public function createSignature(array $params): string
    {
        throw new ConfigurationException('null signer');
    }
}
