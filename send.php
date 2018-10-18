<?php
// error_reporting(0);
require_once 'config.php';
require_once 'Medoo.php';

// Using Medoo namespace
use Medoo\Medoo;

$database = new Medoo($DBCONFIG);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.mysubmail.com/mail/xsend.json');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
//设置post方式提交
curl_setopt($ch, CURLOPT_POST, 1);
//设置post数据
$post_data = [
    'appid' => '13827',
    'to' => 'admin@wyr.me',
    'project' => 'uV8xL4',
    'signature' => 'c46fe14d9798440b95f726f34502696d',
];
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$output = curl_exec($ch);
curl_close($ch);

if ($output === false) {
    echo json_encode([
        'err' => 'Send:' . curl_error($ch),
    ]);
    exit;
}

echo json_encode($output);
