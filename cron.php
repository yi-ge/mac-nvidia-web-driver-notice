<?php
error_reporting(0);
require_once 'config.php';
require_once 'Medoo.php';

// Using Medoo namespace
use Medoo\Medoo;

$database = new Medoo($DBCONFIG);

function getNewContent()
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://gfe.nvidia.com/mac-update');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
    $output = curl_exec($ch);
    curl_close($ch);

    if ($output !== false) {
        $xml = simplexml_load_string($output);
        return json_encode($xml);
    } else {
        return getNewContent();
    }
}

$time = 15; // s

$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

ob_end_clean(); //清除之前的缓冲内容，这是必需的，如果之前的缓存不为空的话，里面可能有http头或者其它内容，导致后面的内容不能及时的输出
header('Connection: close'); //告诉浏览器，连接关闭了，这样浏览器就不用等待服务器的响应
header('HTTP/1.1 200 OK'); //可以发送200状态码，以这些请求是成功的，要不然可能浏览器会重试，特别是有代理的情况下
ob_start(); //开始当前代码缓冲
echo '后台执行已开启';
//下面输出http的一些头信息
$size = ob_get_length();
header("Content-Length: $size");
ob_end_flush(); //输出当前缓冲
flush(); //输出PHP缓冲
if (!function_exists('fastcgi_finish_request')) {
    function fastcgi_finish_request()
    {
    }
} else {
    fastcgi_finish_request(); /* 响应完成, 关闭连接 */
}

ignore_user_abort(true); // 后台运行
set_time_limit(0); // 取消脚本运行时间的超时上限

while (1) {
    // start
    $datas = $database->select('data', 'content', [
        'LIMIT' => 1,
        'ORDER' => [
            'id' => 'DESC',
        ],
    ]);

    if (empty($datas[0])) {
        $database->insert('data', [
            'content' => getNewContent(),
            'update_time' => date('Y-m-d H:i:s'),
        ]);
    } else {
        $content = getNewContent();
        if ($datas[0] != $content && $content != 'false' && $content != false) {
            $tmp = json_decode($content, true);
            try {
                if ($tmp['dict']['array']['dict'][0]['string'][4] != '17G3025') {
                    $database->insert('data', [
                        'content' => $content,
                        'update_time' => date('Y-m-d H:i:s'),
                    ]);
                    file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/send.php?key=18289712378917421');
                    exit;
                } else {
                    $database->insert('data', [
                        'content' => $content,
                        'update_time' => date('Y-m-d H:i:s'),
                    ]);
                }
            } catch (Exception $e) {
                $database->insert('data', [
                    'content' => $content,
                    'update_time' => date('Y-m-d H:i:s'),
                ]);
            }
        } else {
            $datas = $database->update('data', [
                'update_time' => date('Y-m-d H:i:s'),
            ], [
                'LIMIT' => 1,
                'ORDER' => [
                    'id' => 'DESC',
                ],
            ]);
        }
    }

    // end
    sleep($time);
}

// sleep($time);

// file_get_contents($url);
