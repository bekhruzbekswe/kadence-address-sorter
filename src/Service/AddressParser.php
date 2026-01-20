<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\InvalidAddressException;
use App\Model\Address;

final class AddressParser
{
    private const POSTCODE_PATTERN = '/^BT\d{1,2}\s\d[A-Z]{2}$/';
    private const NUMBER_NAME_PATTERN = '/^([\d.]+)\s+(.+)$/';
    private const COUNTY_PREFIXES = ['Co.', 'County'];

    public function parse(string $raw): Address
    {
        $raw = trim($raw);
        $parts = array_map('trim', explode(',', $raw));

        if (count($parts) < 3) {
            throw InvalidAddressException::forAddress($raw, 'Must have at least 3 parts');
        }

        [$postcode, $parts] = $this->extractPostcode($raw, $parts);
        $parts = $this->stripCounty($parts);

        if (count($parts) < 2) {
            throw InvalidAddressException::forAddress($raw, 'Missing town or street');
        }

        if (count($parts) > 3) {
            throw InvalidAddressException::forAddress($raw, 'Too many parts');
        }

        $town = array_pop($parts);
        $streetPart = array_pop($parts);
        $propertyPart = array_pop($parts);

        [$streetNumber, $streetName] = $this->parseStreetComponent($raw, (string) $streetPart);
        [$propertyNumber, $propertyName] = $this->parsePropertyComponent($propertyPart);

        return new Address(
            $raw,
            $town,
            $streetName,
            $streetNumber,
            $postcode,
            $propertyName,
            $propertyNumber
        );
    }

    /**
     * @param array<string> $parts
     * @return array{string, array<string>}
     */
    private function extractPostcode(string $raw, array $parts): array
    {
        $postcode = array_pop($parts);

        if ($postcode === null || !preg_match(self::POSTCODE_PATTERN, $postcode)) {
            throw InvalidAddressException::forAddress($raw, 'Invalid postcode format');
        }

        return [$postcode, $parts];
    }

    /**
     * @param array<string> $parts
     * @return array<string>
     */
    private function stripCounty(array $parts): array
    {
        if (empty($parts)) {
            return $parts;
        }

        $last = array_last($parts);

        foreach (self::COUNTY_PREFIXES as $prefix) {
            if (is_string($last) && stripos($last, $prefix) === 0) {
                array_pop($parts);
                return $parts;
            }
        }

        return $parts;
    }

    /** @return array{int, string} */
    private function parseStreetComponent(string $raw, string $part): array
    {
        if (!preg_match(self::NUMBER_NAME_PATTERN, $part, $matches)) {
            throw InvalidAddressException::forAddress($raw, 'Missing street number');
        }

        return [(int) $matches[1], $matches[2]];
    }

    /** @return array{float|null, string|null} */
    private function parsePropertyComponent(?string $part): array
    {
        if ($part === null) {
            return [null, null];
        }

        if (preg_match(self::NUMBER_NAME_PATTERN, $part, $matches)) {
            return [(float) $matches[1], $matches[2]];
        }

        return [null, $part];
    }
}