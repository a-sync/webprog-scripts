<?php
	require('functions.php');

	if(isset($_GET['action']) && isset($_GET['aid'])) {
		if(isset($_POST['verif']) && isset($_POST['aid'])) {
			if($_GET['action'] == 'modify_acc' && $_POST['verif'] == 'modify_acc') {

        if($_POST['aid'] != $_GET['aid']) { logdie('ERROR053: WHAT THE FUCK?! o.O', 'head'); }

				$USER = userData(MYSQL_ASSOC, 'account.php?aid='.$_POST['aid']);
				$ACCOUNTDATA = getAccountData($_GET['aid'], MYSQL_ASSOC, '*', 'headsilent');

        $passkeyUser = isMatching($USER['uid'], $USER['tam_passkeys'], $ACCOUNTDATA['aid'], $ACCOUNTDATA['tam_passkey']);
        $accountUser = isMatching($USER['uid'], $USER['tam_accounts'], $ACCOUNTDATA['aid'], $ACCOUNTDATA['tam_account']);

        $SITEDATA = getSiteData($ACCOUNTDATA['site_id'], MYSQL_ASSOC, 'sid, name', 'headsilent');

        if($passkeyUser == 'no' && $accountUser == 'no' && $USER['tam_class'] < $acc_mod) {
          logdie('Nincs jogosultságod módosítani az accountot!', 'headsilent');
        }
        else {
          head('Account módosítás - aID: '.$ACCOUNTDATA['aid'].' ('.$SITEDATA['name'].' :: '.$ACCOUNTDATA['user'].')', 'account.php?aid='.$ACCOUNTDATA['aid'], $acc_mod);
        	if($ACCOUNTDATA['tam_status'] == 0 && $USER['tam_class'] < $acc_mod) { logdie('Az account jelenleg inaktív, ha újra aktív lesz, vagy törlésre kerül, kapsz értesítést!'); }
        }

        if($USER['tam_class'] >= $acc_mod) {
          $site_id = $SITEDATA['sid'];

          $user = esc($_POST['user']);

          $user_id = esc($_POST['user_id']);

          if($USER['tam_class'] >= $acc_full || $accountUser == 'yes') {
            $password = esc($_POST['password']);

            $passkey = esc($_POST['passkey']);
            if(strlen($passkey) != 32) { logdie('Helytelen azonosítókulcs!'); }//strlow csak 0123456789abcdef karakterek

            if($USER['tam_class'] >= $acc_full) {
              $email = esc($_POST['email']);
              if($email != '' && !preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $email)) { logdie('Valós e-mail címet adj meg!'); }

              $email_pass = esc($_POST['email_pass']);
            } else { $email = $ACCOUNTDATA['email']; $email_pass = $ACCOUNTDATA['email_pass']; }
          } else { $password = $ACCOUNTDATA['password']; $passkey = $ACCOUNTDATA['passkey']; $email = $ACCOUNTDATA['email']; $email_pass = $ACCOUNTDATA['email_pass']; }
        }

				$upload = preg_replace('/,/', '.', $_POST['upload']);
				$upload_type = $_POST['upload_type'];
				$upload_GB = '';
				if($upload != '') {
					if(is_numeric($upload)) { $upload_GB = sizeConverter(number_format($upload, 2, '.', ''), $upload_type); }
					else { logdie('Helytelen feltöltési érték!'); }
				}

				$download = preg_replace('/,/', '.', $_POST['download']);
				$download_type = $_POST['download_type'];
				$download_GB = '';
				if($download != '') {
					if(is_numeric($download)) { $download_GB = sizeConverter(number_format($download, 2, '.', ''), $download_type); }
					else { logdie('Helytelen letöltési érték!'); }
				}

        if($USER['tam_class'] >= $acc_mod) {
          if($USER['tam_class'] >= $acc_full || ($ACCOUNTDATA['tam_status'] != 0 && $ACCOUNTDATA['tam_status'] != 4)) {
            $status = esc($_POST['status']);
            if($USER['tam_class'] < $acc_full && $status == 4) { logdie('ERROR052: WHAT THE FUCK?! o.O', 'silent'); }
          } else { $status = $ACCOUNTDATA['tam_status']; }
        }

				$comm = esc($_POST['comm']);

        if($USER['tam_class'] >= $acc_mod) { $admin_comm = esc($_POST['admin_comm']); }

        $aid = $ACCOUNTDATA['aid'];

        if($USER['tam_class'] >= $acc_mod) {
          //biztosan kell dupe ellenõrzés? ha kell az alább lévõ megfelelõ-e biztosan nem szûri e ki a módosítani kívánt accot CSAK az aid kiszûrésével?
          $check = "SELECT * FROM tam_accounts WHERE aid != '$aid' site_id = '$site_id' AND passkey = '$passkey'";
          if($user != '') { $check = $check." OR user = '$user'"; }
          if($user_id != '') { $check = $check." OR user_id = '$user_id'"; }
          $checkresult = mysql_query($check);
          $dupeNum = mysql_num_rows($checkresult);
          if($dupeNum > 0) {
            if($USER['tam_class'] >= $acc_mod) { logdie('Már létezik azonos accountra vonatkozó bejegyzés!'); }
            else { logdie('Már létezik azonos accountra vonatkozó bejegyzés!'); }//ideiglenesen, lenti helyett || nem szólni, csk adminoknak jelezni és logolni
            //else { log('Már létezik azonos accountra vonatkozó bejegyzés!', 0, 'hack', 9); }//logolás, magas prioritás, azonnal utánanézni
         }
          //log fontos adat módosítva, admin komm fontos adat módosítva xy által
          //if($USER['tam_class'] < $acc_full && $ACCOUNTDATA['tam_status'] == 0)  req (type: system) ellenõrzésre, ?aktiválásra?

          $query = "UPDATE tam_accounts SET site_id = '$site_id', user = '$user', user_id = '$user_id', password = '$password', email = '$email', email_pass = '$email_pass', upload = '$upload_GB', download = '$download_GB', passkey = '$passkey', tam_status = '$status', comm = '$comm', admin_comm = '$admin_comm' WHERE aid = '$aid'";
					mysql_query($query);
        }
        else {
          //admin komm legutóbb módosítva xy által

          $query = "UPDATE tam_accounts SET upload = '$upload_GB', download = '$download_GB', comm = '$comm' WHERE aid = '$aid'";//, admin_comm = '$admin_comm'
					mysql_query($query);
        }

				redir('account.php?aid='.$aid);
      }
			if($_GET['action'] == 'delete_acc' && $_POST['verif'] == 'delete_acc') {
				head('Account törlés...', 'acclist.php', $acc_full);

				if($_POST['aid'] != $_GET['aid']) { logdie('ERROR023: WHAT THE FUCK?! o.O'); }
				$ACCOUNTDATA = getAccountData($_POST['aid'], MYSQL_ASSOC);
				$aid = $ACCOUNTDATA['aid'];

				session_start();
				if(bin2hex(md5($_POST['captcha'], TRUE)) != $_SESSION['captcha']) {
					redir('manageacc.php?action=delete_acc&aid='.$aid.'&error=captcha');
				}
				session_unset();
				session_destroy();

				delAccIDFromUsers($ACCOUNTDATA, 'account');
				delAccIDFromUsers($ACCOUNTDATA, 'passkey');

				$query = "DELETE FROM tam_accounts WHERE aid = '$aid'";
				mysql_query($query);//üzenet az érintetteknek
				//log a törlésrõl

				redir('acclist.php');
			}
		}
		else {
			if($_GET['action'] == 'modify_acc') {
				$USER = userData(MYSQL_ASSOC, 'account.php?aid='.$_GET['aid']);
				$ACCOUNTDATA = getAccountData($_GET['aid'], MYSQL_ASSOC, '*', 'headsilent');

        $passkeyUser = isMatching($USER['uid'], $USER['tam_passkeys'], $ACCOUNTDATA['aid'], $ACCOUNTDATA['tam_passkey']);
        $accountUser = isMatching($USER['uid'], $USER['tam_accounts'], $ACCOUNTDATA['aid'], $ACCOUNTDATA['tam_account']);

        if($passkeyUser == 'no' && $accountUser == 'no' && $USER['tam_class'] < $acc_mod) {
          logdie('Nincs jogosultságod módosítani az accountot!', 'headsilent');
        }
        else {
          $SITEDATA = getSiteData($ACCOUNTDATA['site_id'], MYSQL_ASSOC, 'name', 'headsilent');
          head('Account módosítás - aID: '.$ACCOUNTDATA['aid'].' ('.$SITEDATA['name'].' :: '.$ACCOUNTDATA['user'].')', 'account.php?aid='.$ACCOUNTDATA['aid'], $acc_mod);
        	if($ACCOUNTDATA['tam_status'] == 0 && $USER['tam_class'] < $acc_mod) { logdie('Az account jelenleg inaktív, ha újra aktív lesz, vagy törlésre kerül, kapsz értesítést!'); }
        }

        $s = $ACCOUNTDATA['tam_status'];//simplify

        if($USER['tam_class'] < $acc_mod) { $disabled = true; }
        else { $disabled = false; }
?>

          <script type="text/javascript" src="functions.js"></script>
          <table align="center">
            <form method="post" name="modify_acc">
              <tr><td>Oldal:</td><td><?php siteList($ACCOUNTDATA['site_id'], $disabled); ?></td></tr>
              <tr><td>Felhasználó ID:</td><td><input type="text" name="user_id"<?php if($USER['tam_class'] < $acc_mod) { echo ' disabled'; } ?> value="<?php echo $ACCOUNTDATA['user_id']; ?>"></td></tr>
              <tr><td>Felhasználónév:</td><td><input type="text" name="user"<?php if($USER['tam_class'] < $acc_mod) { echo ' disabled'; } ?> value="<?php echo $ACCOUNTDATA['user']; ?>"></td></tr>
              <?php if($USER['tam_class'] >= $acc_full || $accountUser == 'yes') { ?>
                  <tr><td>Jelszó:</td><td><input type="text" name="password" value="<?php echo $ACCOUNTDATA['password']; ?>"><input type="button" onClick="randomPass('modify_acc', 'password')" value="&#8226;"></td></tr>
                  <tr><td>Azonosítókulcs:</td><td><input type="text" name="passkey" maxlength="32" value="<?php echo $ACCOUNTDATA['passkey']; ?>"></td></tr>
                  <?php if($USER['tam_class'] >= $acc_full) { ?>
                    <tr><td>E-Mail:</td><td><input type="text" name="email" value="<?php echo $ACCOUNTDATA['email']; ?>"></td></tr>
                    <tr><td>E-Mail jelszó:</td><td><input type="text" name="email_pass" value="<?php echo $ACCOUNTDATA['email_pass']; ?>"><input type="button" onClick="randomPass('modify_acc', 'email_pass')" value="&#8226;"></td></tr>
              <?php } } ?>
              <tr><td>Feltöltés:</td><td><input type="text" name="upload" value="<?php echo $ACCOUNTDATA['upload']; ?>">
              <select name="upload_type" size="1">
                <option value="kB">kB</option>
                <option value="MB">MB</option>
                <option selected value="GB">GB</option>
                <option value="TB">TB</option>
                <option value="PB">PB</option>
              </select>
              </td></tr>
              <tr><td>Letöltés:</td><td><input type="text" name="download" value="<?php echo $ACCOUNTDATA['download']; ?>">
              <select name="download_type" size="1">
                <option value="kB">kB</option>
                <option value="MB">MB</option>
                <option selected value="GB">GB</option>
                <option value="TB">TB</option>
                <option value="PB">PB</option>
              </select>
              </td></tr>
              <?php if($USER['tam_class'] >= $acc_mod) { ?>
                <tr><td>Állapot:</td><td>
                <select name="status" size="1"<?php if($USER['tam_class'] < $acc_full && ($s == 0 || $s == 4)) { echo ' disabled'; } ?>>
                  <?php if($USER['tam_class'] >= $acc_full || $s != 4) { ?>
                    <option selected value="0"<?php if($s == 0) { echo ' selected'; } ?>>Inaktív</option>
                    <?php if($USER['tam_class'] >= $acc_full || $s != 0) { ?>
                      <option value="1"<?php if($s == 1) { echo ' selected'; } ?>>Szabad account</option>
                      <option value="2"<?php if($s == 2) { echo ' selected'; } ?>>Szabad azonosítókulcs</option>
                      <option value="3"<?php if($s == 3) { echo ' selected'; } ?>>Használt</option>
                  <?php } } if($USER['tam_class'] >= $acc_full || $s == 4) { ?>
                    <option value="4"<?php if($s == 4) { echo ' selected'; } ?>>Védett</option>
                  <?php } ?>
                </select>
                </td></tr>
              <?php } ?>
              <tr><td>Komment:</td><td><textarea name="comm"><?php echo $ACCOUNTDATA['comm']; ?></textarea></td></tr>
              <?php if($USER['tam_class'] >= $acc_mod) { ?>
                <tr><td>Admin komment:</td><td><textarea name="admin_comm"><?php echo $ACCOUNTDATA['admin_comm']; ?></textarea></td></tr>
              <?php } ?>
              <input type="hidden" name="aid" value="<?php echo $ACCOUNTDATA['aid']; ?>">
              <input type="hidden" name="verif" value="modify_acc">
              <tr><td colspan="2" align="center"><input type="submit" name="send"></td></tr>
            </form>
          </table>

<?php
			}
			if($_GET['action'] == 'delete_acc') {
				$ACCOUNTDATA = getAccountData($_GET['aid'], MYSQL_ASSOC, '*', 'headsilent');
				$SITEDATA = getSiteData($ACCOUNTDATA['site_id'], MYSQL_ASSOC, 'name', 'headsilent');

				head('Account törlés - aID: '.$ACCOUNTDATA['aid'].' ('.$SITEDATA['name'].' :: '.$ACCOUNTDATA['user'].')', 'account.php?aid='.$ACCOUNTDATA['aid'], $acc_full);

				$numUsers = count(getUsersFromIDs($ACCOUNTDATA['tam_account'], MYSQL_ASSOC, 'uid')) + count(getUsersFromIDs($ACCOUNTDATA['tam_passkey'], MYSQL_ASSOC, 'uid'));
?>

				<table align="center">
					<form method="post">
						<tr><td colspan="2" align="center"><a href="account.php?aid=<?php echo $ACCOUNTDATA['aid']; ?>"><?php echo $ACCOUNTDATA['user'].' (aID: '.$ACCOUNTDATA['aid'].')'; ?></a> (<?php echo $SITEDATA['name']; ?>) törlése
						<?php if($numUsers > 0) { echo '<br>FIGYELEM! Az accountot összesen '.$numUsers.' Felhasználó használja!'; } ?></td></tr>
						<tr><td align="right"><?php if(isset($_GET['error']) && $_GET['error'] == 'captcha') { echo '<font size="3" color="red">*</font>'; } ?>Megerõsítõkód:</td>
						<td><img src="captcha.php" border="1"></td></tr>
						<tr><td colspan="2" align="center"><input type="text" name="captcha"></td></tr>

						<input type="hidden" name="verif" value="delete_acc">
						<input type="hidden" name="aid" value="<?php echo $ACCOUNTDATA['aid']; ?>">

						<tr><td colspan="2" align="center"><input type="submit" name="send"></td></tr>
					</form>
				</table>

<?php
			}
		}
	}
	else {
		if(isset($_GET['action']) && $_GET['action'] == 'add_acc') {
			if(isset($_POST['verif']) && $_POST['verif'] == 'add_acc') {
				head('Account hozzáadás...', 'manageacc.php?action=add_acc', $acc_add);
				$USER = userData(MYSQL_ASSOC);
				$SITEDATA = getSiteData($_POST['site_id'], MYSQL_ASSOC, 'sid', 'silent');

				$site_id = $SITEDATA['sid'];

				$user = esc($_POST['user']);

				$user_id = esc($_POST['user_id']);

				$password = esc($_POST['password']);

				$email = esc($_POST['email']);
				if($email != '' && !preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $email)) { logdie('Valós e-mail címet adj meg!'); }

				$email_pass = esc($_POST['email_pass']);

				$upload = preg_replace('/,/', '.', $_POST['upload']);
				$upload_type = $_POST['upload_type'];
				$upload_GB = '';
				if($upload != '') {
					if(is_numeric($upload)) { $upload_GB = sizeConverter(number_format($upload, 2, '.', ''), $upload_type); }
					else { logdie('Helytelen feltöltési érték!'); }
				}

				$download = preg_replace('/,/', '.', $_POST['download']);
				$download_type = $_POST['download_type'];
				$download_GB = '';
				if($download != '') {
					if(is_numeric($download)) { $download_GB = sizeConverter(number_format($download, 2, '.', ''), $download_type); }
					else { logdie('Helytelen letöltési érték!'); }
				}

				$passkey = esc($_POST['passkey']);
				if(strlen($passkey) != 32) { logdie('Helytelen azonosítókulcs!'); }//strlow csak 0123456789abcdef karakterek


				$status = esc($_POST['status']);
				if($USER['tam_class'] < $acc_full && 0 < $status) { logdie('ERROR029: WHAT THE FUCK?! o.O', 'silent'); }

				$comm = esc($_POST['comm']);

				$admin_comm = '';
				if($USER['tam_class'] >= $acc_mod) { $admin_comm = esc($_POST['admin_comm']); }

				$check = "SELECT * FROM tam_accounts WHERE site_id = '$site_id' AND passkey = '$passkey'";
				if($user != '') { $check = $check." OR user = '$user'"; }
				if($user_id != '') { $check = $check." OR user_id = '$user_id'"; }
				$checkresult = mysql_query($check);
				$dupeNum = mysql_num_rows($checkresult);
				if($dupeNum > 0) {
					if($USER['tam_class'] >= $acc_mod) { logdie('Már létezik azonos accountra vonatkozó bejegyzés!'); }
					else { logdie('Már létezik azonos accountra vonatkozó bejegyzés!'); }
					//else { log('Már létezik azonos accountra vonatkozó bejegyzés!', 0, 'hack', 9); }//logolás, magas prioritás, azonnal utánanézni
				}

				//log acc hozzáadva, admin komm acc hozzáadva xy által
				//if($USER['tam_class'] < $acc_full) req (type: system) ellenõrzésre, aktiválásra

				$query = "INSERT INTO tam_accounts VALUES (NULL, '$site_id', '$user', '$user_id', '$password', '$email', '$email_pass', '$upload_GB', '$download_GB', '$passkey', '', '', '$status', '$comm', '$admin_comm')";
				mysql_query($query);
				$inserted_id = mysql_insert_id();
				if($USER['tam_class'] >= $acc_mod) { redir('account.php?aid='.$inserted_id); }
				else { redir('index.php'); }
			}
			else {
				head('Account hozzáadás', 'manageacc.php?action=add_acc', $acc_add);
				$USER = userData(MYSQL_ASSOC);
?>

				<script type="text/javascript" src="functions.js"></script>
				<table align="center">
					<form method="post" name="add_acc">
						<tr><td>Oldal:</td><td><?php siteList(); ?>
						<?php if($USER['tam_class'] >= $site_add) { echo '&nbsp;<a href="managesite.php?action=add_site">Oldal hozzáadás</a>'; }//else link: req új oldal ?>
						</td></tr>
						<tr><td>Felhasználó ID:</td><td><input type="text" name="user_id"></td></tr>
						<tr><td>Felhasználónév:</td><td><input type="text" name="user"></td></tr>
						<tr><td>Jelszó:</td><td><input type="text" name="password"><input type="button" onClick="randomPass('add_acc', 'password')" value="&#8226;"></td></tr>
						<tr><td>Azonosítókulcs:</td><td><input type="text" name="passkey" maxlength="32"></td></tr>
						<tr><td>E-Mail:</td><td><input type="text" name="email"></td></tr>
						<tr><td>E-Mail jelszó:</td><td><input type="text" name="email_pass"><input type="button" onClick="randomPass('add_acc', 'email_pass')" value="&#8226;"></td></tr>
						<tr><td>Feltöltés:</td><td><input type="text" name="upload">
						<select name="upload_type" size="1">
							<option value="kB">kB</option>
							<option value="MB">MB</option>
							<option selected value="GB">GB</option>
							<option value="TB">TB</option>
							<option value="PB">PB</option>
						</select>
						</td></tr>
						<tr><td>Letöltés:</td><td><input type="text" name="download">
						<select name="download_type" size="1">
							<option value="kB">kB</option>
							<option value="MB">MB</option>
							<option selected value="GB">GB</option>
							<option value="TB">TB</option>
							<option value="PB">PB</option>
						</select>
						</td></tr>
						<tr><td>Állapot:</td><td>
						<select name="status" size="1">
							<option selected value="0">Inaktív</option>
						<?php if($USER['tam_class'] >= $acc_full) { ?>
							<option value="1">Szabad account</option>
							<option value="2">Szabad azonosítókulcs</option>
							<option value="3">Használt</option>
							<option value="4">Védett</option>
						<?php } ?>
						</select>
						</td></tr>
						<tr><td>Komment:</td><td><textarea name="comm"></textarea></td></tr>
						<?php if($USER['tam_class'] >= $acc_mod) { ?>
							<tr><td>Admin komment:</td><td><textarea name="admin_comm"></textarea></td></tr>
						<?php } ?>

						<input type="hidden" name="verif" value="add_acc">

						<tr><td colspan="2" align="center"><input type="submit" name="send"></td></tr>
					</form>
				</table>

<?php
			}
		}
		else {
			logdie('Nincs megadva minden utasítás!', 'headsilent');
		}
	}

	foot();
?>