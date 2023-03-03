<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\Task;

use Robo\Contract\CommandInterface;
use Sweetchuck\Robo\Phpstan\Option\ExecOptions;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Process\Process;

abstract class ExecTaskBase extends TaskBase implements CommandInterface
{
    use ExecOptions;

    protected string $command = '';

    /**
     * @var string[]
     */
    protected array $cmdPattern = [];

    /**
     * @var string[]
     */
    protected array $cmdArgs = [];

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options): static
    {
        parent::setOptions($options);
        $this->setOptionsExec($options);

        return $this;
    }

    protected function initOptions(): static
    {
        parent::initOptions();
        $this->initOptionsExec();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommand()
    {
        $this->initOptions();

        return $this
            ->getCommandInit()
            ->getCommandPhpExecutable()
            ->getCommandPhpstanExecutable()
            ->getCommandPhpstanCommand()
            ->getCommandPhpstanOptions()
            ->getCommandPhpstanArguments()
            ->getCommandBuild();
    }

    protected function getCommandInit(): static
    {
        $this->cmdPattern = [];
        $this->cmdArgs = [];

        return $this;
    }

    protected function getCommandPhpExecutable(): static
    {
        if ($this->options['phpExecutable']['value']) {
            $this->cmdPattern[] = '%s';
            $this->cmdArgs[] = escapeshellcmd($this->options['phpExecutable']['value']);
        }

        return $this;
    }

    protected function getCommandPhpstanExecutable(): static
    {
        if ($this->options['phpstanExecutable']['value']) {
            $this->cmdPattern[] = '%s';
            $this->cmdArgs[] = escapeshellcmd($this->options['phpstanExecutable']['value']);
        }

        return $this;
    }

    protected function getCommandPhpstanCommand(): static
    {
        if (!empty($this->options['command']['value'])) {
            $this->cmdPattern[] = $this->options['command']['value'];
        }

        return $this;
    }

    protected function getCommandPhpstanOptions(): static
    {
        foreach ($this->options as $optionName => $option) {
            if (mb_strpos($option['type'], 'option:') !== 0) {
                continue;
            }

            $cliName = $option['cliName'] ?? $optionName;
            switch ($option['type']) {
                case 'option:flag':
                    if ($option['value'] !== false) {
                        $this->cmdPattern[] = "--$cliName";
                    }
                    break;

                case 'option:tri-state':
                    if ($option['value'] === true) {
                        $this->cmdPattern[] = "--$cliName";
                    } elseif ($option['value'] === false) {
                        $this->cmdPattern[] = "--no-$cliName";
                    }
                    break;

                case 'option:value-required':
                    if ($option['value'] !== null) {
                        $this->cmdPattern[] = "--$cliName=%s";
                        $this->cmdArgs[] = escapeshellarg((string) $option['value']);
                    }
                    break;

                case 'option:verbose':
                    if ($option['value'] > 0) {
                        $this->cmdPattern[] = '-' . str_repeat($cliName, min($option['value'], 3));
                    }
                    break;

                default:
                    throw new \InvalidArgumentException("Option type {$option['type']} is not supported");
            }
        }

        return $this;
    }

    protected function getCommandPhpstanArguments(): static
    {
        foreach ($this->options as $option) {
            if (mb_strpos($option['type'], 'argument:') !== 0) {
                continue;
            }

            switch ($option['type']) {
                case 'argument:single':
                    if ($option['value'] !== null) {
                        $this->cmdPattern[] = '%s';
                        $this->cmdArgs[] = escapeshellarg($option['value']);
                    }
                    break;

                case 'argument:multiple':
                    foreach ($option['value'] as $value) {
                        $this->cmdPattern[] = '%s';
                        $this->cmdArgs[] = escapeshellarg($value);
                    }
                    break;
            }
        }

        return $this;
    }

    protected function getCommandBuild(): string
    {
        return vsprintf(implode(' ', $this->cmdPattern), $this->cmdArgs);
    }

    protected function runPrepare(): static
    {
        parent::runPrepare();
        $this->command = $this->getCommand();

        return $this;
    }

    protected function runHeader(): static
    {
        $this->printTaskInfo(
            '<info>{command}</info>',
            [
                'command' => $this->command,
            ]
        );

        return $this;
    }

    protected function runAction(): static
    {
        $processInner = Process::fromShellCommandline(
            $this->command,
            $this->getWorkingDirectory() ?: '.',
            $this->options['envVars']['value'] ?: null,
            null,
            $this->options['processTimeout']['value'],
        );

        $process = $this
            ->getProcessHelper()
            ->run(
                $this->output(),
                $processInner,
                null,
                function (string $type, string $data): void {
                    $this->processRunCallback($type, $data);
                }
            );

        $this->actionExitCode = (int) $process->getExitCode();
        $this->actionStdOutput = $process->getOutput();
        $this->actionStdError = $process->getErrorOutput();

        return $this;
    }

    protected function processRunCallback(string $type, string $data): void
    {
        switch ($type) {
            case Process::OUT:
                if (!$this->getHideStdOutput()) {
                    $this->output()->write($data);
                }
                break;

            case Process::ERR:
                if (!$this->getHideStdError()) {
                    $this->printTaskError($data);
                }
                break;
        }
    }

    protected function getProcessHelper(): ProcessHelper
    {
        return $this
            ->getContainer()
            ->get('application')
            ->getHelperSet()
            ->get('process');
    }
}
