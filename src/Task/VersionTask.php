<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\Task;

class VersionTask extends ExecTaskBase
{

    protected string $taskName = 'PHPStan - Version';

    protected function initOptions(): static
    {
        parent::initOptions();

        $this->options['version'] = [
            'type' => 'option:flag',
            'cliName' => 'version',
            'value' => true,
        ];

        return $this;
    }

    protected function runProcessOutputs(): static
    {
        parent::runProcessOutputs();

        $parts = explode(' ', trim($this->actionStdOutput));
        $this->assets['version.full'] = end($parts);

        return $this;
    }
}
