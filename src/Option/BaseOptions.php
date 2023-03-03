<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Phpstan\Option;

/**
 * @property array $options
 */
trait BaseOptions
{
    // region Option - assetNamePrefix.
    protected string $assetNamePrefix = '';

    public function getAssetNamePrefix(): string
    {
        return $this->assetNamePrefix;
    }

    public function setAssetNamePrefix(string $value): static
    {
        $this->assetNamePrefix = $value;

        return $this;
    }
    // endregion

    /**
     * @param array<string, mixed> $options
     */
    protected function setOptionsBase(array $options): static
    {
        if (array_key_exists('assetNamePrefix', $options)) {
            $this->setAssetNamePrefix($options['assetNamePrefix']);
        }

        return $this;
    }

    protected function initOptionsBase(): static
    {
        $this->options += [
            'assetNamePrefix' => [
                'type' => 'other',
                'value' => $this->getAssetNamePrefix(),
            ],
        ];

        return $this;
    }
}
