<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\Tests\Unit\Task;

use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyProcess;

/**
 * @covers \Sweetchuck\Robo\Phpstan\Task\VersionTask<extended>
 */
class VersionTaskTest extends TaskTestBase
{
    public function casesGetCommand(): array
    {
        return [
            'basic' => [
                'vendor/bin/phpstan --version',
                [],
            ],
            'with custom working directory' => [
                'vendor/bin/phpstan --version',
                [
                    'workingDirectory' => '/foo/bar',
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesGetCommand
     */
    public function testGetCommand(string $expected, array $options): void
    {
        $task = $this->taskBuilder->taskPhpstanVersion($options);

        $this->tester->assertSame($expected, $task->getCommand());
    }

    public function casesRunSuccess(): array
    {
        return [
            'basic' => [
                [
                    'assets' => [
                        'version.full' => '1.4.6',
                    ],
                ],
                [],
                [
                    [
                        'exitCode' => 0,
                        'stdOutput' => 'PHPStan - PHP Static Analysis Tool 1.4.6',
                        'stdError' => '',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesRunSuccess
     */
    public function testRunSuccess(array $expected, array $options, array $processProphecy): void
    {
        $expected += [
            'wasSuccessful' => true,
            'assets' => [],
        ];

        DummyProcess::$prophecy = $processProphecy;

        $result = $this
            ->taskBuilder
            ->taskPhpstanVersion($options)
            ->run();

        $this->tester->assertSame(
            $expected['wasSuccessful'],
            $result->wasSuccessful(),
            'task exit code'
        );

        $actualAssets = $result->getData();
        foreach ($expected['assets'] as $key => $expectedValue) {
            $this->tester->assertArrayHasKey(
                $key,
                $actualAssets,
                "'$key' asset is present"
            );

            $this->tester->assertSame(
                $expectedValue,
                $actualAssets[$key],
                "$key asset is okay"
            );
        }
    }
}
