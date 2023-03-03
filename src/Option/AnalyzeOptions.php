<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\Option;

/**
 * @property array $options
 */
trait AnalyzeOptions
{
    // region Option - level
    protected ?int $level = null;

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): static
    {
        $this->level = $level;

        return $this;
    }
    // endregion

    // region Option - autoloadFile
    protected ?string $autoloadFile = null;

    public function getAutoloadFile(): ?string
    {
        return $this->autoloadFile;
    }

    public function setAutoloadFile(?string $autoloadFile): static
    {
        $this->autoloadFile = $autoloadFile;

        return $this;
    }
    // endregion

    // region Option - errorFormat
    protected ?string $errorFormat = null;

    public function getErrorFormat(): ?string
    {
        return $this->errorFormat;
    }

    public function setErrorFormat(?string $errorFormat): static
    {
        $this->errorFormat = $errorFormat;

        return $this;
    }
    // endregion

    // region Option - memoryLimit
    protected ?string $memoryLimit = null;

    public function getMemoryLimit(): ?string
    {
        return $this->memoryLimit;
    }

    public function setMemoryLimit(?string $memoryLimit): static
    {
        $this->memoryLimit = $memoryLimit;

        return $this;
    }
    // endregion

    // region Option - xdebug
    protected bool $xdebug = false;

    public function getXdebug(): bool
    {
        return $this->xdebug;
    }

    public function setXdebug(bool $xdebug): static
    {
        $this->xdebug = $xdebug;

        return $this;
    }
    // endregion

    // region Option - paths
    /**
     * @var string[]
     */
    protected array $paths = [];

    /**
     * @return string[]
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * @param string[] $paths
     */
    public function setPaths(array $paths): static
    {
        $this->paths = $paths;

        return $this;
    }
    // endregion

    // region Option - failOn
    protected string $failOn = 'warning';

    public function getFailOn(): string
    {
        return $this->failOn;
    }

    public function setFailOn(string $value): static
    {
        $this->failOn = $value;

        return $this;
    }
    //endregion

    /**
     * @param array<string, mixed> $options
     */
    protected function setOptionsAnalise(array $options): static
    {
        if (array_key_exists('level', $options)) {
            $this->setLevel($options['level']);
        }

        if (array_key_exists('autoloadFile', $options)) {
            $this->setAutoloadFile($options['autoloadFile']);
        }

        if (array_key_exists('errorFormat', $options)) {
            $this->setErrorFormat($options['errorFormat']);
        }

        if (array_key_exists('memoryLimit', $options)) {
            $this->setMemoryLimit($options['memoryLimit']);
        }

        if (array_key_exists('xdebug', $options)) {
            $this->setXdebug($options['xdebug']);
        }

        if (array_key_exists('paths', $options)) {
            $this->setPaths($options['paths']);
        }

        if (array_key_exists('failOn', $options)) {
            $this->setFailOn($options['failOn']);
        }

        return $this;
    }

    protected function initOptionsAnalise(): static
    {
        $this->options += [
            'level' => [
                'type' => 'option:value-required',
                'value' => $this->getLevel(),
            ],
            'autoload-file' => [
                'type' => 'option:value-required',
                'value' => $this->getAutoloadFile(),
            ],
            'error-format' => [
                'type' => 'option:value-required',
                'value' => $this->getErrorFormat(),
            ],
            'memory-limit' => [
                'type' => 'option:value-required',
                'value' => $this->getMemoryLimit(),
            ],
            'xdebug' => [
                'type' => 'option:flag',
                'value' => $this->getXdebug(),
            ],
            'paths' => [
                'type' => 'argument:multiple',
                'value' => $this->getPaths(),
            ],
        ];

        return $this;
    }
}
