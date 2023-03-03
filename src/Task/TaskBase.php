<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\Task;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Robo\Common\OutputAwareTrait;
use Consolidation\AnnotatedCommand\Output\OutputAwareInterface;
use Robo\Result;
use Robo\Task\BaseTask;
use Robo\TaskInfo;
use Sweetchuck\Robo\Phpstan\Option\BaseOptions;

abstract class TaskBase extends BaseTask implements ContainerAwareInterface, OutputAwareInterface
{
    use ContainerAwareTrait;
    use OutputAwareTrait;
    use BaseOptions;

    /**
     * @var array<string, mixed>
     */
    protected array $options = [];

    protected int $actionExitCode = 0;

    protected string $actionStdOutput = '';

    protected string $actionStdError = '';

    /**
     * @var array<string, mixed>
     */
    protected array $assets = [];

    protected string $taskName = 'PHPStan';

    public function getTaskName(): string
    {
        return $this->taskName ?: TaskInfo::formatTaskName($this);
    }

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options): static
    {
        $this->setOptionsBase($options);

        return $this;
    }

    protected function initOptions(): static
    {
        $this->options = [];
        $this->initOptionsBase();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Robo\Result<string, mixed>
     */
    public function run()
    {
        return $this
            ->runPrepare()
            ->runHeader()
            ->runAction()
            ->runProcessOutputs()
            ->runReturn();
    }

    protected function runPrepare(): static
    {
        $this->initOptions();

        return $this;
    }

    protected function runHeader(): static
    {
        return $this;
    }

    abstract protected function runAction(): static;

    protected function runProcessOutputs(): static
    {
        return $this;
    }

    /**
     * @return \Robo\Result<string, mixed>
     */
    protected function runReturn(): Result
    {
        return new Result(
            $this,
            $this->getTaskResultCode(),
            $this->getTaskResultMessage(),
            $this->getAssetsWithPrefixedNames(),
        );
    }

    /**
     * @param null|array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    protected function getTaskContext($context = null)
    {
        if (!$context) {
            $context = [];
        }

        if (empty($context['name'])) {
            $context['name'] = $this->getTaskName();
        }

        return parent::getTaskContext($context);
    }

    protected function getTaskResultCode(): int
    {
        return $this->actionExitCode;
    }

    protected function getTaskResultMessage(): string
    {
        return $this->actionStdError;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getAssetsWithPrefixedNames(): array
    {
        $prefix = $this->getAssetNamePrefix();
        if ($prefix === '') {
            return $this->assets;
        }

        $assets = [];
        foreach ($this->assets as $key => $value) {
            $assets["{$prefix}{$key}"] = $value;
        }

        return $assets;
    }
}
