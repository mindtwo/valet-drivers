<?php

namespace Mindtwo\ValetDrivers\Commands;

use DirectoryIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UninstallDriversCommand extends BaseCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'uninstall';

    /**
     * @var string
     */
    protected static $defaultDescription = 'Removes mindtwo custom valet drivers from both Valet and Herd unless specified otherwise.';

    private OutputInterface $output;

    protected function configure(): void
    {
        $this
            ->addOption('valet', null, InputOption::VALUE_NEGATABLE, 'Remove drivers from the Valet directory; negating skips Valet.')
            ->addOption('herd', null, InputOption::VALUE_NEGATABLE, 'Remove drivers from the Herd directory; negating skips Herd.');
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

        $output->writeln('Uninstalling custom valet drivers...');

        if ($input->getOption('no-valet') && $input->getOption('no-herd')) {
            $output->writeln('You cannot skip both valet and herd. Please specify at least one of them.', OutputInterface::VERBOSITY_VERBOSE);

            return Command::FAILURE;
        }

        $uninstallPaths = [];

        if ($input->getOption('herd') || (! $input->getOption('herd') && ! $input->getOption('no-herd'))) {
            $uninstallPaths[] = $this->getHerdDirectory();
        } else {
            $output->writeln('Skipping herd drivers...');
        }

        if ($input->getOption('valet') || (! $input->getOption('valet') && ! $input->getOption('no-valet'))) {
            $uninstallPaths[] = $this->getValetDirectory();
        } else {
            $output->writeln('Skipping valet drivers...');
        }

        foreach ($uninstallPaths as $uninstallPath) {
            $this->removeDriversFromDirectory($uninstallPath);
        }

        $output->writeln('Custom valet drivers uninstalled.');

        return Command::SUCCESS;
    }

    private function removeDriversFromDirectory(string $path): void
    {
        $this->output->writeln('Removing drivers from '.$path);
        if (! is_dir($path)) {
            $this->output->writeln('The specified path does not exist. Nothing to do...', OutputInterface::VERBOSITY_VERBOSE);

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

            $this->output->writeln(sprintf('Removing Driver: %s', $filename), OutputInterface::VERBOSITY_VERBOSE);

            unlink($path.$filename);
        }
    }
}
