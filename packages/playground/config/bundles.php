<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Empiriq\BinanceHistoryConnector\ConnectorBundle::class => ['backtrade' => true],
    Empiriq\BinanceApiConnector\ConnectorBundle::class => ['realtrade' => true],
    Empiriq\Server\ServerBundle::class => ['all' => true],
];
