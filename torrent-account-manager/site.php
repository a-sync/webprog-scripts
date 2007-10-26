<?php
	require('functions.php');

	function print_code($code) {
		if($code != '') {
			$str1 = '<?php/*br*/'.$code.'/*br*/?>';
			$str2 = highlight_string($str1, TRUE);
			$str3 = preg_replace('/\/\*br\*\//', '<br>', $str2);
			return $str3;
		}
		return '';
	}

	if(isset($_GET['sid'])) {
		$SITEDATA = getSiteData($_GET['sid'], MYSQL_ASSOC, '*', 'head');

		head('Oldal adatlap - sID: '.$SITEDATA['sid'].' ('.$SITEDATA['name'].')', 'site.php?sid='.$SITEDATA['sid']);

		$USER = userData(MYSQL_ASSOC);

		$site_alldomains = explode('|', $SITEDATA['domains']);
		$num = count($site_alldomains);
		for($i = 0; $num > $i; $i++) {
			if(!isset($site_domains)) { $site_domains = $site_alldomains[$i]; }
			else { $site_domains = $site_domains.'<br>'.$site_alldomains[$i]; }
		}

		$SITEACCOUNTS = getAccountsOfSite($SITEDATA['sid'], MYSQL_ASSOC);

		if($SITEACCOUNTS != '') {
			$num = count($SITEACCOUNTS);
			for($i = 0; $num > $i; $i++) {
				$ACCOUNTDATA = $SITEACCOUNTS[$i];
				if($ACCOUNTDATA['user'] == '') { $accDataName = 'aID: '.$ACCOUNTDATA['aid']; }
				else { $accDataName = $ACCOUNTDATA['user']; }

				if($ACCOUNTDATA['password'] != '') {
					if(!isset($site_accounts)) { $site_accounts = '<a href="account.php?aid='.$ACCOUNTDATA['aid'].'">'.$accDataName.'</a>'; }
					else { $site_accounts = $site_accounts.', <a href="account.php?aid='.$ACCOUNTDATA['aid'].'">'.$accDataName.'</a>'; }
				}
				else {
					if(!isset($site_passkeys)) { $site_passkeys = '<a href="account.php?aid='.$ACCOUNTDATA['aid'].'">'.$accDataName.'</a>'; }
					else { $site_passkeys = $site_passkeys.', <a href="account.php?aid='.$ACCOUNTDATA['aid'].'">'.$accDataName.'</a>'; }
				}
			}
		}
		if(!isset($site_accounts)) { $site_accounts = 'Nincsenek accountok!'; }
		if(!isset($site_passkeys)) { $site_passkeys = 'Nincsenek azonosítókulcsok!'; }
?>

	<table align="center" border="1">
		<?php if($USER['tam_class'] >= $site_mod) { ?>
			<tr><td>Oldal ID:</td><td><?php echo $SITEDATA['sid']; ?></td></tr>
		<?php } ?>
		<tr><td>Oldal neve:</td><td><?php echo $SITEDATA['name']; ?></td></tr>
		<tr><td>Oldal domain címei:</td><td><?php echo $site_domains; ?></td></tr>
		<?php if($USER['tam_class'] >= $site_mod) { ?>
			<tr><td>Azonosítókulcs linkje:</td><td><?php echo wildcard($SITEDATA['passkey_link'], $SITEDATA['domains']); ?></td></tr>
			<tr><td>Felhasználók adatlapjának linkje:</td><td><?php echo wildcard($SITEDATA['user_link'], $SITEDATA['domains']); ?></td></tr>
			<tr><td>Dinamikus adatlekérdezõ linkje:</td><td><?php echo wildcard($SITEDATA['dindata_link'], $SITEDATA['domains']); ?></td></tr>
		<?php } ?>
		<?php if($USER['tam_class'] >= $site_full) { ?>
			<tr><td>Megszorítás kalkuláló egyenlet:</td><td><?php echo print_code($SITEDATA['restrict_calc']); ?></td></tr>
		<?php } ?>
		<tr><td>Komment:</td><td><?php echo bb($SITEDATA['comm']); ?></td></tr>
		<?php if($USER['tam_class'] >= $site_mod) { ?>
			<tr><td>Admin komment:</td><td><?php echo bb($SITEDATA['admin_comm']); ?></td></tr>
		<?php } ?>
		<?php if($USER['tam_class'] >= $acc_mod) { ?>
			<tr><td>Accountok:</td><td><?php echo $site_accounts; ?></td></tr>
			<tr><td>Azonosítókulcsok:</td><td><?php echo $site_passkeys; ?></td></tr>
		<?php } ?>
	</table>

<?php
	}
	else { logdie('Nincs Oldal ID megadva!', 'head'); }

	foot();
?>