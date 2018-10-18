<?php
header('Content-Type:application/json; charset=utf-8');

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
$xmljson = json_encode($xml);
echo $xmljson;
