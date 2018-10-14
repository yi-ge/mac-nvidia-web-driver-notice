<?php
if (file_exists('log.txt')) {
    $fil = fopen('log.txt', r);
    $dat = fread($fil, filesize('log.txt'));
    $dat = json_decode($dat, true);
    echo '已执行' . $dat['t'] . '次, 上一次执行时间是: ' . $dat['m'] . ', 当前时间是：' . date('Y-m-d H:i:s');
} else {
    echo '尚未开始';
}
