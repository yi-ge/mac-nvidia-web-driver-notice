
<?php
require_once 'Medoo.php';

// Using Medoo namespace
use Medoo\Medoo;

$database = new Medoo([
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
]);

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

    $has = $database->has('user', [
        'AND' => [
            'OR' => [
                'email' => Request::post('email'),
                'phone' => Request::post('phone'),
            ],
            'version' => Request::post('version'),
        ],
    ]);

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

        echo json_encode([
            'status' => 1,
            'id' => $database->id(),
        ]);
    }
} else {
    $datas = $database->select('user', '*', [
        'ORDER' => ['create_time' => 'DESC'],
    ]);

    echo json_encode($datas);
}
