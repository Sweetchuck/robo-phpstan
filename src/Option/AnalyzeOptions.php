<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\Option;

/**
 * @property array $options
 */
trait AnalyzeOptions
{
    // region Option - configuration
    protected ?string $configuration = null;

    public function getConfiguration(): ?string
    {
        return $this->configuration;
    }

    /**
     * @return $this
     */
    public function setConfiguration(?string $configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }
    // endregion

    // region Option - level
    protected ?int $level = null;

    public function getLevel(): ?int
    {
        return $this->level;
    }

    /**
     * @return $this
     */
    public function setLevel(?int $level)
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

    /**
     * @return $this
     */
    public function setAutoloadFile(?string $autoloadFile)
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

    /**
     * @return $this
     */
    public function setErrorFormat(?string $errorFormat)
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

    /**
     * @return $this
     */
    public function setMemoryLimit(?string $memoryLimit)
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

    /**
     * @return $this
     */
    public function setXdebug(bool $xdebug)
    {
        $this->xdebug = $xdebug;

        return $this;
    }
    // endregion

    // region Options - paths
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
     *
     * @return $this
     */
    public function setPaths(array $paths)
    {
        $this->paths = $paths;

        return $this;
    }
    // endregion

    //region Option - lintReporters
    /**
     * @var null[]|bool[]|string[]|\Sweetchuck\LintReport\ReporterInterface[]
     */
    protected array $lintReporters = [];

    /**
     * @return null[]|bool[]|string[]|\Sweetchuck\LintReport\ReporterInterface[]
     */
    public function getLintReporters(): array
    {
        return $this->lintReporters;
    }

    /**
     * @param null[]|bool[]|string[]|\Sweetchuck\LintReport\ReporterInterface[] $lintReporters
     *
     * @return $this
     */
    public function setLintReporters(array $lintReporters)
    {
        $this->lintReporters = $lintReporters;

        return $this;
    }

    /**
     * @param string $id
     * @param null|bool|string|\Sweetchuck\LintReport\ReporterInterface $lintReporter
     *
     * @return $this
     */
    public function addLintReporter(string $id, $lintReporter = null)
    {
        $this->lintReporters[$id] = $lintReporter;

        return $this;
    }

    /**
     * @return $this
     */
    public function removeLintReporter(string $id)
    {
        unset($this->lintReporters[$id]);

        return $this;
    }
    //endregion

    // region Option - failOn
    protected string $failOn = 'warning';

    public function getFailOn(): string
    {
        return $this->failOn;
    }

    /**
     * @return $this
     */
    public function setFailOn(string $value)
    {
        $this->failOn = $value;

        return $this;
    }
    //endregion

    /**
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function setOptionsAnalise(array $options)
    {
        if (array_key_exists('configuration', $options)) {
            $this->setConfiguration($options['configuration']);
        }

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

    /**
     * @return $this
     */
    protected function initOptionsAnalise()
    {
        $this->options += [
            'configuration' => [
                'type' => 'option:value-required',
                'value' => $this->getConfiguration(),
            ],
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
