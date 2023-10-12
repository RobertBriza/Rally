<?php

declare(strict_types=1);

namespace app;

use app\AppModule\Service\TemplateRenderer;
use Nette\Bootstrap\Configurator;

class Bootstrap
{
    public static function boot(): Configurator
    {
        $configurator = new Configurator;
        $appDir = dirname(__DIR__);

        $configurator->setDebugMode(true);
        $configurator->enableTracy($appDir . '/log');

        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING);

        $configurator->setTimeZone('Europe/Prague');
        $configurator->setTempDirectory($appDir . '/temp');

        $configurator->createRobotLoader()
            ->addDirectory(__DIR__)
            ->register();

        $dotenv = \Dotenv\Dotenv::createImmutable($appDir);
        $dotenv->load();

        $originDir = __DIR__.'/../deploy/local';

        $twigLoader = new \Twig\Loader\FilesystemLoader($originDir);
        $twig = new \Twig\Environment($twigLoader);

        $resultDir = $_ENV["SECRET_DIR"];
        $renderer = new TemplateRenderer($twig);

        if (!$renderer->isGenerated($resultDir)) {
            $renderer->renderAndSaveTemplates($originDir, $resultDir);
        }

        //dynamic config files
        foreach (glob($resultDir . '/*.neon') as $neonFile) {
            $configurator->addConfig($neonFile);
        }

        //static config files
        foreach (glob($appDir . '/config/*.neon') as $neonFile) {
            $configurator->addConfig($neonFile);
        }

        return $configurator;
    }
}
