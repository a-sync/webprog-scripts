<?php
/* INDEX */

include('functions.php');

$USER = checkLogin();


head();

box(0, 'Gyümölcs');
echo('alma');
box(1);

box(0, 'Zöldség');
echo('répa');
box(1);

box(0);
echo('gomba');
box(1);

foot();

?>
