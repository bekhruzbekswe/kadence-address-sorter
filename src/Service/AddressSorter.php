<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Address;

final class AddressSorter
{
    public function __construct(
        private AddressParser $parser
    ) {
    }

    /**
     * @param string[] $addresses
     * @return string[]
     */
    public function sort(array $addresses): array
    {
        $parsed = array_map(
            fn(string $raw) => $this->parser->parse($raw),
            $addresses
        );

        usort($parsed, $this->compare(...));

        return array_map(
            fn(Address $addr) => $addr->getRaw(),
            $parsed
        );
    }

    private function compare(Address $first, Address $second): int
    {
        return $this->compareByTown($first, $second)
            ?: $this->compareByStreetName($first, $second)
            ?: $this->compareByStreetNumber($first, $second)
            ?: $this->compareByPropertyName($first, $second)
            ?: $this->compareByPropertyNumber($first, $second);
    }

    private function compareByTown(Address $first, Address $second): int
    {
        return strcasecmp($first->getTown(), $second->getTown());
    }

    private function compareByStreetName(Address $first, Address $second): int
    {
        return strcasecmp($first->getStreetName(), $second->getStreetName());
    }

    private function compareByStreetNumber(Address $first, Address $second): int
    {
        return $first->getStreetNumber() <=> $second->getStreetNumber();
    }

    private function compareByPropertyName(Address $first, Address $second): int
    {
        return strcasecmp($first->getPropertyName() ?? '', $second->getPropertyName() ?? '');
    }

    private function compareByPropertyNumber(Address $first, Address $second): int
    {
        return ($first->getPropertyNumber() ?? 0) <=> ($second->getPropertyNumber() ?? 0);
    }
}