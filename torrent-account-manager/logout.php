<?php
	require('functions.php');

/*
	delCookie();

	head('Kijelentkezés...', 'public');
*/

	head('Kijelentkezés...');

	delCookie();

	redir('login.php');

	foot();
?>