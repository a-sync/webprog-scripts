<?php
	require('functions.php');

	if(isset($_GET['action']) && isset($_GET['sid'])) {
		if(isset($_POST['verif']) && isset($_POST['sid'])) {
			if($_GET['action'] == 'delete_site' && $_POST['verif'] == 'delete_site') {
				head('Oldal törlés...', 'sitelist.php', $site_full);

				if($_POST['sid'] != $_GET['sid']) { logdie('ERROR024: WHAT THE FUCK?! o.O'); }
				$SITEDATA = getSiteData($_POST['sid'], MYSQL_ASSOC, 'sid');
				$sid = $SITEDATA['sid'];

				session_start();
				if(bin2hex(md5($_POST['captcha'], TRUE)) != $_SESSION['captcha']) {
					redir('managesite.php?action=delete_site&sid='.$sid.'&error=captcha');
				}
				session_unset();
				session_destroy();

				$ACCOUNTS = getAccountsOfSite($SITEDATA['sid'], MYSQL_ASSOC, $orderby = 'aid');
				if($ACCOUNTS != '') {
					$numAccounts = count($ACCOUNTS);

					for($l = 0; $numAccounts > $l; $l++) {
						$ACCOUNTDATA = $ACCOUNTS[$l];
						$aid = $ACCOUNTDATA['aid'];

						delAccIDFromUsers($ACCOUNTDATA, 'account');
						delAccIDFromUsers($ACCOUNTDATA, 'passkey');
						$query = "DELETE FROM tam_accounts WHERE aid = '$aid'";
						mysql_query($query);//üzenet az érintetteknek
					}
				}

				$query = "SELECT uid, accounts_sites FROM tam_users WHERE accounts_sites != '' ORDER BY 'uid' ASC";
				$queryresult = mysql_query($query);
				$resultNum = mysql_num_rows($queryresult);

				for($i = 0; $resultNum > $i; $i++) {
					$USERDATA = mysql_fetch_array($queryresult, MYSQL_ASSOC);
					$sites = explode('|', $USERDATA['accounts_sites']);

					$key = array_search($sid, $sites);
					if($key !== FALSE) {
						unset($sites[$key]);
						natcasesort($sites);

						$siteList = implode('|', $sites);
						$uid = $USERDATA['uid'];

						$query = "UPDATE tam_users SET accounts_sites = '$siteList' WHERE uid = '$uid'";
						mysql_query($query);
					}
				}

				$query = "DELETE FROM tam_sites WHERE sid = '$sid'";
				mysql_query($query);//rendszerüzenet
				//log a törlésrõl
				//else restrictions.php fájl megíró funkció meghívása

				redir('sitelist.php');
			}
		}
		else {
			if($_GET['action'] == 'delete_site') {
				$SITEDATA = getSiteData($_GET['sid'], MYSQL_ASSOC, 'sid, name', 'headsilent');

				head('Oldal törlés - sID: '.$SITEDATA['sid'].' ('.$SITEDATA['name'].')', 'site.php?sid='.$SITEDATA['sid'], $site_full);

				$numAccounts = count(getAccountsOfSite($SITEDATA['sid'], MYSQL_ASSOC, $orderby = 'aid'));
?>

				<table align="center">
					<form method="post">
						<tr><td colspan="2" align="center"><a href="site.php?sid=<?php echo $SITEDATA['sid']; ?>"><?php echo $SITEDATA['name'].' (sID: '.$SITEDATA['sid'].')'; ?></a> törlése
						<?php if($numAccounts > 0) { echo '<br>FIGYELEM! Az oldallal a hozzátartozó '.$numAccounts.' account is törlõdni fog!'; } ?></td></tr>
						<tr><td align="right"><?php if(isset($_GET['error']) && $_GET['error'] == 'captcha') { echo '<font size="3" color="red">*</font>'; } ?>Megerõsítõkód:</td>
						<td><img src="captcha.php" border="1"></td></tr>
						<tr><td colspan="2" align="center"><input type="text" name="captcha"></td></tr>

						<input type="hidden" name="verif" value="delete_site">
						<input type="hidden" name="sid" value="<?php echo $SITEDATA['sid']; ?>">

						<tr><td colspan="2" align="center"><input type="submit" name="send"></td></tr>
					</form>
				</table>

<?php
			}
		}
	}
	else {
		if(isset($_GET['action']) && $_GET['action'] == 'add_site') {
			if(isset($_POST['verif']) && $_POST['verif'] == 'add_site') {
				head('Oldal hozzáadás...', 'managesite.php?action=add_site', $site_add);
				$USER = userData(MYSQL_ASSOC);

				$name = esc($_POST['name']);

				$domainList = preg_replace('/ /', '', $_POST['domains']);
				$domainList = preg_replace('/,/', '|', $domainList);
				$domainsArray = explode('|', $domainList);
				$domainsNum = count($domainsArray);
				for($i = 0; $domainsNum > $i; $i++) {
					if(substr($domainsArray[$i], 0, 7) != 'http://') { $domainsArray[$i] = 'http://'.$domainsArray[$i]; }
					if(substr($domainsArray[$i], -1) == '/') { $domainsArray[$i] = substr($domainsArray[$i], 0, -1); }
				}
				$domains = implode('|', $domainsArray);

				if($name == '' || $domains == '') { logdie('Legalább az oldal nevét és címét add meg!'); }

				$passkey_link = esc($_POST['passkey_link']);
				if($passkey_link != '') {
				//	if(!preg_match('/#domain#/', $passkey_link)) { logdie('Az azonosítókulcs linkje nem tartalmazza a "#domain#" változót!'); }
					if(!preg_match('/#passkey#/', $passkey_link)) { logdie('Az azonosítókulcs linkje nem tartalmazza a "#passkey#" változót!'); }
				}

				$user_link = esc($_POST['user_link']);
				if($user_link != '') {
				//	if(!preg_match('/#domain#/', $user_link)) { logdie('A felhasználók adatlapjának linkje nem tartalmazza a "#domain#" változót!'); }
					if(!preg_match('/#user_id#/', $user_link)) { logdie('A felhasználók adatlapjának linkje nem tartalmazza a "#user_id#" változót!'); }
				}

				$dindata_link = esc($_POST['dindata_link']);
				if($dindata_link != '') {
				//	if(!preg_match('/#domain#/', $dindata_link)) { logdie('A dinamikus adatlekérdezõ linkje nem tartalmazza a "#domain#" változót!'); }
					if(!preg_match('/#user_id#/', $dindata_link)) { logdie('A dinamikus adatlekérdezõ linkje nem tartalmazza a "#user_id#" változót!'); }
				}

				$restrict_calc = '';
				if($USER['tam_class'] >= $site_full) { $restrict_calc = esc($_POST['restrict_calc']); }
				//if restrict_calc != '' { writeRestrict(); }

				$comm = esc($_POST['comm']);

				$admin_comm = '';
				if($USER['tam_class'] >= $site_mod) { $admin_comm = esc($_POST['admin_comm']); }

				//log oldal hozzáadva
				//if($USER['tam_class'] < $site_full) req (type: system) ellenõrzésre, hiányzó adatok pótlására

				$query = "INSERT INTO tam_sites VALUES (NULL, '$name', '$domains', '$passkey_link', '$user_link', '$dindata_link', '$restrict_calc', '$comm', '$admin_comm')";
				mysql_query($query);
				$inserted_id = mysql_insert_id();

				//log oldal hozzáadva, admin comm-ba info a hozzadó személyérõl
				//if($USER['tam_class'] < $site_full) req (type: system) ellenõrzésre, hiányzó adatok pótlására
				//else restrictions.php fájl megíró funkció meghívása

				redir('site.php?sid='.$inserted_id);
			}
			else {
				head('Oldal hozzáadás', 'managesite.php?action=add_site', $site_add);
				$USER = userData(MYSQL_ASSOC);
?>

				<table align="center">
					<form method="post">
						<tr><td>Oldal neve:</td><td><input type="text" name="name"></td></tr>
						<tr><td>Oldal domain címei:</td><td><input type="text" name="domains"></td></tr>
						<tr><td>Azonosítókulcs linkje:</td><td><input type="text" name="passkey_link" maxlenght="255"></td></tr>
						<tr><td>Felhasználók adatlapjának linkje:</td><td><input type="text" name="user_link" maxlenght="255"></td></tr>
						<tr><td>Dinamikus adatlekérdezõ linkje:</td><td><input type="text" name="dindata_link" maxlenght="255"></td></tr>
						<?php if($USER['tam_class'] >= $site_full) { ?>
							<script type="text/javascript" src="elements.js"></script>
							<tr><td>Megszorítás kalkuláló egyenlet:</td><td>
								<div id="textDiv" style="left:0px; top:0px; width:185px; height:100px; position:relative;">
									<textarea id="textBox" style="width:179px; height:93px; left:0px; top:0px; position:absolute;" name="restrict_calc"></textarea>
									<div id="handleRight" style="width:5px; height:95px; background-color:LightGray; position:absolute; left:180px; top:1px; cursor:e-resize;"></div>
									<div id="handleCorner" style="width:5px; height:5px; background-color:Gray; position:absolute; left:180px; top:95px; cursor:se-resize;"></div>
									<div id="handleBottom" style="width:180px; height:5px; background-color:LightGray; position:absolute; left:0px; top:95px; cursor:s-resize;"></div>
								</div>
							</td></tr>
							<script type="text/javascript">
								var textDiv = document.getElementById('textDiv');
								var textBox = document.getElementById('textBox');
								var handleRight = document.getElementById('handleRight');
								var handleCorner = document.getElementById('handleCorner');
								var handleBottom = document.getElementById('handleBottom');
								new dragObject(handleRight, null, new Position(15, 0), new Position(500, 0), null, RightMove, null, false);
								new dragObject(handleBottom, null, new Position(0, 15), new Position(0, 500), null, BottomMove, null, false);
								new dragObject(handleCorner, null, new Position(15, 15), new Position(500, 500), null, CornerMove, null, false);
							</script>
						<?php } ?>
						<tr><td>Komment:</td><td><textarea name="comm"></textarea></td></tr>
						<?php if($USER['tam_class'] >= $site_mod) { ?>
							<tr><td>Admin komment:</td><td><textarea name="admin_comm"></textarea></td></tr>
						<?php } ?>
						<input type="hidden" name="verif" value="add_site">
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