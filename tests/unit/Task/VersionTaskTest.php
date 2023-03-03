<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\Tests\Unit\Task;

use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyProcess;

/**
 * @covers \Sweetchuck\Robo\Phpstan\Task\VersionTask
 * @covers \Sweetchuck\Robo\Phpstan\Task\TaskBase
 */
class VersionTaskTest extends TaskTestBase
{

    /**
     * @return array<string, mixed>
     */
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
     * @param array<string, mixed> $options
     *
     * @dataProvider casesGetCommand
     */
    public function testGetCommand(string $expected, array $options): void
    {
        $task = $this->taskBuilder->taskPhpstanVersion($options);

        $this->tester->assertSame($expected, $task->getCommand());
    }

    /**
     * @return array<string, mixed>
     */
    public function casesRunSuccess(): array
    {
        return [
            'basic' => [
                [
                    'assets' => [
                        'version.full' => '1.9.2',
                    ],
                ],
                [],
                [
                    [
                        'exitCode' => 0,
                        'stdOutput' => 'PHPStan - PHP Static Analysis Tool 1.9.2',
                        'stdError' => '',
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<string, mixed> $options
     * @param array<string, mixed> $processProphecy
     *
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
