<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\Option;

/**
 * @property array $options
 */
trait ExecOptions
{
    // region Option - workingDirectory.
    protected string $workingDirectory = '';

    public function getWorkingDirectory(): string
    {
        return $this->workingDirectory;
    }

    /**
     * @return $this
     */
    public function setWorkingDirectory(string $value)
    {
        $this->workingDirectory = $value;

        return $this;
    }
    // endregion

    // region Option - envVars.
    /**
     * @var array<string, string>
     */
    protected array $envVars = [];

    /**
     * @return array<string, string>
     */
    public function getEnvVars(): array
    {
        return $this->envVars;
    }

    /**
     * @param array<string, string> $envVars
     *
     * @return $this
     */
    public function setEnvVars(array $envVars)
    {
        $this->envVars = $envVars;

        return $this;
    }

    public function getEnvVar(string $name): ?string
    {
        return $this->envVars[$name] ?? null;
    }

    /**
     * @return $this
     */
    public function setEnvVar(string $name, ?string $value)
    {
        if ($value === null) {
            unset($this->envVars[$name]);

            return $this;
        }

        $this->envVars[$name] = $value;

        return $this;
    }
    // endregion

    // region Option - phpExecutable.
    protected string $phpExecutable = '';

    public function getPhpExecutable(): string
    {
        return $this->phpExecutable;
    }

    /**
     * @return $this
     */
    public function setPhpExecutable(string $phpExecutable)
    {
        $this->phpExecutable = $phpExecutable;

        return $this;
    }
    // endregion

    // region Option - phpstanExecutable.
    protected string $phpstanExecutable = 'vendor/bin/phpstan';

    public function getPhpstanExecutable(): string
    {
        return $this->phpstanExecutable;
    }

    /**
     * @return $this
     */
    public function setPhpstanExecutable(string $phpstanExecutable)
    {
        $this->phpstanExecutable = $phpstanExecutable;

        return $this;
    }
    // endregion

    // region Option - processTimeout.
    protected ?int $processTimeout = null;

    public function getProcessTimeout(): ?int
    {
        return $this->processTimeout;
    }

    /**
     * @return $this
     */
    public function setProcessTimeout(?int $processTimeout)
    {
        $this->processTimeout = $processTimeout;

        return $this;
    }
    // endregion

    // region Option - hideStdOutput
    protected bool $hideStdOutput = true;

    public function getHideStdOutput(): bool
    {
        return $this->hideStdOutput;
    }

    /**
     * @return $this
     */
    public function setHideStdOutput(bool $hideStdOutput)
    {
        $this->hideStdOutput = $hideStdOutput;

        return $this;
    }
    // endregion

    /**
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function setOptionsExec(array $options)
    {
        if (array_key_exists('workingDirectory', $options)) {
            $this->setWorkingDirectory($options['workingDirectory']);
        }

        if (array_key_exists('phpExecutable', $options)) {
            $this->setPhpExecutable($options['phpExecutable']);
        }

        if (array_key_exists('phpstanExecutable', $options)) {
            $this->setPhpstanExecutable($options['phpstanExecutable']);
        }

        if (array_key_exists('envVars', $options)) {
            $this->setEnvVars($options['envVars']);
        }

        if (array_key_exists('processTimeout', $options)) {
            $this->setProcessTimeout($options['processTimeout']);
        }

        if (array_key_exists('hideStdOutput', $options)) {
            $this->setHideStdOutput($options['hideStdOutput']);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function initOptionsExec()
    {
        $this->options += [
            'workingDirectory' => [
                'type' => 'other',
                'value' => $this->getWorkingDirectory(),
            ],
            'phpExecutable' => [
                'type' => 'other',
                'value' => $this->getPhpExecutable(),
            ],
            'phpstanExecutable' => [
                'type' => 'other',
                'value' => $this->getPhpstanExecutable() ?: 'vendor/bin/phpstan',
            ],
            'envVars' => [
                'type' => 'other',
                'value' => $this->getEnvVars(),
            ],
            'processTimeout' => [
                'type' => 'other',
                'value' => $this->getProcessTimeout(),
            ],
            'hideStdOutput' => [
                'type' => 'other',
                'value' => $this->getHideStdOutput(),
            ],
        ];

        return $this;
    }
}
