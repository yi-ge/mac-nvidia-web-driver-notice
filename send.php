<?php
error_reporting(0);
require_once 'config.php';
require_once 'Medoo.php';

// Using Medoo namespace
use Medoo\Medoo;

$database = new Medoo($DBCONFIG);

if ($_GET['key'] != '18289712378917421') {
    die('非法访问');
}

function sendEmail($emailList)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://api.mysubmail.com/mail/xsend.json');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
    // 设置post方式提交
    curl_setopt($ch, CURLOPT_POST, 1);
    // 设置post数据
    $post_data = [
        'appid' => $GLOBALS['emailAppid'],
        'to' => $emailList,
        'project' => $GLOBALS['emailProject'],
        'signature' => $GLOBALS['emailSignature'],
    ];
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $output = curl_exec($ch);
    curl_close($ch);

    if ($output === false) {
        sendEmail();
    }
}

function sendSMS($phoneList)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://api.mysubmail.com/message/multixsend.json');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
    // 设置post方式提交
    curl_setopt($ch, CURLOPT_POST, 1);
    // 设置post数据
    $post_data = [
        'appid' => $GLOBALS['smsAppid'],
        'multi' => $phoneList,
        'project' => $GLOBALS['smsProject'],
        'signature' => $GLOBALS['smsSignature'],
    ];
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $output = curl_exec($ch);
    curl_close($ch);

    if ($output === false) {
        sendEmail();
    }
}

$emailList = $database->select('user', 'email', [
    'AND' => [
        'email_sent' => 0,
        'version' => '18A391',
        'email[!]' => '',
    ],
]);

if (!empty($emailList)) {
    sendEmail(join(',', $emailList));
    $database->update('user', [
        'email_sent' => 1,
        'update_time' => date('Y-m-d H:i:s'),
    ], [
        'AND' => [
            'email_sent' => 0,
            'version' => '18A391',
            'email[!]' => '',
        ],
    ]);
}

$phoneList = $database->select('user', 'phone', [
    'AND' => [
        'phone_sent' => 0,
        'version' => '18A391',
        'phone[!]' => '',
    ],
]);

if (!empty($phoneList)) {
    $tmp = [];
    foreach ($phoneList as $item) {
        $t = [
            'to' => $item,
        ];
        array_push($tmp, $t);
    }
    $phoneList = json_encode($tmp);
    sendSMS($phoneList);
    $database->update('user', [
        'phone_sent' => 1,
        'update_time' => date('Y-m-d H:i:s'),
    ], [
        'AND' => [
            'phone_sent' => 0,
            'version' => '18A391',
            'phone[!]' => '',
        ],
    ]);
}

echo 'ok';
