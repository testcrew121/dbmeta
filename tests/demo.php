<?php

require_once __DIR__ . '/../vendor/autoload.php';

//$path = dirname(__DIR__) . '/tmp/opcache';
//$meta = new \Odan\Cache\OpCache($path);

$file = dirname(__DIR__) . '/tmp/test.db';
$path = dirname($file);
if (!file_exists($path)) {
    mkdir($path, 0777, true);
}
$meta = new \Odan\Cache\SqliteCache($file);

$meta->clear();

$meta->set('key', 'value' . date('U'));
$meta->set(array('table', '1'), 'value2');
$meta->set(array('table', '2'), array('name' => 'odan', 'phone' => 1234567));
$meta->set('keyttl', 'value with ttl', 1);
//sleep(2);
$meta->get('keyttl');

echo '<pre>';
var_dump($meta->get('key'));
var_dump($meta->get(array('table', '1')));
var_dump($meta->get(array('table', '2')));

$meta->set('temp', 'test');
var_dump($meta->has('temp'));
var_dump($meta->get('temp'));

$meta->remove('temp');
var_dump($meta->has('temp'));
var_dump($meta->get('temp', 'default value'));

echo '</pre>';
