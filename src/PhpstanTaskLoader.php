<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan;

use Psr\Container\ContainerInterface;
use Sweetchuck\Robo\Phpstan\Task\AnalyzeTask;
use Sweetchuck\Robo\Phpstan\Task\VersionTask;

/**
 * @method \Robo\Collection\CollectionBuilder|\Robo\Contract\TaskInterface task(string $className, ...$args)
 */
trait PhpstanTaskLoader
{
    /**
     * @param array<string, mixed> $options
     *
     * @return \Sweetchuck\Robo\Phpstan\Task\AnalyzeTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskPhpstanAnalyze(array $options = [])
    {
        /** @var \Sweetchuck\Robo\Phpstan\Task\AnalyzeTask|\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(AnalyzeTask::class);
        $task->setContainer($this->getContainer());
        $task->setOptions($options);

        return $task;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return \Sweetchuck\Robo\Phpstan\Task\VersionTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskPhpstanVersion(array $options = [])
    {
        /** @var \Sweetchuck\Robo\Phpstan\Task\VersionTask|\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(VersionTask::class);
        $task->setContainer($this->getContainer());
        $task->setOptions($options);

        return $task;
    }

    /**
     * @see \League\Container\ContainerAwareInterface::getContainer()
     */
    abstract public function getContainer() : ContainerInterface;
}
