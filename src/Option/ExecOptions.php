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

    public function setWorkingDirectory(string $value): static
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
     */
    public function setEnvVars(array $envVars): static
    {
        $this->envVars = $envVars;

        return $this;
    }

    public function getEnvVar(string $name): ?string
    {
        return $this->envVars[$name] ?? null;
    }

    public function setEnvVar(string $name, ?string $value): static
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

    public function setPhpExecutable(string $phpExecutable): static
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

    public function setPhpstanExecutable(string $phpstanExecutable): static
    {
        $this->phpstanExecutable = $phpstanExecutable;

        return $this;
    }
    // endregion

    // region Option - configuration
    protected ?string $configuration = null;

    public function getConfiguration(): ?string
    {
        return $this->configuration;
    }

    public function setConfiguration(?string $configuration): static
    {
        $this->configuration = $configuration;

        return $this;
    }
    // endregion

    // region Option - processTimeout
    protected ?int $processTimeout = null;

    public function getProcessTimeout(): ?int
    {
        return $this->processTimeout;
    }

    public function setProcessTimeout(?int $processTimeout): static
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

    public function setHideStdOutput(bool $hideStdOutput): static
    {
        $this->hideStdOutput = $hideStdOutput;

        return $this;
    }
    // endregion

    // region Option - hideStdError
    protected bool $hideStdError = true;

    public function getHideStdError(): bool
    {
        return $this->hideStdError;
    }

    public function setHideStdError(bool $hideStdError): static
    {
        $this->hideStdError = $hideStdError;

        return $this;
    }
    // endregion

    // region Option - noProgress
    protected bool $noProgress = false;

    public function getNoProgress(): bool
    {
        return $this->noProgress;
    }

    public function setNoProgress(bool $noProgress): static
    {
        $this->noProgress = $noProgress;

        return $this;
    }
    // endregion

    // region Option - debug
    protected bool $debug = false;

    public function getDebug(): bool
    {
        return $this->debug;
    }

    public function setDebug(bool $debug): static
    {
        $this->debug = $debug;

        return $this;
    }
    // endregion

    // region Option - quiet
    protected bool $quiet = false;

    public function getQuiet(): bool
    {
        return $this->quiet;
    }

    public function setQuiet(bool $quiet): static
    {
        $this->quiet = $quiet;

        return $this;
    }
    // endregion

    // region Option - ansi
    protected ?bool $ansi = null;

    public function getAnsi(): ?bool
    {
        return $this->ansi;
    }

    public function setAnsi(?bool $ansi): static
    {
        $this->ansi = $ansi;

        return $this;
    }
    // endregion

    // region Option - noInteraction
    protected bool $noInteraction = false;

    public function getNoInteraction(): bool
    {
        return $this->noInteraction;
    }

    public function setNoInteraction(bool $noInteraction): static
    {
        $this->noInteraction = $noInteraction;

        return $this;
    }
    // endregion

    // region Option - verbose
    protected int $verbose = 0;

    public function getVerbose(): int
    {
        return $this->verbose;
    }

    public function setVerbose(int $verbose): static
    {
        assert($verbose > 0 && $verbose < 4, "Allowed values: 0-3; Current value: $verbose");
        $this->verbose = $verbose;

        return $this;
    }
    // endregion

    /**
     * @param array<string, mixed> $options
     */
    protected function setOptionsExec(array $options): static
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

        if (array_key_exists('configuration', $options)) {
            $this->setConfiguration($options['configuration']);
        }

        if (array_key_exists('processTimeout', $options)) {
            $this->setProcessTimeout($options['processTimeout']);
        }

        if (array_key_exists('hideStdOutput', $options)) {
            $this->setHideStdOutput($options['hideStdOutput']);
        }

        if (array_key_exists('hideStdError', $options)) {
            $this->setHideStdError($options['hideStdError']);
        }

        if (array_key_exists('noProgress', $options)) {
            $this->setNoProgress($options['noProgress']);
        }

        if (array_key_exists('debug', $options)) {
            $this->setDebug($options['debug']);
        }

        if (array_key_exists('quiet', $options)) {
            $this->setQuiet($options['quiet']);
        }

        if (array_key_exists('ansi', $options)) {
            $this->setAnsi($options['ansi']);
        }

        if (array_key_exists('noInteraction', $options)) {
            $this->setNoInteraction($options['noInteraction']);
        }

        if (array_key_exists('verbose', $options)) {
            $this->setVerbose($options['verbose']);
        }

        return $this;
    }

    protected function initOptionsExec(): static
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
            'configuration' => [
                'type' => 'option:value-required',
                'value' => $this->getConfiguration(),
            ],
            'processTimeout' => [
                'type' => 'other',
                'value' => $this->getProcessTimeout(),
            ],
            'hideStdOutput' => [
                'type' => 'other',
                'value' => $this->getHideStdOutput(),
            ],
            'no-progress' => [
                'type' => 'option:flag',
                'value' => $this->getNoProgress(),
            ],
            'debug' => [
                'type' => 'option:flag',
                'value' => $this->getDebug(),
            ],
            'quiet' => [
                'type' => 'option:flag',
                'value' => $this->getQuiet(),
            ],
            'ansi' => [
                'type' => 'option:tri-state',
                'value' => $this->getAnsi(),
            ],
            'no-interaction' => [
                'type' => 'option:flag',
                'value' => $this->getNoInteraction(),
            ],
            'verbose' => [
                'cliName' => 'v',
                'type' => 'option:verbose',
                'value' => $this->getVerbose(),
            ],
        ];

        return $this;
    }
}
