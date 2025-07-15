<?php

namespace Empiriq\BinanceApiConnector\Common\Interfaces;

interface SignerInterface
{
    public function createSignature(array $params): string;
}
