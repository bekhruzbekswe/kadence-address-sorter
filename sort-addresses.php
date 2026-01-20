<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\Service\AddressParser;
use App\Service\AddressSorter;
use App\Exception\InvalidAddressException;

if ($argc < 2) {
    echo "Usage: php sort-addresses.php <filename>\n";
    exit(1);
}

$filename = $argv[1];

if (!file_exists($filename)) {
    echo "Error: File '{$filename}' not found\n";
    exit(1);
}

$content = file_get_contents($filename);

if ($content === false) {
    echo "Error: Could not read file '{$filename}'\n";
    exit(1);
}

$addresses = array_filter(
    array_map('trim', explode("\n", $content)),
    fn(string $line): bool => $line !== ''
);

if (empty($addresses)) {
    echo "Error: No addresses found in file\n";
    exit(1);
}

try {
    $parser = new AddressParser();
    $sorter = new AddressSorter($parser);

    $sortedAddresses = $sorter->sort($addresses);

    echo "Sorted Addresses:\n";
    echo str_repeat('-', 80) . "\n";
    foreach ($sortedAddresses as $address) {
        echo $address . "\n";
    }
    echo str_repeat('-', 80) . "\n";
    echo "Total: " . count($sortedAddresses) . " addresses\n";

} catch (InvalidAddressException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Unexpected error: " . $e->getMessage() . "\n";
    exit(1);
}