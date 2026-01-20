<?php

declare(strict_types=1);

namespace Tests\Service;

use App\Service\AddressParser;
use App\Service\AddressSorter;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AddressSorterTest extends TestCase
{
    private AddressSorter $sorter;

    protected function setUp(): void
    {
        $this->sorter = new AddressSorter(new AddressParser());
    }

    #[Test]
    public function sortsByTownFirst(): void
    {
        $addresses = [
            '10 Main St, Belfast, BT1 1AA',
            '10 Main St, Armagh, BT61 7DY',
        ];

        $sorted = $this->sorter->sort($addresses);

        $this->assertSame('10 Main St, Armagh, BT61 7DY', $sorted[0]);
        $this->assertSame('10 Main St, Belfast, BT1 1AA', $sorted[1]);
    }

    #[Test]
    public function sortsByStreetNameSecond(): void
    {
        $addresses = [
            '10 Zebra Road, Belfast, BT1 1AA',
            '10 Alpha Street, Belfast, BT1 1BB',
        ];

        $sorted = $this->sorter->sort($addresses);

        $this->assertSame('10 Alpha Street, Belfast, BT1 1BB', $sorted[0]);
        $this->assertSame('10 Zebra Road, Belfast, BT1 1AA', $sorted[1]);
    }

    #[Test]
    public function sortsByStreetNumberThird(): void
    {
        $addresses = [
            '20 Main St, Belfast, BT1 1AA',
            '5 Main St, Belfast, BT1 1BB',
        ];

        $sorted = $this->sorter->sort($addresses);

        $this->assertSame('5 Main St, Belfast, BT1 1BB', $sorted[0]);
        $this->assertSame('20 Main St, Belfast, BT1 1AA', $sorted[1]);
    }

    #[Test]
    public function sortsByPropertyNameFourth(): void
    {
        $addresses = [
            'Zebra House, 10 Main St, Belfast, BT1 1AA',
            'Alpha House, 10 Main St, Belfast, BT1 1BB',
        ];

        $sorted = $this->sorter->sort($addresses);

        $this->assertSame('Alpha House, 10 Main St, Belfast, BT1 1BB', $sorted[0]);
        $this->assertSame('Zebra House, 10 Main St, Belfast, BT1 1AA', $sorted[1]);
    }

    #[Test]
    public function sortsByPropertyNumberFifth(): void
    {
        $addresses = [
            '3.5 The Block, 10 Main St, Belfast, BT1 1AA',
            '1.2 The Block, 10 Main St, Belfast, BT1 1BB',
        ];

        $sorted = $this->sorter->sort($addresses);

        $this->assertSame('1.2 The Block, 10 Main St, Belfast, BT1 1BB', $sorted[0]);
        $this->assertSame('3.5 The Block, 10 Main St, Belfast, BT1 1AA', $sorted[1]);
    }

    #[Test]
    public function handlesEmptyArray(): void
    {
        $sorted = $this->sorter->sort([]);

        $this->assertSame([], $sorted);
    }

    #[Test]
    public function handlesSingleAddress(): void
    {
        $addresses = ['10 Main St, Belfast, BT1 1AA'];

        $sorted = $this->sorter->sort($addresses);

        $this->assertCount(1, $sorted);
    }
}
