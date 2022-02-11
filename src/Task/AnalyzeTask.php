<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\Task;

use Sweetchuck\LintReport\ReporterInterface;
use Sweetchuck\Robo\Phpstan\LintReportWrapper\ReportWrapper;
use Sweetchuck\Robo\Phpstan\Option\AnalyzeOptions;

class AnalyzeTask extends ExecTaskBase
{
    use AnalyzeOptions;

    const EXIT_CODE_OK = 0;

    const EXIT_CODE_WARNING = 2;

    const EXIT_CODE_ERROR = 1;

    const EXIT_CODE_UNKNOWN = 3;

    protected string $taskName = 'PHPStan - Analyze';

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);
        $this->setOptionsAnalise($options);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function initOptions()
    {
        parent::initOptions();
        $this->initOptionsAnalise();
        $this->options['command'] = [
            'type' => 'command',
            'value' => 'analyze',
        ];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function runProcessOutputs()
    {
        parent::runProcessOutputs();

        if (!$this->isLintSuccess() || !$this->actionStdOutput) {
            return $this;
        }

        $errorFormat = $this->options['error-format']['value'];
        if ($errorFormat !== 'json') {
            $this->output()->write($this->actionStdOutput);

            return $this;
        }

        $this->assets['report.raw'] = json_decode($this->actionStdOutput, true);
        $this->assets['report.wrapper'] = new ReportWrapper($this->assets['report.raw']);

        $lintReporters = $this->initLintReporters();
        if (!$lintReporters) {
            $this->output()->write($this->actionStdOutput);

            return $this;
        }

        foreach ($lintReporters as $lintReporter) {
            $lintReporter
                ->setReportWrapper($this->assets['report.wrapper'])
                ->generate();
        }

        return $this;
    }

    protected function getTaskResultCode(): int
    {
        /** @var null|\Sweetchuck\LintReport\ReportWrapperInterface $reportWrapper */
        $reportWrapper = $this->assets['report.wrapper'] ?? null;
        if (!$reportWrapper) {
            return $this->actionExitCode;
        }

        switch ($this->getFailOn()) {
            case 'never':
                return static::EXIT_CODE_OK;

            case 'warning':
                if ($reportWrapper->numOfErrors()) {
                    return static::EXIT_CODE_ERROR;
                }

                return $reportWrapper->numOfWarnings() ?
                    static::EXIT_CODE_WARNING
                    : static::EXIT_CODE_OK;
        }

        return $reportWrapper->numOfErrors() ?
            static::EXIT_CODE_ERROR
            : static::EXIT_CODE_OK;
    }

    /**
     * @return \Sweetchuck\LintReport\ReporterInterface[]
     */
    protected function initLintReporters(): array
    {
        $lintReporters = [];
        $container = $this->getContainer();
        foreach ($this->getLintReporters() as $id => $lintReporter) {
            if ($lintReporter === false) {
                continue;
            }

            if (!$lintReporter) {
                $lintReporter = $container->get($id);
            } elseif (is_string($lintReporter)) {
                $lintReporter = $container->get($lintReporter);
            }

            if ($lintReporter instanceof ReporterInterface) {
                $lintReporters[$id] = $lintReporter;
                if (!$lintReporter->getDestination()) {
                    $lintReporter->setDestination($this->output());
                }
            }
        }

        return $lintReporters;
    }

    /**
     * Returns true if the lint ran successfully.
     *
     * Returns true even if there was any code style error or warning.
     */
    protected function isLintSuccess(): bool
    {
        return in_array($this->actionExitCode, $this->lintSuccessExitCodes());
    }

    /**
     * @return int[]
     */
    protected function lintSuccessExitCodes(): array
    {
        return [
            static::EXIT_CODE_OK,
            static::EXIT_CODE_WARNING,
            static::EXIT_CODE_ERROR,
        ];
    }
}
