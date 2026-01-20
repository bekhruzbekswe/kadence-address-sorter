# Implementation Notes

Northern Ireland address validation and sorting tool built with PHP 8.5+.

## Quick Start

```bash
# Install dependencies
composer install

# Run tests
composer test

# Sort addresses from file
php sort-addresses.php addresses.txt

# Or with composer
composer sort addresses.txt
```

---

## Architecture

| Layer | File | Purpose |
|-------|------|---------|
| **Model** | `Address.php` | Immutable value object holding parsed address data |
| **Service** | `AddressParser.php` | Validates and parses raw address strings |
| **Service** | `AddressSorter.php` | Sorts addresses using multi-level comparison |
| **Exception** | `InvalidAddressException.php` | Thrown for malformed addresses |

---

## Design Decisions

### 1. Case-Insensitive Sorting
**Requirement:** Sort by town/street/property alphabetically  
**Decision:** Used `strcasecmp()` for all string comparisons  
**Rationale:** "Belfast" and "BELFAST" should be treated as equal — common expectation for address sorting

### 2. County Prefix Detection
**Requirement:** County starts with "Co." or "County"  
**Decision:** Used `stripos()` for case-insensitive prefix matching  
**Rationale:** Handles variations like "Co.", "CO.", "County", "COUNTY"

### 3. Property Number as Float
**Requirement:** Apartments may have numbers like "2.4"  
**Decision:** Stored as `float` (`propertyNumber`), street number as `int`  
**Rationale:** Enables correct ordering: 1.1 < 2.4 < 3.5

### 4. Street Number Parsing
**Requirement:** House number with street name on same line  
**Decision:** Pattern `/^([\d.]+)\s+(.+)$/` extracts leading number  
**Rationale:** Handles both "18 Ormeau Ave" and "2.4 The Front"

### 5. Postcode Validation
**Requirement:** Must start with BT, 1-2 digits, space, digit, two letters  
**Decision:** Pattern `/^BT\d{1,2}\s\d[A-Z]{2}$/`  
**Note:** Uppercase only — postcodes like "BT2 8hs" rejected (per UK standard)

### 6. Null Handling in Sort
**Decision:** Null property names → empty string, null property numbers → 0  
**Rationale:** Addresses without properties sort before those with properties alphabetically

### 7. Natural Sorting Suggestion
**Note:** Used alphabetic and numeric sorting on request but I would suggest using natural sorting for address.

---

## Sort Priority

1. **Town** (A→Z)
2. **Street Name** (A→Z)
3. **Street Number** (1→999)
4. **Property Name** (A→Z)
5. **Property Number** (1.0→99.9)

---

## Example Input → Output

**Input** (`addresses.txt`):
```
18 Ormeau Ave, Belfast, BT2 8HS
12 Abbey Road, Armagh, BT61 7DY
2.4 The Front, 36 Shore Road, Holywood, BT18 9GZ
```

**Output**:
```
12 Abbey Road, Armagh, BT61 7DY
18 Ormeau Ave, Belfast, BT2 8HS
2.4 The Front, 36 Shore Road, Holywood, BT18 9GZ
```
