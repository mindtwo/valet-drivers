<?php

namespace Mindtwo\ValetDrivers\Commands;

use Symfony\Component\Console\Command\Command;

abstract class BaseCommand extends Command
{
    protected function getHerdDirectory(): string
    {
        return getenv('HOME').'/Library/Application Support/Herd/config/valet/Drivers/';
    }

    protected function getValetDirectory(): string
    {
        return getenv('HOME').'/.config/valet/Drivers/';
    }
}
