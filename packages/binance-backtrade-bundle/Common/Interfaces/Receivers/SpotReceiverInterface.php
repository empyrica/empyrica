<?php

namespace Empiriq\BinanceHistoryConnector\Common\Interfaces\Receivers;

use Empiriq\BinanceHistoryConnector\Common\Interfaces\ReceiverInterface;
use Empiriq\BinanceHistoryConnector\Common\Interfaces\Streams\SpotStreamInterface;

/**
 * @internal
 */
interface SpotReceiverInterface extends ReceiverInterface
{
    public function addStream(SpotStreamInterface $stream): SpotReceiverInterface;
}
