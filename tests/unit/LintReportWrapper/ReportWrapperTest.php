<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\Tests\Unit\LintReportWrapper;

use Codeception\Test\Unit;
use Sweetchuck\Robo\Phpstan\LintReportWrapper\ReportWrapper;
use Sweetchuck\Robo\Phpstan\Tests\UnitTester;

class ReportWrapperTest extends Unit
{
    protected UnitTester $tester;

    /**
     * @return array<string, mixed>
     */
    public function casesReports()
    {
        $dir = codecept_data_dir('fixtures/LintReportWrapper');

        return [
            'ok:no-files' => [
                'expected' => [
                    'countFiles' => 0,
                    'numOfErrors' => 0,
                    'numOfWarnings' => 0,
                    'highestSeverity' => 'ok',
                ],
                'report' => [
                    'totals' => [
                        'errors' => 0,
                        'file_errors' => 0,
                    ],
                    'files' => [],
                ],
            ],
            'fail' => [
                'expected' => [
                    'countFiles' => 4,
                    'numOfErrors' => 4,
                    'numOfWarnings' => 0,
                    'highestSeverity' => 'error',
                ],
                'report' => json_decode(
                    file_get_contents("$dir/report.json") ?: '{}',
                    true,
                ),
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<string, mixed> $report
     *
     * @dataProvider casesReports
     */
    public function testAll(array $expected, array $report): void
    {
        $rw = new ReportWrapper($report);

        $this->tester->assertSame(
            $expected['countFiles'],
            $rw->countFiles(),
            'countFiles',
        );
        $this->tester->assertSame(
            $expected['numOfErrors'],
            $rw->numOfErrors(),
            'numOfErrors',
        );
        $this->tester->assertSame(
            $expected['numOfWarnings'],
            $rw->numOfWarnings(),
            'numOfWarnings',
        );
        $this->tester->assertSame(
            $expected['highestSeverity'],
            $rw->highestSeverity(),
            'highestSeverity',
        );

        /**
         * @var string $filePath
         * @var \Sweetchuck\Robo\Phpcs\LintReportWrapper\FileWrapper $fw
         */
        foreach ($rw->yieldFiles() as $filePath => $fw) {
            $file = array_shift($report['files']);
            $this->tester->assertSame($filePath, $fw->filePath());
            $this->tester->assertSame($file['errors'], $fw->numOfErrors());
            $this->tester->assertSame(0, $fw->numOfWarnings());
            $this->tester->assertSame('error', $fw->highestSeverity());
            //$this->tester->assertSame($file['__stats'], $fw->stats());

            /**
             * @var int $i
             * @var \Sweetchuck\LintReport\FailureWrapperInterface $failureWrapper
             */
            foreach ($fw->yieldFailures() as $i => $failureWrapper) {
                $message = $file['messages'][$i];
                $this->tester->assertSame('error', $failureWrapper->severity());
                //$this->tester->assertSame($message['source'], $failureWrapper->source());
                $this->tester->assertSame($message['line'] ?? 0, $failureWrapper->line());
                $this->tester->assertSame(0, $failureWrapper->column());
                $this->tester->assertSame($message['message'], $failureWrapper->message());
            }
        }
    }
}
