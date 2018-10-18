<?php
error_reporting(0);
require_once 'config.php';
require_once 'Medoo.php';

// Using Medoo namespace
use Medoo\Medoo;

$database = new Medoo($DBCONFIG);

// 字段名称    字段类型    必填参数    说明
// return_code    int(1)    Y    1：支付成功
// total_fee    int(16)    Y    金额。单位：分
// out_trade_no    string(32)    Y    用户端自主生成的订单号
// payjs_order_id    string(32)    Y    PAYJS 订单号
// transaction_id    string(32)    Y    微信用户手机显示订单号
// time_end    string(32)    Y    支付成功时间
// openid    string(32)    Y    用户OPENID标示，本参数没有实际意义，旨在方便用户端区分不同用户
// attach    string(127)    N    用户自定义数据
// mchid    string(16)    Y    PAYJS 商户号
// sign    string(32)    Y    数据签名 详见签名算法

//用于判断所有post进来的参数，长度是否符合，防止有人输入特别长的参数来攻击。
if (strlen($_POST['return_code']) > 1 && strlen($_POST['total_fee']) > 16 && strlen($_POST['out_trade_no']) > 32 && strlen($_POST['payjs_order_id']) > 32 && strlen($_POST['transaction_id']) > 32 && strlen($_POST['time_end']) > 32 && strlen($_POST['openid']) > 32 && strlen($_POST['mchid']) > 16 && strlen($_POST['sign']) > 32) {
    header('HTTP/1.1 500 Internal Server Error');
    die('长度错误');
}

function sign($attributes)
{
    /*
     * 函数sign()是用于生成数字签名。
     * 其参数是一个包含所有API请求参数的数组，其数组下标是对应API请求的参数名，数组数据则对应API参数数据。
     * 返回值是对应这个请求参数的签名，数据类型为String
     */
    ksort($attributes);
    $sign = strtoupper(md5(urldecode(http_build_query($attributes)) . '&key=' . $GLOBALS['mkey']));
    return $sign;
}

function sign_verify_for_array($arr)
{
    /*
     * 函数sign_verify()是一个用于验证数字签名的函数
     * 参数$attributes是一个来自API返回的对象，具体请参考API文档。
     * 当验证成功时，返回true。
     * 当验证失败时，返回false，意味着有人动过这个传来的数据。
     */
    $sign_verify = $arr['sign'];
    unset($arr['sign']);
    if (sign($arr) == $sign_verify) {
        return true;
    } else {
        return false;
    }
}

if ($_POST['return_code'] == 1) {
    // 1.验签逻辑
    //签字验证
    $data = ['return_code' => $_POST['return_code'], 'total_fee' => $_POST['total_fee'], 'out_trade_no' => $_POST['out_trade_no'], 'payjs_order_id' => $_POST['payjs_order_id'], 'transaction_id' => $_POST['transaction_id'], 'time_end' => $_POST['time_end'], 'openid' => $_POST['openid'], 'mchid' => $_POST['mchid'], 'sign' => $_POST['sign']];
    if (!sign_verify_for_array($data)) {
        header('HTTP/1.1 500 Internal Server Error');
        die('签名错误');
    }
    // 2.验重逻辑
    $datas = $database->select('order', 'time_end', [
        'out_trade_no' => $_POST['out_trade_no'],
    ]);

    if (!empty($datas[0])) {
        echo 'success';
        exit;
    }

    // 3.自身业务逻辑

    $row = $database->update('order', [
        'payjs_order_id' => $_POST['payjs_order_id'],
        'transaction_id' => $_POST['transaction_id'],
        'total_fee' => $_POST['total_fee'],
        'openid' => $_POST['openid'],
        'mchid' => $_POST['mchid'],
        'time_end' => $_POST['time_end'],
    ], [
        'out_trade_no' => $_POST['out_trade_no'],
    ]);

    if ($row->rowCount() > 0) {
        echo 'success';
        exit;
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        die('更新订单信息错误');
    }

    // 4.返回 success 字符串（http状态码为200）
    echo 'success';
}
