<?php

$time = 5;

$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// start
if (file_exists('log.txt')) {
    $fil = fopen('log.txt', r);
    $dat = fread($fil, filesize('log.txt'));
    $dat = json_decode($dat, true);
    fclose($fil);
    $fil = fopen('log.txt', w);
    $dat['t'] = $dat['t'] + 1;
    $dat['m'] = date('Y-m-d H:i:s');
    fwrite($fil, json_encode($dat));
} else {
    $fil = fopen('log.txt', w);
    fwrite($fil, '{"t": 1, "m"' . date('Y-m-d H:i:s') . '}');
    fclose($fil);
}

// function
// close condition
if ($_GET['close']) {
    exit;
}

// end

sleep($time);

file_get_contents($url);
