<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\Tests\Helper\RoboFiles;

use Robo\State\Data;
use Robo\Tasks;
use Robo\Contract\TaskInterface;
use Sweetchuck\Robo\Phpstan\PhpstanTaskLoader;

class PhpstanRoboFile extends Tasks
{
    use PhpstanTaskLoader;

    protected function output()
    {
        return $this->getContainer()->get('output');
    }

    public function phpstanVersion(): TaskInterface
    {
        return $this
            ->collectionBuilder()
            ->addTask(
                $this
                    ->taskPhpstanVersion()
                    ->setPhpstanExecutable('vendor/bin/phpstan')
            )
            ->addCode(function (Data $data) {
                $this
                    ->output()
                    ->writeln("The version of the PHPStan is: '{$data['version.full']}'");
            });
    }
}
