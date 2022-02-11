<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\Tests\Unit\Task;

use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyProcess;

/**
 * @covers \Sweetchuck\Robo\Phpstan\Task\AnalyzeTask<extended>
 * @covers \Sweetchuck\Robo\Phpstan\PhpstanTaskLoader
 */
class AnalyzeTaskTest extends TaskTestBase
{

    /**
     * @return array<string, mixed>
     */
    public function casesGetCommand(): array
    {
        return [
            'basic' => [
                'vendor/bin/phpstan analyze',
                [],
            ],
            'with custom working directory' => [
                'vendor/bin/phpstan analyze',
                [
                    'workingDirectory' => '/foo/bar',
                ],
            ],
            'level' => [
                "vendor/bin/phpstan analyze --level='2'",
                [
                    'level' => 2,
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
        $task = $this->taskBuilder->taskPhpstanAnalyze($options);

        $this->tester->assertSame($expected, $task->getCommand());
    }

    /**
     * @return array<string, mixed>
     */
    public function casesRunSuccess(): array
    {
        $reportErrors = [
            'totals' => [
                'errors' => 0,
                'file_errors' => 1,
            ],
            'files' => [
                'a.php' => [
                    'errors' => 1,
                    'messages' => [
                        [
                            'message' => '',
                            'line' => 42,
                            'ignorable' => true,
                        ],
                    ],
                ],
            ],
        ];

        return [
            'basic' => [
                [
                    'exitCode' => 1,
                    'assets' => [
                        'report.raw' => $reportErrors,
                    ],
                ],
                [
                    'errorFormat' => 'json',
                ],
                [
                    [
                        'exitCode' => 1,
                        'stdOutput' => json_encode($reportErrors),
                        'stdError' => implode("\n", [
                            'Note: Using configuration file /dir/phpstan.neon.',
                            '27/27 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%',
                            '',
                            '[ERROR] Found 1 errors',
                            '',
                        ]),
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
            'exitCode' => 0,
            'assets' => [],
        ];

        DummyProcess::$prophecy = $processProphecy;

        $result = $this
            ->taskBuilder
            ->taskPhpstanAnalyze($options)
            ->run();

        $this->tester->assertSame(
            $expected['exitCode'],
            $result->getExitCode(),
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
