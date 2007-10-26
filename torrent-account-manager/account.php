<?php
	require('functions.php');

	function calcFreeType($USER, $ACCOUNTDATA, $type) {
		include('config.php');//fix

		$accountUser = isMatching($USER['uid'], $USER['tam_accounts'], $ACCOUNTDATA['aid'], $ACCOUNTDATA['tam_account']);
		$passkeyUser = isMatching($USER['uid'], $USER['tam_passkeys'], $ACCOUNTDATA['aid'], $ACCOUNTDATA['tam_passkey']);
		if($passkeyUser == 'yes' && $accountUser == 'yes') { logdie('ERROR027: WHAT THE FUCK?! o.O'); }

		if($type == 'account') {
			$userhasacc = isMatchingOne($ACCOUNTDATA['site_id'], $USER['accounts_sites']);//feltétlen kizáró ok a saját acc? (inkább vonódjon le az éppen számítando maxaccountsonsite változobol egy és ugy nézze)
			if($accowner_lock == 'yes' && $userhasacc == 'yes') { return 'no'; }
			if($ACCOUNTDATA['tam_status'] == 1 && $acc_user_free != 'no' && $accountUser == 'no') {
				$accountsOfUserOnSite = getAccountsOfUserOnSite($USER['tam_accounts'], $ACCOUNTDATA['site_id'], MYSQL_ASSOC);
				if(count($accountsOfUserOnSite) >= $maxaccountonsite) { return 'no'; }
				else { return 'yes'; }
			}
			else { return 'no'; }
		}
		elseif($type == 'passkey') {
			if($ACCOUNTDATA['tam_status'] == 2 && $acc_user_free != 'no' && $passkeyUser == 'no' && $accountUser == 'no') {
				$passkeyssOfUserOnSite = getAccountsOfUserOnSite($USER['tam_passkeys'], $ACCOUNTDATA['site_id'], MYSQL_ASSOC);
				if(count($passkeyssOfUserOnSite) >= $maxpasskeyonsite) { return 'no'; }
				else { return 'yes'; }
			}
			else { return 'no'; }
		}
	}

	if(isset($_GET['aid'])) {
		$USER = userData(MYSQL_ASSOC);
		$ACCOUNTDATA = getAccountData($_GET['aid'], MYSQL_ASSOC, '*', 'headsilent');
		$SITEDATA = getSiteData($ACCOUNTDATA['site_id'], MYSQL_ASSOC, '*', 'headsilent');

		$passkeyUser = isMatching($USER['uid'], $USER['tam_passkeys'], $ACCOUNTDATA['aid'], $ACCOUNTDATA['tam_passkey']);
		$accountUser = isMatching($USER['uid'], $USER['tam_accounts'], $ACCOUNTDATA['aid'], $ACCOUNTDATA['tam_account']);
		if($passkeyUser == 'yes' && $accountUser == 'yes') { logdie('ERROR027: WHAT THE FUCK?! o.O'); }

		$getfreeaccount = calcFreeType($USER, $ACCOUNTDATA, 'account');
		$getfreepasskey = calcFreeType($USER, $ACCOUNTDATA, 'passkey');

		if($passkeyUser == 'no' && $accountUser == 'no' && $USER['tam_class'] < $acc_mod && $getfreeaccount == 'no' && $getfreepasskey == 'no') {
			logdie('Nincs jogosultságod megtekinteni az account adatlapját!', 'headsilent');
		}
		else {
			head('Account adatlap - aID: '.$ACCOUNTDATA['aid'].' ('.$SITEDATA['name'].' :: '.$ACCOUNTDATA['user'].')', 'account.php?aid='.$ACCOUNTDATA['aid']);
			if($ACCOUNTDATA['tam_status'] == 0 && $USER['tam_class'] < $acc_mod) { logdie('Az account jelenleg inaktív, ha újra aktív lesz, vagy törlésre kerül, kapsz értesítést!'); }
		}

		$RATIODATA = getRatio($ACCOUNTDATA, $SITEDATA);

		if($ACCOUNTDATA['tam_account'] != '') {
			$tam_account = getUsersFromIDs($ACCOUNTDATA['tam_account'], MYSQL_ASSOC, 'uid, tam_user');
			$num = count($tam_account);

			if($USER['tam_class'] >= $acc_mod) {
				for($i = 0; $num > $i; $i++) {
					$auser = $tam_account[$i];
					if(!isset($account_users0)) { $account_users0 = '<a href="user.php?uid='.$auser['uid'].'">'.$auser['tam_user'].'</a>'; }
					else { $account_users0 = $account_users0.', <a href="user.php?uid='.$auser['uid'].'">'.$auser['tam_user'].'</a>'; }
				}
			}
			else {
				if($accountUser == 'yes') {
					if($acc_user_users == 'uid') {
						for($i = 0; $num > $i; $i++) {
							$auser = $tam_account[$i];
							if(!isset($account_users1)) { $account_users1 = '<a href="user.php?uid='.$auser['uid'].'">uID: '.$auser['uid'].'</a>'; }
							else { $account_users1 = $account_users1.', <a href="user.php?uid='.$auser['uid'].'">uID: '.$auser['uid'].'</a>'; }
						}
					}
					elseif($acc_user_users == 'number') {
						$account_users1 = $num.' felhasználó';
					}
				}
				if($acc_user_free == 'number') {
					$account_users2 = $num.' felhasználó';
				}
			}
		}
		else {
			$account_users0 = 'Nincsenek felhasználók!';
			$account_users1 = $account_users0;
			$account_users2 = $account_users0;
		}

		if($ACCOUNTDATA['tam_passkey'] != '') {
			$tam_passkey = getUsersFromIDs($ACCOUNTDATA['tam_passkey'], MYSQL_ASSOC, 'uid, tam_user');
			$num = count($tam_passkey);

			if($USER['tam_class'] >= $acc_mod) {
				for($i = 0; $num > $i; $i++) {
					$puser = $tam_passkey[$i];
					if(!isset($passkey_users0)) { $passkey_users0 = '<a href="user.php?uid='.$puser['uid'].'">'.$puser['tam_user'].'</a>'; }
					else { $passkey_users0 = $passkey_users0.', <a href="user.php?uid='.$puser['uid'].'">'.$puser['tam_user'].'</a>'; }
				}
			}
			else {
				if($passkeyUser == 'yes') {
					if($acc_user_users == 'uid') {
						for($i = 0; $num > $i; $i++) {
							$puser = $tam_passkey[$i];
							if(!isset($passkey_users1)) { $passkey_users1 = '<a href="user.php?uid='.$puser['uid'].'">uID: '.$puser['uid'].'</a>'; }
							else { $passkey_users1 = $passkey_users1.', <a href="user.php?uid='.$puser['uid'].'">uID: '.$puser['uid'].'</a>'; }
						}
					}
					elseif($acc_user_users == 'number') {
						$passkey_users1 = $num.' felhasználó';
					}
				}
				if($acc_user_free == 'number') {
					$passkey_users2 = $num.' felhasználó';
				}
			}
		}
		else {
			$passkey_users0 = 'Nincsenek felhasználók!';
			$passkey_users1 = $passkey_users0;
			$passkey_users2 = $passkey_users0;
		}
?>

	<table align="center" border="1">
		<?php if($USER['tam_class'] >= $acc_mod) { ?>
			<tr><td>Account ID:</td><td><?php echo $ACCOUNTDATA['aid']; ?></td></tr>
		<?php } ?>
		<tr><td>Oldal:</td><td><?php echo '<a href="site.php?sid='.$SITEDATA['sid'].'">'.$SITEDATA['name'].'</a>'; ?></td></tr>
		<tr><td>Felhasználó ID:</td><td><?php echo $ACCOUNTDATA['user_id']; ?></td></tr>
		<tr><td>Felhasználónév:</td><td><?php echo $ACCOUNTDATA['user']; ?></td></tr>
		<?php if($accountUser == 'yes' || $USER['tam_class'] >= $acc_full) { ?>
			<tr><td>Jelszó:</td><td><?php echo $ACCOUNTDATA['password']; ?></td></tr>
		<?php } if($passkeyUser == 'yes' || $accountUser == 'yes' || $USER['tam_class'] >= $acc_full) { ?>
			<tr><td>Azonosítókulcs:</td><td><?php echo $ACCOUNTDATA['passkey']; ?></td></tr>
		<?php } if($USER['tam_class'] >= $acc_full) { ?>
			<tr><td>E-Mail:</td><td><?php echo $ACCOUNTDATA['email']; ?></td></tr>
			<tr><td>E-Mail jelszó:</td><td><?php echo $ACCOUNTDATA['email_pass']; ?></td></tr>
		<?php } ?>
		<tr><td>Arány:</td><td><?php echo $RATIODATA['ratio']; ?></td></tr>
		<tr><td>Arány érték:</td><td><?php echo $RATIODATA['value']; ?></td></tr>
		<tr><td>Arány stabilitás:</td><td><?php echo number_format($RATIODATA['datatraffic'], 0, '', ''); ?></td></tr>
		<tr><td>Feltöltés:</td><td><?php echo $RATIODATA['upload'].' GB'; ?></td></tr>
		<tr><td>Letöltés:</td><td><?php echo $RATIODATA['download'].' GB'; ?></td></tr>
		<tr><td>Állapot:</td><td><?php echo getAccStatus($ACCOUNTDATA['tam_status']); ?></td></tr>
<!--		<tr><td>Megszorítások:</td><td><?php //megszorítás kalkulálo meghívása site_id-vel ?></td></tr> -->
		<tr><td>Komment:</td><td><?php echo bb($ACCOUNTDATA['comm']); ?></td></tr>
		<?php if($USER['tam_class'] >= $acc_mod) { ?>
			<tr><td>Admin komment:</td><td><?php echo bb($ACCOUNTDATA['admin_comm']); ?></td></tr>
			<tr><td>Account használók:</td><td><?php echo $account_users0; ?></td></tr>
			<tr><td>Azonosítókulcs használók:</td><td><?php echo $passkey_users0; ?></td></tr>
		<?php } else { if($acc_user_users != 'no' && ($accountUser == 'yes' || $passkeyUser == 'yes')) { ?>
			<tr><td>Account használók:</td><td><?php echo $account_users1; ?></td></tr>
			<tr><td>Azonosítókulcs használók:</td><td><?php echo $passkey_users1; ?></td></tr>
		<?php } elseif($acc_user_free == 'number' && ($getfreeaccount == 'yes' || $getfreepasskey == 'yes')) { ?>
			<tr><td>Account használók:</td><td><?php echo $account_users2; ?></td></tr>
			<tr><td>Azonosítókulcs használók:</td><td><?php echo $passkey_users2; ?></td></tr>
		<?php } } ?>
	</table>

<?php
	}
	else { logdie('Nincs Account ID megadva!', 'headsilent'); }

	foot();
?>