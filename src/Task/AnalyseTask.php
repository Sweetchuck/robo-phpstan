<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\Task;

class AnalyseTask extends ExecTaskBase
{

    protected string $taskName = 'PHPStan - Analyse';

    /**
     * {@inheritdoc}
     */
    protected function initOptions()
    {
        parent::initOptions();

        //$this->options['level'] = [];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function runProcessOutputs()
    {
        parent::runProcessOutputs();

        $parts = explode(' ', trim($this->actionStdOutput));
        $this->assets['version.full'] = end($parts);

        return $this;
    }
}
