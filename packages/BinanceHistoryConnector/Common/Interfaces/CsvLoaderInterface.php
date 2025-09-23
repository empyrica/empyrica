<?php

namespace Empiriq\BinanceHistoryConnector\Common\Interfaces;

/**
 * @internal
 */
interface CsvLoaderInterface
{
    public function load(string $uri): string;
}
