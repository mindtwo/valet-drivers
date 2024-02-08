<?php

namespace Mindtwo\ValetDrivers\Commands;

use DirectoryIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallDriversCommand extends Command
{

    /**
     * @var string
     */
    protected static $defaultName = 'install';

    /**
     * @var string
     */
    protected static $defaultDescription = 'Install the custom valet drivers used for mindtwo projects.';

    protected function configure(): void
    {
        $this
            ->addOption('valet', null, InputOption::VALUE_NONE, 'Install the drivers for Laravel Valet using the default directory.')
            ->addOption('herd', null, InputOption::VALUE_NONE, 'Install the drivers for Laravel Herd using the default directory.')
            ->addOption('path', 'p', InputOption::VALUE_REQUIRED, 'Path to the valet drivers directory.')
        ;
    }

    /**
     * Execute the command
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int 0 if everything went fine, or an exit code.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
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

        $this->installDriversToPath($targetPath, $output);

        $output->writeln('Custom valet drivers installed. 🚀🚀');

        return Command::SUCCESS;
    }

    private function installDriversToPath(string $path, OutputInterface $outputInterface): void
    {
        $sourcePath = __DIR__ . '/../../drivers/';


        $outputInterface->writeln('Installing drivers to ' . $path);
        if (!is_dir($path)) {
            $outputInterface->writeln('The specified path does not exist. Creating it...', OutputInterface::VERBOSITY_VERBOSE);
            mkdir($path, 0755, true);
        }

        $iterator = new DirectoryIterator($sourcePath);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isDot()) {
                continue;
            }

            $filename = $fileinfo->getFilename();

            if (is_file($path . $filename)) {
                unlink($path . $filename);
            }

            $outputInterface->writeln(sprintf('Installing Driver: %s', $filename), OutputInterface::VERBOSITY_VERBOSE);

            symlink(
                $sourcePath . $filename,
                $path . $filename
            );
        }
    }

    private function getHerdDirectory(): string
    {
        return getenv('HOME') . '/Library/Application Support/Herd/config/valet/Drivers/';
    }

    private function getValetDirectory(): string
    {
        return getenv('HOME') . '/.config/valet/Drivers/';
    }

}
