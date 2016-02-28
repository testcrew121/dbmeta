<?php

require_once __DIR__ . '/../vendor/autoload.php';

$meta = new \Odan\DbMeta\DbMetaSqlite();
$meta->connect(__DIR__ . '/meta.db');

$meta->set('key', 'value' . date('U'));
$meta->set(array('table', '1'), 'value2');
$meta->set(array('table', '2'), array('name' => 'odan', 'phone' => 1234567));

var_dump($meta->get('key'));
var_dump($meta->get(array('table', '1')));
var_dump($meta->get(array('table', '2')));

$meta->set('temp', 'test');
var_dump($meta->exist('temp'));
var_dump($meta->get('temp'));

$meta->delete('temp');
var_dump($meta->exist('temp'));
var_dump($meta->get('temp'));
