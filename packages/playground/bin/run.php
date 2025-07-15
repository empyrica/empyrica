#!/usr/bin/env php
<?php

if (!is_dir(dirname(__DIR__) . '/vendor')) {
    throw new LogicException('Dependencies are missing. Try running "composer install".');
}

if (!is_file(dirname(__DIR__) . '/vendor/autoload_runtime.php')) {
    throw new LogicException('Symfony Runtime is missing. Try running "composer require symfony/runtime".');
}

$_SERVER['APP_RUNTIME_OPTIONS']['project_dir'] = dirname(__DIR__, 1);

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

if (!defined('ENTRYPOINT')) {
    define('ENTRYPOINT', $_SERVER['argv'][0]);
}

return function (array $context) {
    $input = new Symfony\Component\Console\Input\ArgvInput();
    $kernel = new App\Kernel($input->getFirstArgument() ?? $context['APP_ENV'], (bool)$context['APP_DEBUG']);
    $application = new Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
    $application->setDefaultCommand(ENTRYPOINT, true);

    return $application;
};
