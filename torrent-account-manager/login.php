<?php
	require("functions.php");

	function removeAd() {//extra.hu reklám eltüntetése egy évre ezen a domainen
		setcookie('s_status', '0', time() + 60 * 60 * 24 * 365 * 1, '/', $_SERVER['HTTP_HOST']);
	}

	function checkUserPass($tam_user, $tam_pass) {
		$query = "SELECT * FROM tam_users";
		$queryresult = mysql_query($query);
		$resultnum = mysql_num_rows($queryresult);

		if($resultnum < 1) { logdie('ERROR020: WHAT THE FUCK?! o.O'); }

		$j = 0;

		for($i = 1; $resultnum >= $i; $i++) {
			$row = mysql_fetch_assoc($queryresult);

			if(strtolower($tam_user) == strtolower($row['tam_user'])) {
				$userData = $row;
				$j++;
			}
		}

		if($j < 1) { logdie('Helytelen Felhasználónév!'); }
		if($j > 1) { logdie('ERROR021: WHAT THE FUCK?! o.O'); }

		checkVerif($userData['verif']);

		$identPass = ident($tam_pass, $userData['reg_time']);
		if($identPass != $userData['tam_pass']) { logdie('Helytelen Jelszó!'); }

		return $userData;
	}

	function lastLogin($USERDATA) {
		$ip = $_SERVER['REMOTE_ADDR'];
		$date = date('Y-m-d H:i:s');
		$uid = $USERDATA['uid'];
		$query = "UPDATE tam_users SET last_ip = '$ip', last_login = '$date' WHERE uid = '$uid'";
		mysql_query($query);
	}

	removeAd();

	if(isset($_POST['action']) && $_POST['action'] == 'login') {
		head('Bejelentkezés...', 'public');

		$user = $_POST['user'];
		$password = $_POST['password'];

		if($user == '' || $password == '') { die('Add meg az adatokat!'); }

		$USERDATA = checkUserPass($user, $password);

		addCookie($USERDATA['uid'], $USERDATA['tam_pass']);

		lastLogin($USERDATA);

		if(isset($_GET['back'])) { $redir = $_GET['back']; }
		else { $redir = 'index.php'; }
		redir($redir);
	}
	elseif(checkLogin() == 'logged_out') {
		head('Bejelentkezés', 'public');
?>
	<table align="center">
		<form method="post">
		<tr><td>Felhasználónév:</td><td><input type="text" name="user" maxlength="32"></td></tr>
		<tr><td>Jelszó:</td><td><input type="password" name="password" maxlength="32"></td></tr>
		<input type="hidden" name="action" value="login">
		<tr><td colspan="2" align="center"><input type="submit" name="send" value="Mehet"></td></tr>
		</form>
	</table>

<?php
	}
	else {
		head('Bejelentkezve - Átirányítás...');

		redir('index.php');//$back értékre átirányít ha van, ha nincs akkormeg index-re
	}

	foot();
?>