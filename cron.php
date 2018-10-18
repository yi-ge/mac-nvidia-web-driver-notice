<?php
error_reporting(0);
require_once 'config.php';
require_once 'Medoo.php';

// Using Medoo namespace
use Medoo\Medoo;

$database = new Medoo($DBCONFIG);

$time = 5; // s

$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// start
function getNewContent()
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://gfe.nvidia.com/mac-update');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
    $output = curl_exec($ch);

    if ($output === false) {
        echo json_encode([
            'err' => 'The Nvidia server is not responding:' . curl_error($ch),
        ]);
    }

    curl_close($ch);
    $xml = simplexml_load_string($output);
    return json_encode($xml);
}

$datas = $database->select('data', 'content', [
    'LIMIT' => 1,
    'ORDER' => [
        'id' => 'DESC',
    ],
]);

if (empty($datas[0])) {
    $database->insert('data', [
        'content' => getNewContent(),
    ]);
} else {
    $content = getNewContent();
    if ($datas[0] != $content) {
        $database->insert('data', [
            'content' => $content,
        ]);
        file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/send.php');
        exit;
    }
}

// end

sleep($time);

file_get_contents($url);
