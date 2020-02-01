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

    /**
     * @return $this
     */
    public function setAssetNamePrefix(string $value)
    {
        $this->assetNamePrefix = $value;

        return $this;
    }
    // endregion

    /**
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function setOptionsBase(array $options)
    {
        if (array_key_exists('assetNamePrefix', $options)) {
            $this->setAssetNamePrefix($options['assetNamePrefix']);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function initOptionsBase()
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
