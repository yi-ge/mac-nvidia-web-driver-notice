<?php
error_reporting(0);
require_once 'config.php';
require_once 'Medoo.php';

// Using Medoo namespace
use Medoo\Medoo;

$database = new Medoo($DBCONFIG);

header('Access-Control-Allow-Headers: x-requested-with, content-type, X-Requested-With, Content-Type');
header('Content-Type: application/json; charset=utf-8');

$datas = $database->select('data', 'content', [
    'LIMIT' => 1,
    'ORDER' => [
        'id' => 'DESC',
    ],
]);

if (!empty($datas[0])) {
    echo $datas[0];
} else {
    header('HTTP/1.1 500 Internal Server Error');
    die('数据错误');
}
