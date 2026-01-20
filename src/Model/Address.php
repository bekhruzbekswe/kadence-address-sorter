<?php

declare(strict_types=1);

namespace App\Model;

/**
 * Immutable value object.
 */
final class Address
{
    public function __construct(
        private string $raw,
        private string $town,
        private string $streetName,
        private int $streetNumber,
        private string $postcode,
        private ?string $propertyName = null,
        private ?float $propertyNumber = null
    ) {
    }

    public function getRaw(): string
    {
        return $this->raw;
    }

    public function getTown(): string
    {
        return $this->town;
    }

    public function getStreetName(): string
    {
        return $this->streetName;
    }

    public function getStreetNumber(): int
    {
        return $this->streetNumber;
    }

    public function getPropertyName(): ?string
    {
        return $this->propertyName;
    }

    public function getPropertyNumber(): ?float
    {
        return $this->propertyNumber;
    }

    public function getPostcode(): string
    {
        return $this->postcode;
    }
}