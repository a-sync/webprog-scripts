<?php
	require('functions.php');


	if($_POST['verif'] == 'add_site') {
		head('Oldal kezelõ', 'managesite.php');//Torrent oldalak listájának oldalára átirányít

		$name = $_POST['name'];
		$domains = preg_replace('/,/', '|', $_POST['domains']);//domain cimek vegerol a '/' jelet levenni
		$passkey_link = $_POST['passkey_link'];
		$user_link = $_POST['user_link'];
		$dindata_link = $_POST['dindata_link'];
		$restrict_calc = $_POST['restrict_calc'];
		$comm = $_POST['comm'];
		$admin_comm = mysql_real_escape_string($_POST['admin_comm']);

		if($name == '' || $domains == '') { die('Legalább az oldal nevét és címét add meg!'); }

		$query = "INSERT INTO tam_sites VALUES (NULL, '$name', '$domains', '$passkey_link', '$user_link', '$dindata_link', '$restrict_calc', '$comm', '$admin_comm')";
		mysql_query($query);

		redir('index.php');//Torrent oldalak listájának oldalára átirányít
	}
	else {
		head('Torrent oldal kezelõ', 'managesite.php');
?>

	<table align="center">
		<form method="post">
		<tr><td>Oldal neve:</td><td><input type="text" name="name" size="40"></td></tr>
		<tr><td>Oldal domain címe(i):</td><td><input type="text" name="domains" size="40"></td></tr>
		<tr><td>Azonosítókulcs linkje:</td><td><input type="text" name="passkey_link" size="40"></td></tr>
		<tr><td>Felhasználók adatlapjának elérése:</td><td><input type="text" name="user_link" size="40"></td></tr>
		<tr><td>Dinamikus adatlekérdezõ linkje:</td><td><input type="text" name="dindata_link" size="40"></td></tr>
		<tr><td>Megszorítás kalkuláló egyenlet:</td><td><input type="text" name="restrict_calc" size="40"></td></tr>
		<tr><td>Komment:</td><td><textarea name="comm"></textarea></td></tr>
		<tr><td>Admin komment:</td><td><textarea name="admin_comm"></textarea></td></tr>
		<input type="hidden" name="verif" value="add_site">
		<tr><td colspan="2" align="center"><input type="submit" name="send"></td></tr>
		</form>
	</table>

<?php
	}

	foot();
?>