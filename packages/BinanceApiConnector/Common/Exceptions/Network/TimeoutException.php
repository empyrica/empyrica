<?php

namespace Empiriq\BinanceApiConnector\Common\Exceptions\Network;

use Empiriq\BinanceApiConnector\Common\Exceptions\RuntimeException;
use React\Promise\Timer\TimeoutException as ReactTimeoutException;

class TimeoutException extends RuntimeException
{
    public function __construct(string $message = "", int $code = 0, ?ReactTimeoutException $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
