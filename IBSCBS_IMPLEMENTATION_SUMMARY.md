# IBS/CBS Implementation Summary

## Overview

This document summarizes the implementation of IBS/CBS (Brazilian Tax Reform) tags in the sped-nfe library.

## Date

Implemented: November 4, 2025

## Changes Made

### 1. File: `src/Make.php`

#### 1.1 Added Class Property (Line ~283)

```php
protected $aIBSCBS = [];
```

Stores IBSCBS elements for each item.

#### 1.2 Added Totalization Properties (Lines ~384-402)

Added initialization of IBS/CBS totalization variables in the constructor:

-   `vBCIBSCBS` - Total base of calculation
-   `vIBSUF` - Total IBS state value
-   `vIBSMun` - Total IBS municipal value
-   `vIBS` - Total IBS value
-   `vCBS` - Total CBS value
-   Plus additional fields for differences, tax returns, presumed credits, and monophasic values

#### 1.3 Added Method `tagIBSCBS()` (Lines ~6163-6283)

Creates IBSCBS tags for items (det/imposto/IBSCBS).

**Parameters:**

-   `item` - Item number
-   `CST` - Tax situation code
-   `cClassTrib` - Tax classification code
-   `vBC` - Calculation base value
-   `pIBSUF` - State IBS rate
-   `vIBSUF` - State IBS value
-   `pIBSMun` - Municipal IBS rate
-   `vIBSMun` - Municipal IBS value
-   `vIBS` - Total IBS value
-   `pCBS` - CBS rate
-   `vCBS` - CBS value

**Features:**

-   Automatic totalization of values
-   Proper XML structure with nested groups (gIBSCBS, gIBSUF, gIBSMun, gCBS)
-   Standard formatting for monetary values (2 decimals) and rates (4 decimals)

#### 1.4 Added Method `tagIBSCBSTot()` (Lines ~6804-6911)

Creates IBSCBSTot totals tag (total/IBSCBSTot).

**Features:**

-   Returns null if no IBSCBS items exist
-   Uses automatically totalized values from items
-   Allows manual override of any value
-   Includes all extended fields (differences, credits, monophasic)
-   Properly structured with gIBS, gCBS, and gMono groups

#### 1.5 Updated `buildImp()` Method (Line ~8020-8022)

Added IBSCBS inclusion in the tax structure:

```php
if (!empty($this->aIBSCBS[$nItem])) {
    $this->dom->appChild($imposto, $this->aIBSCBS[$nItem], "Inclusão do node IBSCBS");
}
```

Position: After COFINSST, before ICMSUFDest

#### 1.6 Updated `monta()` Method (Lines ~504-507)

Added IBSCBSTot to total section:

```php
$ibscbsTot = $this->tagIBSCBSTot(null);
if ($ibscbsTot) {
    $this->dom->appChild($this->total, $ibscbsTot, 'Falta tag "total"');
}
```

Position: After ISSQNTot, before retTrib

### 2. File: `examples/5.0testIBSCBS.php` (NEW)

Complete example demonstrating the use of IBS/CBS tags:

-   Full NFe structure
-   Item configuration with IBSCBS
-   Automatic totalization demonstration

### 3. File: `docs/IBSCBS.md` (NEW)

Comprehensive documentation including:

-   Introduction to IBS/CBS (Tax Reform)
-   XML structure explanation
-   Usage examples
-   Field reference tables
-   Complete example
-   Important notes and references

## XML Structure Generated

### Item Level (det/imposto/IBSCBS)

```
IBSCBS
├── CST
├── cClassTrib
└── gIBSCBS
    ├── vBC
    ├── gIBSUF
    │   ├── pIBSUF
    │   └── vIBSUF
    ├── gIBSMun
    │   ├── pIBSMun
    │   └── vIBSMun
    ├── vIBS
    └── gCBS
        ├── pCBS
        └── vCBS
```

### Total Level (total/IBSCBSTot)

```
IBSCBSTot
├── vBCIBSCBS
├── gIBS
│   ├── gIBSUF
│   │   ├── vDif
│   │   ├── vDevTrib
│   │   └── vIBSUF
│   ├── gIBSMun
│   │   ├── vDif
│   │   ├── vDevTrib
│   │   └── vIBSMun
│   ├── vIBS
│   ├── vCredPres
│   └── vCredPresCondSus
├── gCBS
│   ├── vDif
│   ├── vDevTrib
│   ├── vCBS
│   ├── vCredPres
│   └── vCredPresCondSus
└── gMono
    ├── vIBSMono
    ├── vCBSMono
    ├── vIBSMonoReten
    ├── vCBSMonoReten
    ├── vIBSMonoRet
    └── vCBSMonoRet
```

## Features

### Automatic Totalization

-   Values from all items are automatically summed
-   Base calculation, IBS (state and municipal), and CBS values are totalized
-   No manual calculation required

### Optional Tags

-   IBSCBS tags are only included when explicitly added
-   If no items have IBSCBS, the IBSCBSTot tag is not generated
-   Backward compatible - existing code continues to work

### Flexible Totalization

-   Automatic totalization by default
-   Manual override available for all total fields
-   Support for extended fields (credits, differences, monophasic)

### Standard Compliance

-   Follows NFe 4.0 XML structure
-   Proper positioning in XML hierarchy
-   Correct formatting (2 decimals for values, 4 for rates)

## Testing

Run the example:

```bash
php examples/5.0testIBSCBS.php
```

Expected output: Valid NFe 4.0 XML with IBSCBS tags at item and total levels.

## Compatibility

-   **PHP Version**: 7.4+
-   **NFe Version**: 4.0
-   **Backward Compatible**: Yes - existing code unaffected
-   **Breaking Changes**: None

## Usage Summary

```php
// Add IBS/CBS to item
$std = new stdClass();
$std->item = 1;
$std->CST = '000';
$std->cClassTrib = '000001';
$std->vBC = 483.84;
$std->pIBSUF = 0.1000;
$std->vIBSUF = 0.48;
$std->pIBSMun = 0.0000;
$std->vIBSMun = 0.00;
$std->vIBS = 0.48;
$std->pCBS = 0.9000;
$std->vCBS = 4.35;
$nfe->tagIBSCBS($std);

// Totalization is automatic when calling monta()
$xml = $nfe->monta();
```

## Future Considerations

1. **Schema Validation**: Update XSD schemas when official IBS/CBS schemas are released by SEFAZ
2. **CST Codes**: Implement specific validation for IBS/CBS CST codes
3. **Classification Codes**: Add validation for cClassTrib codes when official tables are available
4. **Rate Tables**: Consider adding helper methods for standard IBS/CBS rates by product/service category
5. **Transition Period**: Document the transition period from current taxes to IBS/CBS

## References

-   Emenda Constitucional 132/2023 (Brazilian Tax Reform)
-   NFe 4.0 Technical Manual
-   XML example provided: xml nfe 9.xml

## Authors

Implementation based on the Brazilian Tax Reform (IBS/CBS) and NFe 4.0 specifications.
