<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\Tests\Helper\Dummy;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Robo\Collection\CollectionBuilder;
use Robo\Common\TaskIO;
use Robo\Contract\BuilderAwareInterface;
use Robo\State\StateAwareTrait;
use Robo\TaskAccessor;
use Robo\Tasks;
use Sweetchuck\Robo\Phpstan\PhpstanTaskLoader;

class DummyTaskBuilder implements BuilderAwareInterface, ContainerAwareInterface
{
    use TaskAccessor;
    use ContainerAwareTrait;
    use StateAwareTrait;
    use TaskIO;
    use PhpstanTaskLoader {
        taskPhpstanAnalyze as public;
        taskPhpstanVersion as public;
    }

    /**
     * {@inheritdoc}
     */
    public function collectionBuilder(): CollectionBuilder
    {
        return CollectionBuilder::create(
            $this->getContainer(),
            new Tasks(),
        );
    }

    /**
     * @return \Symfony\Component\Console\Output\OutputInterface
     */
    protected function output()
    {
        return $this->getContainer()->get('output');
    }
}
