<?php

declare(strict_types=1);

namespace ElasticApmBundle\Interactor;

use ElasticApmBundle\Exception\ConfigurationException;

/**
 * This value object contains data and configuration that should be passed to the interactors.
 */
class Config
{
    private $customLabels;
    private $customContext;
    private $shouldCollectMemoryUsage;
    private $memoryUsageLabelName;
    private $shouldExplicitlyCollectCommandExceptions;
    private $shouldUnwrapExceptions;

    public function __construct(array $customLabels, array $customContext, bool $shouldCollectMemoryUsage, string $memoryUsageLabelName, bool $shouldExplicitlyCollectCommandExceptions, bool $shouldUnwrapExceptions)
    {
        if (0 === strlen($memoryUsageLabelName)) {
            throw new ConfigurationException('$memoryUsageLabelName cannot be blank');
        }

        $this->customLabels = $customLabels;
        $this->customContext = $customContext;
        $this->shouldCollectMemoryUsage = $shouldCollectMemoryUsage;
        $this->memoryUsageLabelName = $memoryUsageLabelName;
        $this->shouldExplicitlyCollectCommandExceptions = $shouldExplicitlyCollectCommandExceptions;
        $this->shouldUnwrapExceptions = $shouldUnwrapExceptions;
    }

    public function setCustomLabels(array $customLabels): void
    {
        $this->customLabels = $customLabels;
    }

    /**
     * @param string|int|float $value or any scalar value
     */
    public function addCustomLabels(string $name, $value): void
    {
        $this->customLabels[$name] = $value;
    }

    public function getCustomLabels(): array
    {
        return $this->customLabels;
    }

    public function setCustomContext(array $customContext): void
    {
        $this->customContext = $customContext;
    }

    /**
     * @param string|int|float $value or any scalar value
     */
    public function addCustomContext(string $name, $value): void
    {
        $this->customContext[$name] = $value;
    }

    public function getCustomContext(): array
    {
        return $this->customContext;
    }

    public function shouldCollectMemoryUsage(): bool
    {
        return $this->shouldCollectMemoryUsage;
    }

    public function getMemoryUsageLabelName(): string
    {
        return $this->memoryUsageLabelName;
    }

    public function shouldExplicitlyCollectCommandExceptions(): bool
    {
        return $this->shouldExplicitlyCollectCommandExceptions;
    }

    public function shouldUnwrapExceptions(): bool
    {
        return $this->shouldUnwrapExceptions;
    }
}
