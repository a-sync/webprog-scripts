<?php
/* LOGOUT */

include('functions.php');

delCookie();
redir('login.php', true);
?>