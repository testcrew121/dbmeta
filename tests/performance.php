<?php
require_once __DIR__ . '/../vendor/autoload.php';

set_time_limit(60 * 5);

$objects = [];
$objects[] = new \Odan\Cache\MemoryCache();
$objects[] = new \Odan\Cache\OpCache(dirname(__DIR__) . '/tmp/opcache');

$file = dirname(__DIR__) . '/tmp/test.db';
$path = dirname($file);
if (!file_exists($path)) {
    mkdir($path, 0777, true);
}
$objects[] = new \Odan\Cache\SqliteCache($file);

echo '<pre>';
foreach ($objects as $meta) {
    $start = microtime(true);
    $meta->clear();

    for ($i = 0; $i < 1000; $i++) {
        $meta->set('key', 'value' . date('U'));
        $meta->set(array('table', '1'), 'value2');
        $meta->set(array('table', '2'), array('name' => 'odan', 'phone' => 1234567));
        $meta->set('keyttl', 'value with ttl', 1);


        $meta->get('key');
        $meta->get(array('table', '1'));
        $meta->get(array('table', '2'));

        $meta->set('temp', 'test');
        $meta->has('temp');
        $meta->get('temp');

        $meta->remove('temp');
        $meta->has('temp');
        $meta->get('temp', 'default value');
    }

    $stop = microtime(true);
    $time = $stop - $start;
    $name = get_class($meta);
    echo "Class: $name\nSeconds: $time\n\n";
}

echo '</pre>';
