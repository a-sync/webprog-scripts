<?php
	require('functions.php');

	function checkDupeUser($tam_user, $mod = 0) {
		$query = "SELECT tam_user FROM tam_users";
		$queryresult = mysql_query($query);
		$resultnum = mysql_num_rows($queryresult);

		if($resultnum < 1) { logdie('ERROR018: WHAT THE FUCK?! o.O'); }

		$j = 0;

		for($i = 1; $resultnum >= $i; $i++) {
			$row = mysql_fetch_array($queryresult);

			if(strtolower($tam_user) == strtolower($row['tam_user'])) {
				$j++;
			}
		}

		if($j > 1 + $mod) { logdie('ERROR019: WHAT THE FUCK?! o.O'); }
		if($j != 0 + $mod) { logdie('Ez a felhaszn�l�n�v m�r foglalt!'); }
	}

	function checkDupeEmail($tam_email, $mod = 0) {
		$query = "SELECT tam_email FROM tam_users";
		$queryresult = mysql_query($query);
		$resultnum = mysql_num_rows($queryresult);

		if($resultnum < 1) { logdie('ERROR041: WHAT THE FUCK?! o.O'); }

		$j = 0;

		for($i = 1; $resultnum >= $i; $i++) {
			$row = mysql_fetch_array($queryresult);

			if(strtolower($tam_email) == strtolower($row['tam_email'])) {
				$j++;
			}
		}

		if($j > 1 + $mod) { logdie('ERROR042: WHAT THE FUCK?! o.O'); }
		if($j != 0 + $mod) { logdie('Ez az e-mail m�r foglalt!'); }
	}

	function siteBoxList($sidList = 'no') {
		if($sidList != 'no') { $sidArray = explode('|', $sidList); }

		$query = "SELECT sid, name FROM tam_sites ORDER BY name ASC";
		$result = mysql_query($query);
		$nrows = mysql_num_rows($result);

		for($i = 1; $nrows >= $i; $i++) {
			$row = mysql_fetch_assoc($result);

			if(isset($sidArray) && in_array($row['sid'], $sidArray)) { $checked = ' checked'; }
			else { $checked = ''; }

			if($i != 1) { echo '<br>'; }
			echo '<input type="checkbox" name="ID" value="'.$row['sid'].'"'.$checked.'>'.$row['name'];
		}
	}

	if(isset($_GET['action']) && isset($_GET['uid'])) {
		if(isset($_POST['verif']) && isset($_POST['uid'])) {
			if($_GET['action'] == 'modify_user' && $_POST['verif'] == 'modify_user') {

				if($_POST['uid'] != $_GET['uid']) { logdie('ERROR043: WHAT THE FUCK?! o.O', 'head'); }

				$USER = userData(MYSQL_ASSOC, 'user.php?uid='.$_POST['uid']);
				$USERDATA = getUserData($_POST['uid'], MYSQL_ASSOC, 'uid, tam_user, tam_pass, tam_email, tam_class, accounts_sites, notif, verif, reg_time, admin_comm', 'headsilent');

				if(($USERDATA['uid'] != $USER['uid'] && $USER['tam_class'] < $user_mod) || $USER['tam_class'] < $USERDATA['tam_class']) {
					logdie('Nincs jogosults�god m�dos�tani a felhaszn�l� adatlapj�t!', 'headsilent');
				}
				else {
					head('Felhaszn�l� m�dos�t�s - uID: '.$USERDATA['uid'].' ('.$USERDATA['tam_user'].')', 'user.php?uid='.$USERDATA['uid']);
				}

				if($USERDATA['uid'] == $USER['uid']) {
					if($_POST['user'] == '' || ($_POST['password1'] == '' && $USERDATA['tam_pass'] == '')) { die('Felhaszn�l�n�v �s Jelsz� sz�ks�ges.'); }

					if($USER['tam_class'] >= $user_full) {
						if($_POST['user'] != '') {
							$user_name = esc($_POST['user']);
							if(!preg_match('/^[A-Za-z0-9]{3,32}$/', $user_name)) { die('A felhaszn�l�n�v minimum 3, maximum 32 karakter hossz� legyen, csak kisbet�, nagybet� �s sz�m szerepelhet benne az angol �b�c�-b�l!'); }
							checkDupeUser($user_name, 1);
						} else { $user_name = ''; }
					} else { $user_name = $USERDATA['tam_user']; }

					if($_POST['password1'] != '' || $_POST['password2'] != '') {
						$password1 = esc($_POST['password1']);
						$password2 = esc($_POST['password2']);
						if($password1 != $password2) { die('A jelsz�meger�s�t�s helytelen!'); }
						else {
							if(strlen($password1) < 6 || strlen($password1) > 32) { die('A jelsz� minimum 6, maximum 32 karakter hossz� legyen!'); }
							if(preg_match('/'.strtolower($user_name).'/', strtolower($password1))) { die('A jelsz�ban ne szerepeljen a felhaszn�l�n�v!'); }
							if(!preg_match('/[a-z���������]/', $password1)) { die('A jelsz�ban szerepelnie kell kisbet�nek!'); }
							if(!preg_match('/[A-Z���������]/', $password1)) { die('A jelsz�ban szerepelnie kell nagybet�nek!'); }
							if(!preg_match('/[0-9]/', $password1)) { die('A jelsz�ban szerepelnie kell sz�mnak!'); }
						}
						$password = ident($password1, $USERDATA['reg_time']);
					} else { $password = $USERDATA['tam_pass']; }

					if($USER['tam_class'] >= $user_mod) {
						if($_POST['email'] != '') {
							$email = esc($_POST['email']);
							if(!preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $email)) { die('Val�s e-mail c�met adj meg!'); }
							checkDupeEmail($email, 1);
						} else { $email = ''; }

						$accounts_sites = esc($_POST['IDlist']);
					} else { $email = $USERDATA['tam_email']; $accounts_sites = $USERDATA['accounts_sites']; }

					if(isset($_POST['sysnotif'])) { $notifs_sys = $_POST['sysnotif']; } else { $notifs_sys = 0;};
					if(isset($_POST['accnotif'])) { $notifs_acc = $_POST['accnotif']; } else { $notifs_acc = 0;};
					if(isset($_POST['pmnotif'])) { $notifs_pm = $_POST['pmnotif']; } else { $notifs_pm = 0;};
					$notifs = $notifs_sys + $notifs_acc + $notifs_pm;
					$notif = esc($notifs);

					if($USER['tam_class'] >= $user_full) {
						$admin_comm = esc($_POST['admin_comm']);
					} else { $admin_comm = $USERDATA['admin_comm']; }

					$uid = $USERDATA['uid'];

					$query = "UPDATE tam_users SET tam_user = '$user_name', tam_pass = '$password', tam_email = '$email', accounts_sites = '$accounts_sites', notif = '$notif', admin_comm = '$admin_comm' WHERE uid = '$uid'";
					mysql_query($query);
					//add admin_comm email cser�n�l

					addCookie($uid, $password);

					redir('manageuser.php?action=modify_user&uid='.$uid);
				}
				else {
					if($_POST['user_verif'] == '' && ($_POST['user'] == '' || ($_POST['password1'] == '' && $USERDATA['tam_pass'] == ''))) { die('Meger�s�t� k�d, vagy a Felhaszn�l�n�v �s Jelsz� sz�ks�ges.'); }

          if($USER['tam_class'] >= $user_full) {
						if($_POST['user'] != '') {
							$user_name = esc($_POST['user']);
							if(!preg_match('/^[A-Za-z0-9]{3,32}$/', $user_name)) { die('A felhaszn�l�n�v minimum 3, maximum 32 karakter hossz� legyen, csak kisbet�, nagybet� �s sz�m szerepelhet benne az angol �b�c�-b�l!'); }
							checkDupeUser($user_name, 1);
						} else { $user_name = ''; }

            if($_POST['password1'] != '' || $_POST['password2'] != '') {
              $password1 = esc($_POST['password1']);
              $password2 = esc($_POST['password2']);
              if($password1 != $password2) { die('A jelsz�meger�s�t�s helytelen!'); }
              else {
                if(strlen($password1) < 6 || strlen($password1) > 32) { die('A jelsz� minimum 6, maximum 32 karakter hossz� legyen!'); }
                if(preg_match('/'.strtolower($user_name).'/', strtolower($password1))) { die('A jelsz�ban ne szerepeljen a felhaszn�l�n�v!'); }
                if(!preg_match('/[a-z���������]/', $password1)) { die('A jelsz�ban szerepelnie kell kisbet�nek!'); }
                if(!preg_match('/[A-Z���������]/', $password1)) { die('A jelsz�ban szerepelnie kell nagybet�nek!'); }
                if(!preg_match('/[0-9]/', $password1)) { die('A jelsz�ban szerepelnie kell sz�mnak!'); }
              }
              $password = ident($password1, $USERDATA['reg_time']);
            } else { $password = $USERDATA['tam_pass']; }

						if($_POST['email'] != '') {
							$email = esc($_POST['email']);
							if(!preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $email)) { die('Val�s e-mail c�met adj meg!'); }
							checkDupeEmail($email, 1);
						} else { $email = ''; }

					} else {
              $user_name = $USERDATA['tam_user'];
              $password = $USERDATA['tam_pass'];
              $email = $USERDATA['tam_email'];
          }

					$class = esc($_POST['class']);
					if($class >= $USER['tam_class']) { logdie('ERROR050: A megadott rang be�ll�t�s�hoz nincs jogod!', 'silent'); }

					if(isset($_POST['sysnotif'])) { $notifs_sys = $_POST['sysnotif']; } else { $notifs_sys = 0;};
					if(isset($_POST['accnotif'])) { $notifs_acc = $_POST['accnotif']; } else { $notifs_acc = 0;};
					if(isset($_POST['pmnotif'])) { $notifs_pm = $_POST['pmnotif']; } else { $notifs_pm = 0;};
					$notifs = $notifs_sys + $notifs_acc + $notifs_pm;
					$notif = esc($notifs);

					if($USER['tam_class'] >= $user_add) {
						$verif = esc($_POST['user_verif']);
					} else { $verif = $USERDATA['verif']; }

          $accounts_sites = esc($_POST['IDlist']);

					$admin_comm = esc($_POST['admin_comm']);

					$uid = $USERDATA['uid'];

					$query = "UPDATE tam_users SET tam_user = '$user_name', tam_pass = '$password', tam_email = '$email', tam_class = '$class', accounts_sites = '$accounts_sites', notif = '$notif', verif = '$verif', admin_comm = '$admin_comm' WHERE uid = '$uid'";
					mysql_query($query);
					//add admin_comm email cser�n�l, verif cser�n�l

					redir('user.php?uid='.$uid);
				}
			}
			if($_GET['action'] == 'delete_user' && $_POST['verif'] == 'delete_user') {
				head('Felhaszn�l� t�rl�s...', 'userlist.php', $user_full);

				if($_POST['uid'] != $_GET['uid']) { logdie('ERROR025: WHAT THE FUCK?! o.O'); }
				$USERDATA = getUserData($_POST['uid'], MYSQL_ASSOC);
				$uid = $USERDATA['uid'];

				session_start();
				if(bin2hex(md5($_POST['captcha'], TRUE)) != $_SESSION['captcha']) {
					redir('manageuser.php?action=delete_user&uid='.$uid.'&error=captcha');
				}
				session_unset();
				session_destroy();

				delUserIDFromAccounts($USERDATA, 'account');
				delUserIDFromAccounts($USERDATA, 'passkey');

				$query = "DELETE FROM tam_users WHERE uid = '$uid'";
				mysql_query($query);//?�zenet az ?�rintetteknek?
				//log a t�rl�sr�l

				redir('userlist.php');
			}
		}
		else {
			if($_GET['action'] == 'modify_user') {
				$USER = userData(MYSQL_ASSOC, 'user.php?uid='.$_GET['uid']);
				$USERDATA = getUserData($_GET['uid'], MYSQL_ASSOC, 'uid, tam_user, tam_email, tam_class, accounts_sites, notif, verif, admin_comm', 'headsilent');

				if(($USERDATA['uid'] != $USER['uid'] && $USER['tam_class'] < $user_mod) || $USER['tam_class'] < $USERDATA['tam_class']) {
					logdie('Nincs jogosults�god m�dos�tani a felhaszn�l� adatlapj�t!', 'headsilent');
				}
				else {
					head('Felhaszn�l� m�dos�t�s - uID: '.$USERDATA['uid'].' ('.$USERDATA['tam_user'].')', 'user.php?uid='.$USERDATA['uid']);
				}

				$n = $USERDATA['notif'];//simplify

				if($USERDATA['uid'] == $USER['uid']) {
?>

					<table align="center">
						<form method="post" name="modify_user">
								<tr><td>Felhaszn�l�n�v:</td><td><input type="text" name="user" maxlength="32"<?php if($USER['tam_class'] < $user_full) { echo ' disabled'; } ?> value="<?php echo $USERDATA['tam_user']; ?>"></td></tr>
								<tr><td>�j jelsz�:</td><td><input type="password" name="password1" maxlength="32"></td></tr>
								<tr><td>�j jelsz� m�gegyszer:</td><td><input type="password" name="password2" maxlength="32"></td></tr>
								<tr><td>E-Mail c�m:</td><td><input type="text" name="email" maxlength="255"<?php if($USER['tam_class'] < $user_mod) { echo ' disabled'; } ?> value="<?php echo $USERDATA['tam_email']; ?>"></td></tr>
								<tr><td>E-Mail �rtes�t�s:</td><td>
									<input type="checkbox" name="sysnotif" value="1"<?php if($n == 1 || $n == 3 || $n == 5 || $n == 7) { echo ' checked'; } ?>>Rendszer �zenet �rkez�s�r�l<br>
									<input type="checkbox" name="accnotif" value="2"<?php if($n == 2 || $n == 3 || $n == 6 || $n == 7) { echo ' checked'; } ?>>Haszn�lt accountokkal<br>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;kapcsolatos �zenet �rkez�s�r�l<br>
									<input type="checkbox" name="pmnotif" value="4"<?php if($n == 4 || $n == 5 || $n == 6 || $n == 7) { echo ' checked'; } ?>>Priv�t �zenet �rkez�s�r�l
								</td></tr>
								<?php if($USER['tam_class'] >= $user_mod) { ?>
									<script type="text/javascript" src="functions.js"></script>
									<tr><td>Saj�t accountok oldalai:</td><td><?php siteBoxList($USERDATA['accounts_sites']); ?></td></tr>
									<input type="hidden" name="IDlist">
								<?php } if($USER['tam_class'] >= $user_full) { ?>
									<tr><td>Admin komment:</td><td><textarea name="admin_comm"><?php echo $USERDATA['admin_comm']; ?></textarea></td></tr>
								<?php } ?>
							<input type="hidden" name="uid" value="<?php echo $USERDATA['uid']; ?>">
							<input type="hidden" name="verif" value="modify_user">
							<tr><td colspan="2" align="center"><input type="submit" name="send"<?php if($USER['tam_class'] >= $user_mod) { echo ' onClick="setID(\'modify_user\', \'IDlist\')"'; } ?>></td></tr>
						</form>
					</table>

<?php
				}
				else {
?>

					<table align="center">
						<form method="post" name="modify_user">
								<script type="text/javascript" src="functions.js"></script>
								<tr><td>Felhaszn�l�n�v:</td><td><input type="text" name="user" maxlength="32"<?php if($USER['tam_class'] < $user_full) { echo ' disabled'; } ?> value="<?php echo $USERDATA['tam_user']; ?>"></td></tr>
								<?php if($USER['tam_class'] >= $user_full) { ?>
									<tr><td>�j jelsz�:</td><td><input type="password" name="password1" maxlength="32"></td></tr>
									<tr><td>�j jelsz� m�gegyszer:</td><td><input type="password" name="password2" maxlength="32"></td></tr>
									<tr><td>E-Mail c�m:</td><td><input type="text" name="email" maxlength="255" value="<?php echo $USERDATA['tam_email']; ?>"></td></tr>
								<?php } ?>
								<tr><td>Rang:</td><td>
									<select name="class" size="1">
										<option value="0"<?php if($USERDATA['tam_class'] == 0) { echo ' selected'; } ?>>Felhaszn�l�</option>
										<?php if($USER['tam_class'] > 1) { ?>
											<option value="1"<?php if($USERDATA['tam_class'] == 1) { echo ' selected'; } ?>>Moder�tor</option>
										<?php } if($USER['tam_class'] > 2) { ?>
											<option value="2"<?php if($USERDATA['tam_class'] == 2) { echo ' selected'; } ?>>Adminisztr�tor</option>
										<?php } ?>
									</select>
								</td></tr>
								<tr><td>E-Mail �rtes�t�s:</td><td>
									<input type="checkbox" name="sysnotif" value="1"<?php if($n == 1 || $n == 3 || $n == 5 || $n == 7) { echo ' checked'; } ?>>Rendszer �zenet �rkez�s�r�l<br>
									<input type="checkbox" name="accnotif" value="2"<?php if($n == 2 || $n == 3 || $n == 6 || $n == 7) { echo ' checked'; } ?>>Haszn�lt accountokkal<br>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;kapcsolatos �zenet �rkez�s�r�l<br>
									<input type="checkbox" name="pmnotif" value="4"<?php if($n == 4 || $n == 5 || $n == 6 || $n == 7) { echo ' checked'; } ?>>Priv�t �zenet �rkez�s�r�l
								</td></tr>
								<?php if($USER['tam_class'] >= $user_add) { ?>
									<tr><td>Meger�s�t� k�d:</td><td><input type="text" name="user_verif" maxlength="32" value="<?php echo $USERDATA['verif']; ?>"><input type="button" onClick="randomString('modify_user', 'user_verif')" value="&#8226;"></td></tr>
								<?php } ?>
								<tr><td>Saj�t accountok oldalai:</td><td><?php siteBoxList($USERDATA['accounts_sites']); ?></td></tr>
								<input type="hidden" name="IDlist">
								<tr><td>Admin komment:</td><td><textarea name="admin_comm"><?php echo $USERDATA['admin_comm']; ?></textarea></td></tr>
							<input type="hidden" name="uid" value="<?php echo $USERDATA['uid']; ?>">
							<input type="hidden" name="verif" value="modify_user">
							<tr><td colspan="2" align="center"><input type="submit" name="send" onClick="setID('modify_user', 'IDlist')"></td></tr>
						</form>
					</table>

<?php
				}
			}
			if($_GET['action'] == 'delete_user') {
				$USERDATA = getUserData($_GET['uid'], MYSQL_ASSOC, 'uid, tam_user', 'headsilent');

				head('Felhaszn�l� t�rl�s - uID: '.$USERDATA['uid'].' ('.$USERDATA['tam_user'].')', 'user.php?uid='.$USERDATA['uid'], $user_full);
?>

				<table align="center">
					<form method="post">
						<tr><td colspan="2" align="center"><a href="user.php?uid=<?php echo $USERDATA['uid']; ?>"><?php echo $USERDATA['tam_user'].' (uID: '.$USERDATA['uid'].')'; ?></a>t�rl�se</td></tr>
						<tr><td align="right"><?php if(isset($_GET['error']) && $_GET['error'] == 'captcha') { echo '<font size="3" color="red">*</font>'; } ?>Meger�s�t�k�d:</td>
						<td><img src="captcha.php" border="1"></td></tr>
						<tr><td colspan="2" align="center"><input type="text" name="captcha"></td></tr>

						<input type="hidden" name="verif" value="delete_user">
						<input type="hidden" name="uid" value="<?php echo $USERDATA['uid']; ?>">

						<tr><td colspan="2" align="center"><input type="submit" name="send"></td></tr>
					</form>
				</table>

<?php
			}
		}
	}
	else {
		if(isset($_GET['action']) && $_GET['action'] == 'add_user') {
			if(isset($_POST['verif']) && $_POST['verif'] == 'add_user') {
				head('Felhaszn�l� hozz�ad�s', 'manageuser.php?action=add_user', $tam_invite);
				$USER = userData(MYSQL_ASSOC);

				$reg_time = date('Y-m-d H:i:s');

				if($USER['tam_class'] < $user_mod) {
					$invite_email = esc($_POST['invite_email']);
					if(!preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $invite_email)) { die('Val�s megh�v� e-mail c�met adj meg!'); }

					$verif = bin2hex(md5(mt_rand(microtime()), TRUE));

					$query = "INSERT INTO tam_users VALUES (NULL, '', '', '', '', '', '', '', '$verif', '$reg_time', '', '', '')";
					mysql_query($query);

					//megh�v� email k�ld�se
					//logol�s, admin comm-ba inf� a megh�v� szem�lyr�l

					redir('index.php');//Felhaszn�l�k list�j�nak oldal�ra �tir�ny�t
				}
				else {

					if($USER['tam_class'] >= $user_full && $_POST['user_verif'] == '' && ($_POST['user'] == '' && $_POST['password1'] == '')) { die('Legal�bb a Meger�s�t� k�dot, vagy a Felhaszn�l�nevet �s a Jelszavakat meg kell adnod.'); }
					if($USER['tam_class'] < $user_full && $USER['tam_class'] >= $user_add && $_POST['user_verif'] == '') { die('Legal�bb a Meger�s�t� k�dot meg kell adnod.'); }
					if($USER['tam_class'] < $user_full && $USER['tam_class'] < $user_add && $_POST['invite_email'] == '') { die('Legal�bb a Megh�v� e-mail c�met meg kell adnod.'); }

					if($_POST['invite_email'] != '') {
						$invite_email = esc($_POST['invite_email']);
						if(!preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $invite_email)) { die('Val�s megh�v� e-mail c�met adj meg!'); }
					} else { $invite_email = ''; }

					if($_POST['user'] != '') {
						$user_name = esc($_POST['user']);
						if(!preg_match('/^[A-Za-z0-9]{3,32}$/', $user_name)) { die('A felhaszn�l�n�v minimum 3, maximum 32 karakter hossz� legyen, csak kisbet�, nagybet� �s sz�m szerepelhet benne az angol �b�c�-b�l!'); }
						checkDupeUser($user_name);
					} else { $user_name = ''; }

					if($USER['tam_class'] >= $user_full) {
						if($_POST['password1'] != '' || $_POST['password2'] != '') {
							$password1 = esc($_POST['password1']);
							$password2 = esc($_POST['password2']);
							if($password1 != $password2) { die('A jelsz�meger�s�t�s helytelen!'); }
							else {
								if(strlen($password1) < 6 || strlen($password1) > 32) { die('A jelsz� minimum 6, maximum 32 karakter hossz� legyen!'); }
								if(preg_match('/'.strtolower($user_name).'/', strtolower($password1))) { die('A jelsz�ban ne szerepeljen a felhaszn�l�n�v!'); }
								if(!preg_match('/[a-z���������]/', $password1)) { die('A jelsz�ban szerepelnie kell kisbet�nek!'); }
								if(!preg_match('/[A-Z���������]/', $password1)) { die('A jelsz�ban szerepelnie kell nagybet�nek!'); }
								if(!preg_match('/[0-9]/', $password1)) { die('A jelsz�ban szerepelnie kell sz�mnak!'); }
							}
							$password = ident($password1, $reg_time);
						} else { $password = ''; }

						if($_POST['email'] != '') {
							$email = esc($_POST['email']);
							if(!preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $email)) { die('Val�s e-mail c�met adj meg!'); }
							checkDupeEmail($email);
						} else { $email = ''; }
					}

					$class = esc($_POST['class']);
					if($class >= $USER['tam_class']) { logdie('ERROR040: A megadott rang be�ll�t�s�hoz nincs jogod!', 'silent'); }

					$accounts_sites = esc($_POST['IDlist']);

					if(isset($_POST['sysnotif'])) { $notifs_sys = $_POST['sysnotif']; } else { $notifs_sys = 0;};
					if(isset($_POST['accnotif'])) { $notifs_acc = $_POST['accnotif']; } else { $notifs_acc = 0;};
					if(isset($_POST['pmnotif'])) { $notifs_pm = $_POST['pmnotif']; } else { $notifs_pm = 0;};
					$notifs = $notifs_sys + $notifs_acc + $notifs_pm;
					$notif = esc($notifs);

					if($USER['tam_class'] >= $user_add) {
						$verif = esc($_POST['user_verif']);
					} else { $verif = bin2hex(md5(mt_rand(microtime()), TRUE)); }

					$admin_comm = esc($_POST['admin_comm']);

					$query = "INSERT INTO tam_users VALUES (NULL, '$user_name', '$password', '$email', '', '', '$class', '$accounts_sites', '$notif', '$verif', '$reg_time', '', '', '$admin_comm')";
					mysql_query($query);

					//megh�v� email k�ld�se
					//log user hozz�adva, meghivo elk�ldve, admin comm-ba inf� a megh�v� szem�lyr�l

					redir('index.php');//Felhaszn�l�k list�j�nak oldal�ra �tir�ny�t
				}
			}
			else {
				head('Felhaszn�l� hozz�ad�s', 'manageuser.php?action=add_user', $tam_invite);
				$USER = userData(MYSQL_ASSOC);
?>

				<table align="center">
					<form method="post" name="add_user">
						<tr><td>Megh�v� e-mail c�m:</td><td><input type="text" name="invite_email" maxlength="255"></td></tr>
						<?php if($USER['tam_class'] >= $user_mod) { ?>
							<script type="text/javascript" src="functions.js"></script>
							<tr><td>Felhaszn�l�n�v:</td><td><input type="text" name="user" maxlength="32"></td></tr>
							<?php if($USER['tam_class'] >= $user_full) { ?>
								<tr><td>Jelsz�:</td><td><input type="password" name="password1" maxlength="32"></td></tr>
								<tr><td>Jelsz� m�gegyszer:</td><td><input type="password" name="password2" maxlength="32"></td></tr>
								<tr><td>E-Mail c�m:</td><td><input type="text" name="email" maxlength="255"></td></tr>
							<?php } ?>
							<tr><td>Rang:</td><td>
							<select name="class" size="1">
								<option value="0">Felhaszn�l�</option>
								<?php if($USER['tam_class'] > 1) { ?>
									<option value="1">Moder�tor</option>
								<?php } if($USER['tam_class'] > 2) { ?>
									<option value="2">Adminisztr�tor</option>
								<?php } ?>
							</select>
							</td></tr>
							<tr><td>E-Mail �rtes�t�s:</td><td>
								<input type="checkbox" name="sysnotif" value="1">Rendszer �zenet �rkez�s�r�l<br>
								<input type="checkbox" name="accnotif" value="2">Haszn�lt accountokkal<br>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;kapcsolatos �zenet �rkez�s�r�l<br>
								<input type="checkbox" name="pmnotif" value="4">Priv�t �zenet �rkez�s�r�l
							</td></tr>
							<?php if($USER['tam_class'] >= $user_add) { ?>
								<tr><td>Meger�s�t� k�d:</td><td><input type="text" name="user_verif" maxlength="32"><input type="button" onClick="randomString('add_user', 'user_verif')" value="&#8226;"></td></tr>
							<?php } ?>
							<tr><td>Saj�t accountok oldalai:</td><td><?php siteBoxList(); ?></td></tr>
							<input type="hidden" name="IDlist">
							<tr><td>Admin komment:</td><td><textarea name="admin_comm"></textarea></td></tr>
						<?php } ?>
						<input type="hidden" name="verif" value="add_user">
						<tr><td colspan="2" align="center"><input type="submit" name="send"<?php if($USER['tam_class'] >= $user_mod) { echo ' onClick="setID(\'add_user\', \'IDlist\')"'; } ?>></td></tr>
					</form>
				</table>

<?php
			}
		}
		else {
			logdie('Nincs megadva minden utas�t�s!', 'headsilent');
		}
	}

	foot();
?>
