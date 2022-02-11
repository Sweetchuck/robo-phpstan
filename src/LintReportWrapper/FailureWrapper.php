<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\LintReportWrapper;

use Sweetchuck\LintReport\FailureWrapperInterface;
use Sweetchuck\LintReport\ReportWrapperInterface;

class FailureWrapper implements FailureWrapperInterface
{

    /**
     * @var array<string, mixed>
     */
    protected array $failure = [];

    /**
     * @param array<string, mixed> $failure
     */
    public function __construct(array $failure)
    {
        // @todo Validate.
        $this->failure = $failure + [
            'message' => '',
            'line' => 0,
            'ignorable' => true,
            // Extra.
            'source' => '',
            'severity' => ReportWrapperInterface::SEVERITY_ERROR,
            'type' => 'error',
            'column' => 0,
            'fixable' => false,
        ];
    }

    public function severity(): string
    {
        return strtolower($this->failure['type']);
    }

    public function source(): string
    {
        return $this->failure['source'];
    }

    public function line(): int
    {
        return $this->failure['line'] ?? 0;
    }

    public function column(): int
    {
        return $this->failure['column'];
    }

    public function message(): string
    {
        return $this->failure['message'];
    }
}
