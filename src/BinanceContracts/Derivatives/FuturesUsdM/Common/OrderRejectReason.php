<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

enum OrderRejectReason: string
{
    case NONE = 'NONE'; // The order was not rejected.
    case INSUFFICIENT_BALANCES = 'INSUFFICIENT_BALANCES'; // Account has insufficient balance for requested action.
    case STOP_PRICE_WOULD_TRIGGER_IMMEDIATELY = 'STOP_PRICE_WOULD_TRIGGER_IMMEDIATELY'; // Order would trigger immediately.
    case WOULD_MATCH_IMMEDIATELY = 'WOULD_MATCH_IMMEDIATELY'; // Order would immediately match and take.
    case OCO_BAD_PRICES = 'OCO_BAD_PRICES'; // The relationship of the prices for the orders is not correct.
}
