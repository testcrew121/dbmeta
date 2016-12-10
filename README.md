# DdMeta
Simple and easy key/value interface for OpCache, SQLite3 and Memory.

[![Latest Version](https://img.shields.io/github/release/odan/dbmeta.svg?style=flat-square)](https://github.com/loadsys/odan/dbmeta/releases)
[![Build Status](https://travis-ci.org/odan/dbmeta.svg?branch=master&style=flat-square)](https://travis-ci.org/odan/dbmeta)
[![Crutinizer](https://img.shields.io/scrutinizer/g/odan/dbmeta.svg?style=flat-square)](https://scrutinizer-ci.com/g/odan/dbmeta)
[![codecov](https://codecov.io/gh/odan/dbmeta/branch/master/graph/badge.svg?style=flat-square)](https://codecov.io/gh/odan/dbmeta)
[![Total Downloads](https://img.shields.io/packagist/dt/odan/dbmeta.svg?style=flat-square)](https://packagist.org/packages/odan/micro-app)
[![Repo Size](https://reposs.herokuapp.com/?path=odan/dbmeta&style=flat-square)](https://reposs.herokuapp.com/?path=odan/dbmeta)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)


## Requirements
* PHP >= 5.6

## Optional requirements
* SQLite3 extension (php_sqlite3)
* Write permission for OpCache and database files

## Example

```php

// PHP OpCache
$meta = new \Odan\Cache\OpCache();

// Memory cache
// $meta = new \Odan\Cache\MemoryCache()

// SQLite cache
// $meta = new \Odan\Cache\SqliteCache(__DIR__ . '/meta.db');

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

## Performance comparison

```
Class: Odan\Cache\MemoryCache
Seconds: 0.01800012588501

Class: Odan\Cache\OpCache
Seconds: 7.2159998416901

Class: Odan\Cache\SqliteCache
Seconds: 52.093000173569
```
(lower is better)

## Setup

```
composer require odan/dbmeta
```

## License

MIT
