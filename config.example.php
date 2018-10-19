<?php
$mkey = ''; // 填写通信密钥
$mchid = ''; // 特写商户号
$DBCONFIG = [
    // required
    'database_type' => 'mysql',
    'database_name' => 'macNvidiaWebDriverNotice',
    'server' => 'localhost',
    'username' => 'root',
    'password' => '123456',

    // [optional]
    'charset' => 'utf8',
    'port' => 3306,

    // [optional] Table prefix
    'prefix' => 'notice_',

    // [optional] Enable logging (Logging is disabled by default for better performance)
    'logging' => true,

    // // [optional] MySQL socket (shouldn't be used with server and port)
    // 'socket' => '/tmp/mysql.sock',

    // // [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
    // 'option' => [
    //     PDO::ATTR_CASE => PDO::CASE_NATURAL,
    // ],

    // // [optional] Medoo will execute those commands after connected to the database for initialization
    // 'command' => [
    //     'SET SQL_MODE=ANSI_QUOTES',
    // ],
];

$emailAppid = '';
$emailProject = '';
$emailSignature = '';

$smsAppid = '';
$smsProject = '';
$smsSignature = '';
