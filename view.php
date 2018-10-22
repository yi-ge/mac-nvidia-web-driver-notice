<?php
$myfile = fopen('log.txt', 'r') or die('Unable to open file!');
echo fread($myfile, filesize('log.txt'));
fclose($myfile);
