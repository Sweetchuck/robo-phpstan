<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan;

use League\Container\ContainerAwareInterface;
use Sweetchuck\Robo\Phpstan\Task\VersionTask;

trait PhpstanTaskLoader
{

    /**
     * @return \Sweetchuck\Robo\Phpstan\Task\VersionTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskPhpstanVersion(array $options = [])
    {
        /** @var \Sweetchuck\Robo\Phpstan\Task\VersionTask|\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(VersionTask::class);
        if ($this instanceof ContainerAwareInterface) {
            $task->setContainer($this->getContainer());
        }

        $task->setOptions($options);

        return $task;
    }
}
