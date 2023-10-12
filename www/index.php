<?php

declare(strict_types=1);

@!include __DIR__ . '/../vendor/autoload.php';

$configurator = app\Bootstrap::boot();
$container = $configurator->createContainer();
$application = $container->getByType(Nette\Application\Application::class);
$application->run();
