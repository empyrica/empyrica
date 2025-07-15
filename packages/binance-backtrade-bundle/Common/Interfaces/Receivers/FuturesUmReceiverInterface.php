<?php

namespace Empiriq\BinanceHistoryConnector\Common\Interfaces\Receivers;

use Empiriq\BinanceHistoryConnector\Common\Interfaces\ReceiverInterface;
use Empiriq\BinanceHistoryConnector\Common\Interfaces\Streams\FuturesUmStreamInterface;

/**
 * @internal
 */
interface FuturesUmReceiverInterface extends ReceiverInterface
{
    public function addStream(FuturesUmStreamInterface $stream): FuturesUmReceiverInterface;
}
