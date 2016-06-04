# DdMeta
Simple and easy key/value interface for SQLite3.

## Requirements

* SQLite3 extension (php_sqlite3)
* PHP >= 5.4
* Write permission for database files

## Examples

```php
$meta = new \Odan\DbMeta\DbMetaSqlite();

// Open db file
$meta->open(__DIR__ . '/meta.db');

// Set value
$meta->set('key', 'value' . date('U'));

// Set value with array as key and string as value
$meta->set(array('table', '1'), 'value2');

// Set value with array as key and array as value
$meta->set(array('table', '2'), array('name' => 'odan', 'phone' => 1234567));

// Get value
$value = $meta->get('key');

// Get value by array key
$value2 = $meta->get(array('table', '1'));
$value3 = $meta->get(array('table', '2'));

// Check if key exist
$exist = $meta->has('key'); # returns true
$exist2 = $meta->has('temp'); # returns false

// Delete key
$meta->remove('key');
```
