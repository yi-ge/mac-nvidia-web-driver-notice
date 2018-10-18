<?php
error_reporting(0);
require_once 'config.php';
require_once 'Medoo.php';

// Using Medoo namespace
use Medoo\Medoo;

$database = new Medoo($DBCONFIG);

header('Access-Control-Allow-Headers: x-requested-with, content-type, X-Requested-With, Content-Type');
header('Content-Type: application/json; charset=utf-8');

$datas = $database->select('order', 'time_end', [
    'out_trade_no' => $_GET['out_trade_no'],
]);

if (empty($datas[0])) {
    echo json_encode(['status' => 0]);
} else {
    echo json_encode(['status' => 1]);
}
