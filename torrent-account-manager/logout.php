<?php
	require('functions.php');

/*
	delCookie();

	head('Kijelentkez�s...', 'public');
*/

	head('Kijelentkez�s...');

	delCookie();

	redir('login.php');

	foot();
?>