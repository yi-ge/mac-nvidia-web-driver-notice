
<?php
error_reporting(0);
require_once 'config.php';
require_once 'Medoo.php';

// Using Medoo namespace
use Medoo\Medoo;

$database = new Medoo($DBCONFIG);

header('Access-Control-Allow-Headers: x-requested-with, content-type, X-Requested-With, Content-Type');
header('Content-Type: application/json; charset=utf-8');

class Request
{
    public static function post($key)
    {
        $_rawBody = file_get_contents('php://input');

        try {
            $parameters = json_decode($_rawBody, true);
            return $parameters === null ? [] : $parameters[$key];
        } catch (InvalidParamException $e) { // Invalid JSON data in request body
            return [];
        }
    }

    public static function get($key)
    {
        return $_GET[$key];
    }
}

if (Request::post('version') != null) {
    if (Request::post('email') == '' && Request::post('phone') == '') {
        echo json_encode([
            'status' => 3,
        ]);
        exit;
    }

    if (!empty(Request::post('email')) && !empty(Request::post('phone'))) {
        $has = $database->has('user', [
            'AND' => [
                'OR' => [
                    'email' => Request::post('email'),
                    'phone' => Request::post('phone'),
                ],
                'version' => Request::post('version'),
            ],
        ]);
    } else if (!empty(Request::post('email'))) {
        $has = $database->has('user', [
            'AND' => [
                'email' => Request::post('email'),
                'version' => Request::post('version'),
            ],
        ]);
    } else if (!empty(Request::post('phone'))) {
        $has = $database->has('user', [
            'AND' => [
                'phone' => Request::post('phone'),
                'version' => Request::post('version'),
            ],
        ]);
    }

    if ($has) {
        $row = $database->update('user', [
            'email' => Request::post('email'),
            'phone' => Request::post('phone'),
            'update_time' => date('Y-m-d H:i:s'),
        ], [
            'AND' => [
                'OR' => [
                    'email' => Request::post('email'),
                    'phone' => Request::post('phone'),
                ],
                'version' => Request::post('version'),
            ],
        ]);

        if (!empty(Request::post('phone'))) {
            $phone = $database->select('user', 'id', [
                'phone' => Request::post('phone'),
            ]);

            $theId = $phone[0];

            if ($theId) {
                $database->insert('order', [
                    'out_trade_no' => '1020180520' . $theId,
                ]);

                echo json_encode([
                    'status' => 4,
                    'row' => $row->rowCount(),
                    'id' => $theId,
                ]);
                exit;
            } else {
                echo json_encode([
                    'status' => 5,
                    'row' => $row->rowCount(),
                ]);
                exit;
            }
        }

        echo json_encode([
            'status' => 2,
            'row' => $row->rowCount(),
        ]);
    } else {
        $database->insert('user', [
            'email' => Request::post('email'),
            'phone' => Request::post('phone'),
            'version' => Request::post('version'),
            'create_time' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s'),
        ]);

        if (!empty(Request::post('phone'))) {
            $theId = $database->id();
            $database->insert('order', [
                'out_trade_no' => '1020180520' . $theId,
            ]);

            echo json_encode([
                'status' => 4,
                'id' => $theId,
            ]);
        } else {
            echo json_encode([
                'status' => 1,
                'id' => $database->id(),
            ]);
        }
    }
} else {
    $datas = $database->select('user', '*', [
        'ORDER' => ['create_time' => 'DESC'],
    ]);

    echo json_encode($datas);
}
