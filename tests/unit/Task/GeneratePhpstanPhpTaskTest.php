<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\Tests\Unit\Task;

use PHPUnit\Framework\Attributes\CoversClass;
use Sweetchuck\Robo\Phpstan\Task\GeneratePhpstanPhpTask;
use Sweetchuck\Robo\Phpstan\Task\TaskBase;
use Symfony\Component\Finder\SplFileInfo;

#[CoversClass(GeneratePhpstanPhpTask::class)]
#[CoversClass(TaskBase::class)]
class GeneratePhpstanPhpTaskTest extends TaskTestBase
{

    public function testRunSuccess(): void
    {
        $srcFile1Content = <<<'NEON'
            parameters:
                typeAliases:
                    dummy-aaa-aaa: '''
                        array{
                            bar: string,
                        }
                    '''
                    dummy-aaa-bbb: '''
                        array{
                            foo: string,
                        }
                    '''
            NEON;
        $srcFile1Path = 'data://text/plain;base64,' . base64_encode($srcFile1Content);
        $srcFile1 = new SplFileInfo(
            $srcFile1Path,
            './foo/dummy1.php',
            './foo',
        );
        $srcFile2Content = <<<'NEON'
            parameters:
                typeAliases:
                    dummy-bbb-ccc: '''
                        array{
                            bar: string,
                        }
                    '''
                    dummy-bbb-ddd: '''
                        array{
                            foo: string,
                        }
                    '''
            NEON;
        $srcFile2Path = 'data://text/plain;base64,' . base64_encode($srcFile2Content);
        $srcFile2 = new SplFileInfo(
            $srcFile2Path,
            './foo/dummy2.php',
            './foo',
        );

        $expected = [
            'exitCode' => 0,
            'fileContent' => <<<'PHP'
                <?php

                declare(strict_types = 1);

                namespace Foo\Bar;

                /**
                 * @phpstan-type DummyAaaAaa = dummy-aaa-aaa
                 * @phpstan-type DummyAaaBbb = dummy-aaa-bbb
                 * @phpstan-type DummyBbbCcc = dummy-bbb-ccc
                 * @phpstan-type DummyBbbDdd = dummy-bbb-ddd
                 */
                class Phpstan
                {
                }

                PHP,
        ];

        $options = [
            'srcFiles' => [$srcFile1, $srcFile2],
            'dstFilePath' => tempnam(sys_get_temp_dir(), 'phpstan-php-'),
            'namespace' => 'Foo\Bar',
        ];

        $result = $this
            ->taskBuilder
            ->taskPhpstanGeneratePhp($options)
            ->run();

        $this->tester->assertSame(
            $expected['exitCode'],
            $result->getExitCode(),
            'task exit code',
        );

        $this->tester->assertSame(
            $expected['fileContent'],
            file_get_contents($options['dstFilePath']),
            'generated php file is correct',
        );
    }
}
