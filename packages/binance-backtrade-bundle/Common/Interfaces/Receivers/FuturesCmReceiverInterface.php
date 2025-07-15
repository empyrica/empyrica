<?php

namespace Empiriq\BinanceHistoryConnector\Common\Interfaces\Receivers;

use Empiriq\BinanceHistoryConnector\Common\Interfaces\ReceiverInterface;
use Empiriq\BinanceHistoryConnector\Common\Interfaces\Streams\FuturesCmStreamInterface;

/**
 * @internal
 */
interface FuturesCmReceiverInterface extends ReceiverInterface
{
    public function addStream(FuturesCmStreamInterface $stream): FuturesCmReceiverInterface;
}
