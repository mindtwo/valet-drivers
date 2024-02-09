<?php

namespace Mindtwo\ValetDrivers\Commands;

use DirectoryIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallDriversCommand extends BaseCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'install';

    /**
     * @var string
     */
    protected static $defaultDescription = 'Effortlessly install custom valet drivers for enhanced development on mindtwo projects.';

    private OutputInterface $output;

    protected function configure(): void
    {
        $this
            ->addOption('valet', null, InputOption::VALUE_NONE, 'Installs drivers for Laravel Valet in the default directory.')
            ->addOption('herd', null, InputOption::VALUE_NONE, 'Installs drivers for Laravel Herd in the default directory.')
            ->addOption('path', 'p', InputOption::VALUE_REQUIRED, 'Custom path for driver installation.');

    }

    /**
     * Execute the command
     *
     * @return int 0 if everything went fine, or an exit code.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Store the output interface for later use
        $this->output = $output;

        $output->writeln('Installing custom valet drivers...');

        // Per default, we install the drivers for Laravel Valet
        $targetPath = $this->getValetDirectory();

        if ($input->getOption('herd')) {
            $targetPath = $this->getHerdDirectory();
            $output->writeln('Option `herd` detected. Installing drivers for Laravel Herd.');
        }

        if ($input->getOption('path')) {
            $targetPath = $input->getArgument('path');

            $output->writeln(sprintf('Argument `path` detected. Installing drivers to %s.', $targetPath));
        }

        $this->installDriversToPath($targetPath);

        $output->writeln('Custom valet drivers installed. 🚀🚀');

        return Command::SUCCESS;
    }

    private function installDriversToPath(string $path): void
    {
        $sourcePath = __DIR__.'/../../drivers/';

        $this->output->writeln('Installing drivers to '.$path);
        if (! is_dir($path)) {
            $this->output->writeln('The specified path does not exist. Creating it...', OutputInterface::VERBOSITY_VERBOSE);
            mkdir($path, 0755, true);
        }

        $iterator = new DirectoryIterator($sourcePath);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isDot()) {
                continue;
            }

            $filename = $fileinfo->getFilename();

            if (is_file($path.$filename)) {
                unlink($path.$filename);
            }

            $this->output->writeln(sprintf('Installing Driver: %s', $filename), OutputInterface::VERBOSITY_VERBOSE);

            symlink(
                $sourcePath.$filename,
                $path.$filename
            );
        }
    }
}
