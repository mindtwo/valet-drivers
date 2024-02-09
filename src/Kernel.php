<?php

declare(strict_types=1);

namespace Mindtwo\ValetDrivers;

use Symfony\Component\Console\Application;

class Kernel
{
    public static function setup(Application $application): void
    {
        $application->add(new Commands\InstallDriversCommand());
        $application->add(new Commands\UninstallDriversCommand());
    }
}
