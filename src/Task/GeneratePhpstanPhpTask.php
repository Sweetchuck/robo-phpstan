<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\Task;

use Nette\Neon\Neon;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\String\UnicodeString;

class GeneratePhpstanPhpTask extends TaskBase
{

    //region Option - srcFiles

    /**
     * @var iterable<\SplFileInfo>
     */
    protected iterable $srcFiles = [];

    /**
     * @return iterable<\SplFileInfo>
     */
    public function getSrcFiles(): iterable
    {
        return $this->srcFiles;
    }

    /**
     *
     * @param iterable<\SplFileInfo> $srcFiles
     */
    public function setSrcFiles(iterable $srcFiles): static
    {
        $this->srcFiles = $srcFiles;

        return $this;
    }
    //endregion

    //region Option - dstFilePath
    protected string $dstFilePath = './src/Phpstan.php';

    public function getDstFilePath(): string
    {
        return $this->dstFilePath;
    }

    public function setDstFilePath(string $dstFilePath): static
    {
        $this->dstFilePath = $dstFilePath;

        return $this;
    }
    //endregion

    //region Option - namespace
    protected string $namespace = '';

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): static
    {
        $this->namespace = $namespace;

        return $this;
    }
    //endregion

    // region Option - template
    protected string $template = <<<'PHP'
        <?php

        declare(strict_types = 1);

        namespace {{ namespace }};

        /**
        {{ typeAliases }} */
        class Phpstan
        {
        }

        PHP;

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): static
    {
        $this->template = $template;

        return $this;
    }
    # endregion

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options): static
    {
        parent::setOptions($options);
        if (array_key_exists('srcFiles', $options)) {
            $this->setSrcFiles($options['srcFiles']);
        }

        if (array_key_exists('dstFilePath', $options)) {
            $this->setDstFilePath($options['dstFilePath']);
        }

        if (array_key_exists('namespace', $options)) {
            $this->setNamespace($options['namespace']);
        }

        if (array_key_exists('template', $options)) {
            $this->setTemplate($options['template']);
        }

        return $this;
    }

    protected function runAction(): static
    {
        $replacementPairs = [
            '{{ namespace }}' => $this->getNamespace(),
            '{{ typeAliases }}' => '',
        ];
        foreach ($this->getSrcFiles() as $srcFile) {
            assert($srcFile instanceof \SplFileInfo);
            $srcData = (array) Neon::decodeFile($srcFile->getPathname());
            if (empty($srcData['parameters']['typeAliases'])) {
                $this->logger->warning(
                    '[{task.name}] No type aliases found in file: {srcFiles}',
                    [
                        'task.name' => $this->getTaskName(),
                        'srcFiles' => $srcFile->getPathname(),
                    ],
                );

                continue;
            }
            foreach (array_keys($srcData['parameters']['typeAliases']) as $keyLowerDash) {
                $keyUpperCamel = (new UnicodeString("a_$keyLowerDash"))
                    ->camel()
                    ->trimPrefix('a');
                $replacementPairs['{{ typeAliases }}'] .= " * @phpstan-type $keyUpperCamel = $keyLowerDash\n";
            }
        }

        $fs = new Filesystem();
        try {
            $fs->dumpFile(
                $this->getDstFilePath(),
                strtr($this->getTemplate(), $replacementPairs),
            );
        } catch (IOExceptionInterface $e) {
            $this->logger->error(
                '[{task.name}] Error while generating file: {message}',
                [
                    'task.name' => $e->getMessage(),
                ],
            );
            $this->actionExitCode = 1;
        }

        return $this;
    }
}
