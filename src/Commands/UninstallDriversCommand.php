<?php

namespace Mindtwo\ValetDrivers\Commands;

use DirectoryIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UninstallDriversCommand extends Command
{

    /**
     * @var string
     */
    protected static $defaultName = 'uninstall';

    /**
     * @var string
     */
    protected static $defaultDescription = 'Uninstall the custom valet drivers used for mindtwo projects. If no option is set, we will remove the drivers from both valet and herd.';

    protected function configure(): void
    {
        $this
            ->addOption('valet', null, InputOption::VALUE_NEGATABLE, 'Skip valet if option is negated. If it is set we will only remove the drivers from the valet directory.')
            ->addOption('herd', null, InputOption::VALUE_NEGATABLE, 'Skip herd if option is negated. If it is set we will only remove the drivers from the herd directory.')
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
        $output->writeln('Uninstalling custom valet drivers...');

        if ($input->getOption('no-valet') && $input->getOption('no-herd')) {
            $output->writeln('You cannot skip both valet and herd. Please specify at least one of them.', OutputInterface::VERBOSITY_VERBOSE);
            return Command::FAILURE;
        }

        $uninstallPaths = [];

        if ($input->getOption('herd') || (!$input->getOption('herd') && !$input->getOption('no-herd'))) {
            $uninstallPaths[] = $this->getHerdDirectory();
        } else {
            $output->writeln('Skipping herd drivers...');
        }

        if ($input->getOption('valet') || (!$input->getOption('valet') && !$input->getOption('no-valet'))) {
            $uninstallPaths[] = $this->getValetDirectory();
        } else {
            $output->writeln('Skipping valet drivers...');
        }

        foreach ($uninstallPaths as $uninstallPath) {
            $this->removeDriversFromDirectory($uninstallPath, $output);
        }

        $output->writeln('Custom valet drivers uninstalled.');

        return Command::SUCCESS;
    }

    private function removeDriversFromDirectory(string $path, OutputInterface $outputInterface): void
    {
        $outputInterface->writeln('Removing drivers from ' . $path);
        if (!is_dir($path)) {
            $outputInterface->writeln('The specified path does not exist. Nothing to do...', OutputInterface::VERBOSITY_VERBOSE);
            return;
        }

        $iterator = new DirectoryIterator($path);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isDot()) {
                continue;
            }

            $filename = $fileinfo->getFilename();

            if ($fileinfo->isDot()) {
                continue;
            }

            $outputInterface->writeln(sprintf('Removing Driver: %s', $filename), OutputInterface::VERBOSITY_VERBOSE);

            unlink($path . $filename);
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
