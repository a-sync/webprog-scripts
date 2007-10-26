<?php
	require('functions.php');

	head('Torrent Account Manager');

	$USER = userData(MYSQL_ASSOC);

	redir('user.php?uid='.$USER['uid']);

	foot();
?>