<?php
	require('functions.php');

	function getUserNotif($n) {
		if($n != 0) {
			$notifs = '';
			if($n == 1 || $n == 3 || $n == 5 || $n == 7) { $notifs = 'Rendszer üzenet érkezésérõl'; }
			if($n == 3 || $n == 5 || $n == 7) { $notifs = $notifs.'<br>'; }
			if($n == 2 || $n == 3 || $n == 6 || $n == 7) { $notifs = $notifs.'Használt accountokkal<br>kapcsolatos üzenet érkezésérõl'; }
			if($n == 6 || $n == 7) { $notifs = $notifs.'<br>'; }
			if($n == 4 || $n == 5 || $n == 6 || $n == 7) { $notifs = $notifs.'Privát üzenet érkezésérõl'; }
		}
		else { $notifs = 'Semmirõl se küldjön értesítést!'; }

		return $notifs;
	}

	if(isset($_GET['uid'])) {
		$USER = userData(MYSQL_ASSOC, 'user.php?uid='.$_GET['uid']);
		$USERDATA = getUserData($_GET['uid'], MYSQL_ASSOC, '*', 'headsilent');

		if($USERDATA['uid'] != $USER['uid'] && $USER['tam_class'] < $user_mod) {
			logdie('Nincs jogosultságod megtekinteni a felhasználó adatlapját!', 'headsilent');
		}
		else {
			head('Felhasználó adatlap - uID: '.$USERDATA['uid'].' ('.$USERDATA['tam_user'].')', 'user.php?uid='.$USERDATA['uid']);
		}

		if($USERDATA['accounts_sites'] != '') {
			$tam_sites = getSitesFromIDs($USERDATA['accounts_sites'], MYSQL_ASSOC, 'sid, name');
			$num = count($tam_sites);
			for($i = 0; $num > $i; $i++) {
				$uasite = $tam_sites[$i];
				if(!isset($accounts_sites)) { $accounts_sites = '<a href="site.php?sid='.$uasite['sid'].'">'.$uasite['name'].'</a>'; }
				else { $accounts_sites = $accounts_sites.', <a href="site.php?sid='.$uasite['sid'].'">'.$uasite['name'].'</a>'; }
			}
		}
		else { $accounts_sites = 'Nincsenek saját accountok sehol!'; }

		if($USERDATA['tam_accounts'] != '') {
			$tam_sites = getSitesOfUsersAccounts($USERDATA['tam_accounts'], MYSQL_ASSOC);
			$sitesnum = count($tam_sites);
			for($j = 0; $sitesnum > $j; $j++) {
				$tam_site = $tam_sites[$j];
				if(!isset($user_accounts)) { $user_accounts = '<a href="site.php?sid='.$tam_site['sid'].'">'.$tam_site['name'].'</a><br>'; }
				else { $user_accounts = $user_accounts.'<br><br><a href="site.php?sid='.$tam_site['sid'].'">'.$tam_site['name'].'</a><br>'; }

				$tam_accounts = getAccountsOfUserOnSite($USERDATA['tam_accounts'], $tam_site['sid'], MYSQL_ASSOC);
				$accountsnum = count($tam_accounts);
				for($i = 0; $accountsnum > $i; $i++) {
					$uaccount = $tam_accounts[$i];
					if($uaccount['user'] == '') { $uaccountName = 'aID: '.$uaccount['aid']; }
					else { $uaccountName = $uaccount['user']; }

					if($i == 0) { $user_accounts = $user_accounts.'<a href="account.php?aid='.$uaccount['aid'].'">'.$uaccountName.'</a>'; }
					else { $user_accounts = $user_accounts.', <a href="account.php?aid='.$uaccount['aid'].'">'.$uaccountName.'</a>'; }
				}
			}
		}
		else { $user_accounts = 'Nincsenek accountok!'; }

		if($USERDATA['tam_passkeys'] != '') {
			$tam_sites = getSitesOfUsersAccounts($USERDATA['tam_passkeys'], MYSQL_ASSOC);
			$sitesnum = count($tam_sites);
			for($j = 0; $sitesnum > $j; $j++) {
				$tam_site = $tam_sites[$j];
				if(!isset($user_passkeys)) { $user_passkeys = '<a href="site.php?sid='.$tam_site['sid'].'">'.$tam_site['name'].'</a><br>'; }
				else { $user_passkeys = $user_passkeys.'<br><br><a href="site.php?sid='.$tam_site['sid'].'">'.$tam_site['name'].'</a><br>'; }

				$tam_accounts = getAccountsOfUserOnSite($USERDATA['tam_passkeys'], $tam_site['sid'], MYSQL_ASSOC);
				$accountsnum = count($tam_accounts);
				for($i = 0; $accountsnum > $i; $i++) {
					$uaccount = $tam_accounts[$i];
					if($uaccount['user'] == '') { $uaccountName = 'aID: '.$uaccount['aid']; }
					else { $uaccountName = $uaccount['user']; }

					if($i == 0) { $user_passkeys = $user_passkeys.'<a href="account.php?aid='.$uaccount['aid'].'">'.$uaccountName.'</a>'; }
					else { $user_passkeys = $user_passkeys.', <a href="account.php?aid='.$uaccount['aid'].'">'.$uaccountName.'</a>'; }
				}
			}
		}
		else { $user_passkeys = 'Nincsenek azonosítókulcsok!'; }
?>

	<table align="center" border="1">
		<?php if($USER['tam_class'] >= $user_mod) { ?>
			<tr><td>Felhasználó ID:</td><td><?php echo $USERDATA['uid']; ?></td></tr>
		<?php } ?>
		<tr><td>Felhasználónév:</td><td><?php echo $USERDATA['tam_user']; ?></td></tr>
		<?php if($USERDATA['uid'] == $USER['uid'] || ($USER['tam_class'] >= $user_full && $USER['tam_class'] > $USERDATA['tam_class'])) { ?>
			<tr><td>Jelszó Hash:</td><td><?php echo $USERDATA['tam_pass']; ?></td></tr>
			<tr><td>E-Mail cím:</td><td><?php echo $USERDATA['tam_email']; ?></td></tr>
		<?php } ?>
		<tr><td>Rang:</td><td><?php echo getUserClass($USERDATA['tam_class']); ?></td></tr>
		<tr><td>E-Mail értesítés:</td><td><?php echo getUserNotif($USERDATA['notif']); ?></td></tr>
		<tr><td>Regisztrálás ideje:</td><td><?php echo $USERDATA['reg_time']; ?></td></tr>
		<tr><td>Legutóbbi bejelentkezés:</td><td><?php echo $USERDATA['last_login']; ?></td></tr>
		<?php if($USER['tam_class'] >= $user_mod && $USER['tam_class'] >= $USERDATA['tam_class']) { ?>
			<tr><td>Legutóbbi IP:</td><td><?php echo $USERDATA['last_ip']; ?></td></tr>
		<?php } ?>
		<?php if($USER['tam_class'] >= $user_add && $USER['tam_class'] > $USERDATA['tam_class']) { ?>
			<tr><td>Megerõsítõ kód:</td><td><?php echo $USERDATA['verif']; ?></td></tr>
		<?php } ?>
		<?php if($USER['tam_class'] >= $user_mod && $USER['tam_class'] >= $USERDATA['tam_class']) { ?>
			<tr><td>Saját accountok oldalai:</td><td><?php echo $accounts_sites; ?></td></tr>
			<tr><td>Admin komment:</td><td><?php echo bb($USERDATA['admin_comm']); ?></td></tr>
		<?php } ?>
		<tr><td>Accountok:</td><td><?php echo $user_accounts; ?></td></tr>
		<tr><td>Azonosítókulcsok:</td><td><?php echo $user_passkeys; ?></td></tr>
	</table>

<?php
	}
	else { logdie('Nincs Felhasználó ID megadva!', 'headsilent'); }

	foot();
?>