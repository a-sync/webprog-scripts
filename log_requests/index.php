<?php

$url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER['REQUEST_URI'];

$a = print_r(array($url, $_REQUEST), true);

//die($a);

touch('req.log');
file_put_contents('req.log', $a, FILE_APPEND);


echo 'invalid request';

?>