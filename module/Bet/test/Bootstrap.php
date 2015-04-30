<?php

namespace SiteModuleTest;

use Zend\Loader\AutoloaderFactory;
use ApplicationModuleTest\ServiceManagerTestCase;

$basePath = __DIR__ . '/../../../../';

chdir(__DIR__);

$previousDir = '.';

while (!file_exists('config/application.config.php')) {
    $dir = dirname(getcwd());
    if ($previousDir === $dir) {
        throw new RuntimeException(
            'Unable to locate "config/application.config.php":'
            . ' is SiteModule in a subdir of your application skeleton?'
        );
    }

    $previousDir = $dir;
    chdir($dir);
}

if (
    !(@include_once __DIR__ . '/../vendor/autoload.php')
    && !(@include_once $basePath . 'vendor/autoload.php')
) {
    throw new RuntimeException(
        'vendor/autoload.php could not be found.'
        . ' Did you run `php composer.phar install`?'
    );
}

if (!$config = @include __DIR__ . '/TestConfiguration.php') {
    $config = require __DIR__ . '/TestConfiguration.php.dist';
}

AutoloaderFactory::factory(
    array(
        'Zend\Loader\StandardAutoloader' => array(
            'namespaces' => array(
                'ApplicationModuleTest' =>
                    $basePath .
                    'module/Application/test/ApplicationModuleTest',
                __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
            )
        )
    )
);

ServiceManagerTestCase::setServiceManagerConfiguration(
    require 'config/application.config.php'
);