<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\Tests\Acceptance\Task;

use Sweetchuck\Robo\Phpstan\Tests\AcceptanceTester;
use Sweetchuck\Robo\Phpstan\Tests\Helper\RoboFiles\PhpstanRoboFile;

class VersionTaskCest
{
    public function version(AcceptanceTester $tester): void
    {
        $id = 'version';
        $tester->runRoboTask(
            $id,
            PhpstanRoboFile::class,
            'phpstan:version'
        );
        $exitCode = $tester->getRoboTaskExitCode($id);
        $stdOutput = $tester->getRoboTaskStdOutput($id);
        $stdError = $tester->getRoboTaskStdError($id);

        $tester->assertSame(0, $exitCode, 'exitCode');

        $tester->assertStringContainsString(
            "The version of the PHPStan is: '1.10.3'",
            $stdOutput,
            'stdOutput contains',
        );

        $tester->assertStringContainsString(
            " [PHPStan - Version] vendor/bin/phpstan --version\n",
            $stdError,
            'stdError contains',
        );
    }
}
