<?php

namespace Empiriq\BinanceHistoryConnector\Spot\Spot\Receivers;

use Empiriq\BinanceHistoryConnector\Common\AbstractReceiver;
use Empiriq\BinanceHistoryConnector\Common\Interfaces\Receivers\FuturesCmReceiverInterface;
use Empiriq\BinanceHistoryConnector\Common\Interfaces\Streams\FuturesCmStreamInterface;

class FuturesCmReceiver extends AbstractReceiver implements FuturesCmReceiverInterface
{
    /**
     * @param FuturesCmStreamInterface[] $streams
     */
    public function __construct(private array $streams = [])
    {
    }

    public function addStream(FuturesCmStreamInterface $stream): FuturesCmReceiverInterface
    {
        $this->streams[] = $stream;

        return $this;
    }

    /**
     * @return FuturesCmStreamInterface[]
     */
    protected function getStreams(): array
    {
        return $this->streams;
    }
}
