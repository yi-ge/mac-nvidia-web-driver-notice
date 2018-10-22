<?php
error_reporting(0);

function getNewContent()
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://driver.wyr.me/last-update.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
    $output = curl_exec($ch);
    curl_close($ch);

    if ($output !== false) {
        return json_decode($output, true)['r'];
    } else {
        return getNewContent();
    }
}

ignore_user_abort(true); // 后台运行
set_time_limit(0); // 取消脚本运行时间的超时上限
date_default_timezone_set('Asia/Shanghai');

while (1) {
    // start
    if (time() - strtotime(getNewContent()) > 30) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://driver.wyr.me/cron.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
        curl_setopt($ch, CURLOPT_TIMEOUT, 2); //只需要设置一个秒的数量就可以
        $output = curl_exec($ch);
        curl_close($ch);
    }
    // end
    sleep($time);
}
