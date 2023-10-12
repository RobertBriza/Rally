<?php

namespace app\AppModule\Service;

use Nette\Utils\FileSystem;
use Twig\Environment;

readonly class TemplateRenderer
{
    public function __construct(private Environment $twig)
    {
    }

    public function isGenerated(string $resultDir): bool
    {
        return !empty(glob($resultDir . "*.twig"));
    }

    public function renderAndSaveTemplates(string $originDir, string $resultDir): void
    {
        foreach (glob($originDir . "/*.twig") as $twigFile) {
            if (!preg_match('#([^/]+)\.twig$#', $twigFile, $matches)) {
                throw new \RuntimeException("Invalid twig file name");
            }

            $fileName = $matches[1];
            $content = $this->twig->render($fileName. ".twig", $_ENV);
            $configFilePath = \sprintf("%s/%s", $resultDir, str_replace('.twig', '', $fileName));

            if (!file_exists($configFilePath)) {
                FileSystem::createDir(dirname($configFilePath));
            }

            FileSystem::write($configFilePath, $content);
        }
    }
}
