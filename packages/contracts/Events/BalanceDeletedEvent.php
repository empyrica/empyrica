<?php

namespace Empiriq\Contracts\Events;

use Empiriq\Contracts\Entities\BalanceEntity;

readonly class BalanceDeletedEvent
{
    /**
     * @param BalanceEntity $entity
     */
    public function __construct(
        public BalanceEntity $entity,
    ) {
    }
}
