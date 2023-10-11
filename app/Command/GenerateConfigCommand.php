<?php

namespace app\Command;

use app\Service\TemplateRenderer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dotenv\Dotenv;

#[AsCommand(name: self::NAME)]
class GenerateConfigCommand extends Command
{
    public const NAME = 'app:generate-config';

    public function __construct(private TemplateRenderer $renderer)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName(self::NAME);
        $this->setDescription('Generate dynamic neon files with sensitive data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->renderer->renderAndSaveTemplates(
            __DIR__ . "/../../deploy/local/",
            $_ENV["SECRET_DIR"]
        );

        $output->writeln('<info>Config files has been generated successfully!</info>');

        return Command::SUCCESS;
    }
}