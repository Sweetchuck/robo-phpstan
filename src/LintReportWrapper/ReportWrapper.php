<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\LintReportWrapper;

use Sweetchuck\LintReport\ReportWrapperInterface;

class ReportWrapper implements ReportWrapperInterface
{

    /**
     * @var array<string, mixed>
     */
    protected array $report = [];

    /**
     * @param null|array<string, mixed> $report
     */
    public function __construct(array $report = null)
    {
        if ($report !== null) {
            $this->setReport($report);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getReport(): array
    {
        return $this->report;
    }

    /**
     * @param array<string, mixed> $report
     *
     * @return $this
     */
    public function setReport(array $report)
    {
        $this->report = array_replace_recursive(
            [
                'totals' => [
                    'errors' => 0,
                    'file_errors' => 0,
                    'warnings' => 0,
                    'fixable' => 0,
                ],
                'files' => [],
            ],
            $report,
        );

        return $this;
    }

    public function countFiles(): int
    {
        return count($this->report['files']);
    }

    /**
     * @return iterable<\Sweetchuck\LintReport\FileWrapperInterface>
     */
    public function yieldFiles()
    {
        foreach ($this->report['files'] as $filePath => $file) {
            $file['filePath'] = $filePath;
            yield $filePath => new FileWrapper($file);
        }
    }

    public function numOfErrors(): int
    {
        return $this->report['totals']['file_errors'];
    }

    public function numOfWarnings(): int
    {
        return $this->report['totals']['warnings'];
    }

    public function highestSeverity(): string
    {
        if ($this->numOfErrors()) {
            return ReportWrapperInterface::SEVERITY_ERROR;
        }

        if ($this->numOfWarnings()) {
            return ReportWrapperInterface::SEVERITY_WARNING;
        }

        return ReportWrapperInterface::SEVERITY_OK;
    }
}
