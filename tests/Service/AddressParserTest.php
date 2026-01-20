<?php

declare(strict_types=1);

namespace Tests\Service;

use App\Exception\InvalidAddressException;
use App\Service\AddressParser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AddressParserTest extends TestCase
{
    private AddressParser $parser;

    protected function setUp(): void
    {
        $this->parser = new AddressParser();
    }

    #[Test]
    public function parsesMinimalAddress(): void
    {
        $address = $this->parser->parse('18 Ormeau Ave, Belfast, BT2 8HS');

        $this->assertSame('Belfast', $address->getTown());
        $this->assertSame('Ormeau Ave', $address->getStreetName());
        $this->assertSame(18, $address->getStreetNumber());
        $this->assertSame('BT2 8HS', $address->getPostcode());
        $this->assertNull($address->getPropertyName());
        $this->assertNull($address->getPropertyNumber());
    }

    #[Test]
    public function parsesAddressWithCounty(): void
    {
        $address = $this->parser->parse('18 Ormeau Ave, Belfast, Co. Antrim, BT2 8HS');

        $this->assertSame('Belfast', $address->getTown());
        $this->assertSame('BT2 8HS', $address->getPostcode());
    }

    #[Test]
    public function parsesAddressWithPropertyName(): void
    {
        $address = $this->parser->parse('Ormeau Baths, 18 Ormeau Ave, Belfast, BT2 8HS');

        $this->assertSame('Ormeau Baths', $address->getPropertyName());
        $this->assertNull($address->getPropertyNumber());
    }

    #[Test]
    public function parsesAddressWithPropertyNumber(): void
    {
        $address = $this->parser->parse('2.4 The Front, 36 Shore Road, Holywood, BT18 9GZ');

        $this->assertSame(2.4, $address->getPropertyNumber());
        $this->assertSame('The Front', $address->getPropertyName());
        $this->assertSame(36, $address->getStreetNumber());
    }

    #[Test]
    public function parsesAddressWithPropertyAndCounty(): void
    {
        $address = $this->parser->parse('Ormeau Baths, 18 Ormeau Ave, Belfast, Co. Antrim, BT2 8HS');

        $this->assertSame('Ormeau Baths', $address->getPropertyName());
        $this->assertSame('Belfast', $address->getTown());
        $this->assertSame('BT2 8HS', $address->getPostcode());
    }

    #[Test]
    #[DataProvider('invalidAddressProvider')]
    public function throwsOnInvalidAddress(string $address, string $expectedReason): void
    {
        $this->expectException(InvalidAddressException::class);
        $this->expectExceptionMessageMatches("/$expectedReason/");

        $this->parser->parse($address);
    }

    /** @return array<string, array{string, string}> */
    public static function invalidAddressProvider(): array
    {
        return [
            'too few parts' => ['Belfast, BT2 8HS', 'at least 3 parts'],
            'missing street number' => ['Ormeau Baths, Belfast, BT2 8HS', 'Missing street number'],
            'invalid postcode format' => ['Downing Street, London, SW1A 2AA', 'Invalid postcode'],
            'missing postcode' => ['Ormeau Baths, 18 Ormeau Ave, Belfast, Co. Antrim', 'Invalid postcode'],
            'missing comma before postcode' => ['18 Ormeau Ave, Belfast, Co. Antrim BT2 8HS', 'Invalid postcode'],
        ];
    }

    #[Test]
    public function preservesRawAddress(): void
    {
        $raw = '18 Ormeau Ave, Belfast, BT2 8HS';
        $address = $this->parser->parse($raw);

        $this->assertSame($raw, $address->getRaw());
    }

    #[Test]
    public function parsesAddressWithCountyPrefixInTownName(): void
    {
        $address = $this->parser->parse('123 Main St, Countyville, BT1 1AA');

        $this->assertSame('Countyville', $address->getTown());
        $this->assertSame('BT1 1AA', $address->getPostcode());
        $this->assertSame('Main St', $address->getStreetName());
        $this->assertSame(123, $address->getStreetNumber());
    }
}
