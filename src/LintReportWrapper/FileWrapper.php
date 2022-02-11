<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\LintReportWrapper;

use Sweetchuck\LintReport\FileWrapperInterface;
use Sweetchuck\LintReport\ReportWrapperInterface;

class FileWrapper implements FileWrapperInterface
{

    /**
     * @var array<string, mixed>
     */
    protected array $file = [];

    /**
     * @var array<string, mixed>
     */
    public array $stats = [];

    /**
     * @param array<string, mixed> $file
     */
    public function __construct(array $file)
    {
        $this->file = $file + [
            'filePath' => '',
            'errors' => 0,
            'warnings' => 0,
            'messages' => [],
        ];
    }

    public function filePath(): string
    {
        return $this->file['filePath'];
    }

    public function numOfErrors(): int
    {
        return $this->file['errors'];
    }

    public function numOfWarnings(): int
    {
        return $this->file['warnings'];
    }

    /**
     * @return iterable<\Sweetchuck\LintReport\FailureWrapperInterface>
     */
    public function yieldFailures()
    {
        foreach ($this->file['messages'] as $failure) {
            yield new FailureWrapper($failure);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function stats(): array
    {
        if (!$this->stats) {
            $this->stats = [
                'severityWeight' => '',
                'severity' => '',
                'has' => [
                    ReportWrapperInterface::SEVERITY_OK => false,
                    ReportWrapperInterface::SEVERITY_WARNING => false,
                    ReportWrapperInterface::SEVERITY_ERROR => false,
                ],
                'source' => [],
            ];
            foreach ($this->file['messages'] as $failure) {
                $failure += [
                    'severity' => ReportWrapperInterface::SEVERITY_ERROR,
                    'type' => 'error',
                ];

                $severity = strtolower($failure['type']);
                if ($this->stats['severityWeight'] < $failure['severity']) {
                    $this->stats['severityWeight'] = $failure['severity'];
                    $this->stats['severity'] = $severity;
                }

                $this->stats['has'][$severity] = true;

                $this->stats['source'] += [
                    $failure['source'] => [
                        'severity' => $severity,
                        'count' => 0,
                    ],
                ];
                $this->stats['source'][$failure['source']]['count']++;
            }
        }

        return $this->stats;
    }

    public function highestSeverity(): string
    {
        if ($this->numOfErrors()) {
            return ReportWrapperInterface::SEVERITY_ERROR;
        }

        return $this->numOfWarnings() ?
            ReportWrapperInterface::SEVERITY_WARNING
            : ReportWrapperInterface::SEVERITY_OK;
    }
}
