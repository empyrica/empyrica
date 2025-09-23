<?php

namespace Empiriq\BinanceHistoryConnector\Common;

use Empiriq\BinanceHistoryConnector\Common\Helpers\ParallelIterator;
use Empiriq\BinanceHistoryConnector\Common\Interfaces\ReceiverInterface;
use Empiriq\BinanceHistoryConnector\Common\Interfaces\Streams\SpotStreamInterface;
use Iterator;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @internal
 */
abstract class AbstractReceiver implements ReceiverInterface
{
    /**
     * @return SpotStreamInterface[]
     */
    abstract protected function getStreams(): array;

    /**
     * @return Iterator<int, object>
     */
    public function run(SerializerInterface $serializer): Iterator
    {
        return new ParallelIterator(
            array_map(fn(SpotStreamInterface $stream) => $stream->run($serializer), $this->getStreams())
        );
    }
}
