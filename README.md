<p align="center"><a href="https://www.google.ru/intl/ru/sheets/about/" target="_blank"><img src="./icon.svg" width="80" alt="Google Sheets"></a></p>

# GoogleSheet

`GoogleSheet` is a convenient wrapper for the
standard [`google/apiclient`](https://github.com/googleapis/google-api-php-client) package, providing an easy-to-use
interface for working with Google Sheets.

## Installation

Require the package using [Composer](https://getcomposer.org/):

```bash
composer require lozemc/simple-google-sheets
```

## Usage

#### Creating an Instance

```php

use Lozemc\GoogleSheet;

// Replace 'your_table_id' and 'path/to/your/credentials-config.json' with actual values

$table_id = 'your_table_id';
$credentials = 'path/to/your/credentials-config.json';

$table = new GoogleSheet($table_id, $credentials);

```

### Setting the Sheet

```php

$table->set_sheet('YourSheetName');

```

### Getting Rows

```php

// Get all rows
$rows = $table->get_rows();

print_r($rows);

// [
//    [ 'A1 value', 'B1 value', 'C1 value' ],
//    [ 'A2 value', 'B2 value', 'C2 value' ],
//    [ 'A3 value', 'B3 value', 'C3 value' ]
// ]


// Get rows from a specific range
$range = 'B2:C3';
$rows = $table->get_rows($range);

print_r($rows);

// [
//    [ 'B2 value', 'C2 value' ],
//    [ 'B3 value', 'C3 value' ]
// ]

```

### Updating Rows

```php

$table->update([['A1 value', 'B1 value']]);

// 

$range = 'A4';
$table->update([['A4 value', 'B4 value']], $range);

// 

$table->update([
    ['A1 value', 'B1 value'],
    ['A2 value', 'B2 value', 'C3 value'],
    ['A3 value', 'B3 value', '', 'D4 value'],
]);

```

### Appending Rows

```php
$table->append([['Value 1', 'Value 2']]);

//

$table->append([['Value 1', 'Value 2']], 'A10');

```

### Simple Example

```php

require_once __DIR__ . '/vendor/autoload.php';

use Lozemc\GoogleSheet;

$table_id = '1gncMRlsonFj2YzYw79QnfVA4Go5UcYkmfgG1T7Vb5Q';
$credentials = __DIR__ . '/credentials-config.json'; 

$table = new GoogleSheet($table_id, $credentials);


// Set the sheet name if not provided during initialization
$table->set_sheet('Sheet1');


// Append new rows
$table->append([
    ['Lisa', 'Anderson'],
    ['Jane', 'Jones'],
]);


// Get all rows
$rows = $table->get_rows();

// Display the retrieved rows
print_r($rows);


// Get rows from a specific range in another sheet
$rows = $table->set_sheet('Sheet2')->get_rows('B10:C20');
print_r($rows);


// Update data in a specific range
$table->update([['New value']], 'B10');
$row = $table->get_rows('B10');
print_r($row);

```

## Requirements

- PHP >=7.4.33

## Dependencies

- [google/apiclient ^2.15.0](https://github.com/googleapis/google-api-php-client)

## License

This package is licensed under the [MIT License](LICENSE).
