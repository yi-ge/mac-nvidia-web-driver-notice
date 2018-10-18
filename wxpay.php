<?php
require_once 'config.php';
header('Access-Control-Allow-Headers: x-requested-with, content-type, X-Requested-With, Content-Type');
header('Content-Type: application/json; charset=utf-8');

class Payjs
{
    private $url = 'https://payjs.cn/api/native';
    public function __construct($data = null)
    {
        $this->data = $data;
    }
    public function pay()
    {
        $data = $this->data;
        $data['mchid'] = $GLOBALS['mchid'];
        $data['sign'] = $this->sign($data);
        return $this->post($data, $this->url);
    }
    public function post($data, $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $rst = curl_exec($ch);
        curl_close($ch);
        return $rst;
    }
    public function sign(array $attributes)
    {
        ksort($attributes);
        $sign = strtoupper(md5(urldecode(http_build_query($attributes)) . '&key=' . $GLOBALS['mkey']));
        return $sign;
    }
}
$arr = [
    'body' => $_GET['body'], // 订单标题
    'out_trade_no' => $_GET['out_trade_no'], // 订单号
    'total_fee' => $_GET['total_fee'], // 金额,单位:分
    'notify_url' => 'https://driver.wyr.me/notify.php', // 回调地址
];
$payjs = new Payjs($arr);
$rst = $payjs->pay();
echo $rst;
