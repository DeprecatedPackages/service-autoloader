<?php

/**
 * @Test Minetro\Autoloader\DI\ServiceAutoload - Full autoloading
 */

use Minetro\Autoloader\DI\ServiceAutoloadExtension;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;
use Tests\Autoload\Services\TestAnnotation;
use Tests\Autoload\Services\TestInterface;

require_once __DIR__ . '/../../bootstrap.php';

test(function () {
    $loader = new ContainerLoader(TEMP_DIR, TRUE);

    $class = $loader->load(function (Compiler $compiler) {
        $compiler->addExtension('autoload', new ServiceAutoloadExtension());
        $compiler->addConfig(['parameters' => ['appDir' => TESTER_DIR . '/cases/Autoload']]);
    }, time());

    /** @var Container $container */
    $container = new $class();
    Assert::type(Container::class, $container);

    // 1st - annotations
    Assert::count(1, $container->findByType(TestAnnotation::class));
    Assert::type(TestAnnotation::class, $container->getService('autoload.1'));

    // 2nd - interfaces
    Assert::count(1, $container->findByType(TestInterface::class));
    Assert::type(TestInterface::class, $container->getService('autoload.2'));
});
