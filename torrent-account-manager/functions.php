<?php
error_reporting(E_ALL);// ^ E_NOTICE);
//wtf errorokat átírni hibakazelés segítéséhez

	require('config.php');

	$connection = /*@*/mysql_connect($tam_db_host, $tam_db_user, $tam_db_pass) or die('Nem sikerült kapcsolódni az adatbázishoz!');
	mysql_select_db($tam_db_name, $connection);

	function esc($str) {
		return mysql_real_escape_string($str);
	}

	function redir($redir) {
		$redirLoc = rawurlencode($redir);
		echo '<script type="text/javascript">document.location="'.$redirLoc.'";</script>';
		exit;
	}

	function logdie($title = 'Torrent Account Manager', $dieoptions = 'no') {
		//log funkciónak elküldeni az adatokat
		if($dieoptions == 'disabled' || $dieoptions == 'inactive') {//kibõvíteni külön mindkét opcióra
      echo '<title>'.$title.'</title>';
      echo '</head><body>';

      die($title);
		}
		else {
      $USER = userData(MYSQL_ASSOC);//mivan ha nincs bejelentkezve? nem lehetséges valahol?

      if(($dieoptions == 'headsilent' || $dieoptions == 'silent') && $USER['tam_class'] < 1) { $title = 'Ehhez nincs jogosultságod!'; }

      if($dieoptions == 'head' || $dieoptions == 'headsilent') {
        echo '<html><head>';
        if(checkLogin() == 'logged_out') {
          echo '<meta http-equiv="refresh" content="0; url=login.php">';
          echo '</head><body>';
          exit;
        }
        else {
          echo '<title>'.$title.'</title>';
          echo '</head><body>';
        }
        menu($title);
      }

      die($title);
    }
	}

	function head($title, $back = 'index.php', $enabled_class = 0) {
		$backLoc = rawurlencode($back);
		echo '<html><head>';

		if(checkLogin($enabled_class) == 'logged_out' && $backLoc != 'public') {
			echo '<meta http-equiv="refresh" content="0; url=login.php?back='.$backLoc.'">';
			echo '</head><body>';
			exit;
		}
		else {
			echo '<title>'.$title.'</title>';
			echo '</head><body>';
		}
		menu($title);
	}

	function menu($title) {
		echo '<table align="center"><tr><td>'.$title.'</td></tr></table>';

		if(checkLogin() == 'logged_in') {
			echo '<table align="center"><tr>';

			echo '<td align="center" width="100"><a href="index.php">index.php</td>';
			echo '<td align="center" width="100"><a href="acclist.php">acclist.php</td>';
			echo '<td align="center" width="100"><a href="manageacc.php">manageacc.php</td>';
			echo '<td align="center" width="100"><a href="sitelist.php">sitelist.php</td>';
			echo '<td align="center" width="100"><a href="managesite.php">managesite.php</td>';
			echo '<td align="center" width="100"><a href="userlist.php">userlist.php</td>';
			echo '<td align="center" width="100"><a href="manageuser.php">manageuser.php</td>';
			echo '<td align="center" width="100"><a href="login.php">login.php</td>';//debug
			echo '<td align="center" width="100"><a href="logout.php">logout.php</td>';
			echo '<td align="center" width="100"><a href="test.php">test.php</td>';//debug
			echo '<td align="center" width="100"><a href="captcha.php">captcha.php</td>';//debug

			echo '</tr></table>';
		}
	}

	function foot() {//($page)
		echo '</body></html>';
	}

	function checkLogin($enabled_class = 0) {
		if(isset($_COOKIE['uid']) && isset($_COOKIE['tam_pass'])) {
			$uid = esc($_COOKIE['uid']);
			$tam_pass = esc($_COOKIE['tam_pass']);
			$query = "SELECT verif, tam_class FROM tam_users WHERE uid = '$uid' AND tam_pass = '$tam_pass'";

			$queryresult = mysql_query($query);
			$resultnum = mysql_num_rows($queryresult);

			if($resultnum > 1) { logdie('ERROR001: WHAT THE FUCK?! o.O'); }
			if($resultnum < 1) {
				delCookie();//a sütik hibásak
				return 'logged_out';
			}//kilistázni vagy linkelni (log)

			$USERDATA = mysql_fetch_assoc($queryresult);

			checkVerif($USERDATA['verif']);
			//checkCookieLife($USERDATA['last_login']);//bugged1

			if($USERDATA['tam_class'] < $enabled_class) { logdie('Fejlécben megadott minimum rang alapján ehhez nincs jogosultságod!', 'headsilent'); }

			return 'logged_in';
		}
		else {
			return 'logged_out';
		}
	}

	function userData($fetch = MYSQL_BOTH, $redir = 'login.php') {//MYSQL_BOTH MYSQL_ASSOC MYSQL_NUM
		if(isset($_COOKIE['uid']) && isset($_COOKIE['tam_pass'])) {
			$uid = esc($_COOKIE['uid']);
			$tam_pass = esc($_COOKIE['tam_pass']);
			$query = "SELECT * FROM tam_users WHERE uid = '$uid' AND tam_pass = '$tam_pass'";

			$queryresult = mysql_query($query);
			$resultnum = mysql_num_rows($queryresult);

			if($resultnum > 1) { logdie('ERROR002: WHAT THE FUCK?! o.O'); }
			if($resultnum < 1) {
				delCookie();
				redir('login.php?back='.$redir);
				//die('A sütik hibásak!');//log
			}

			$returnData = mysql_fetch_array($queryresult, $fetch);

			checkVerif($returnData['verif']);
			//checkCookieLife($returnData['last_login']);//bugged1

			return $returnData;
		}
		else {
			delCookie();
			redir('login.php?back='.$redir);
		}
	}

/*	function checkCookieLife($last_login) {//bugged1
		$deadTime = strtotime($last_login) + 60 * 60 * 12;
		if(time() > $deadtime) {
			delCookie();
			redir('login.php');//?error=cookielife //&back érték elenlegi lap alapján
		}//$cookie_life változót bevezetni
	}*/

	function checkVerif($verif, $dieoptions = 'no') {
if($dieoptions != 'no') { die('ERROR051: WHAT THE FUCK?! o.O<br>'.$dieoptions); }//debug (hivatkozik-e valami a checkVerif $dieoptions átadására)
		if($verif == 'disabled') {
			delCookie();
			logdie('Ez a felhasználó ki van tiltva!', 'disabled');
		}
		if($verif != '') {
			delCookie();
			logdie('Ez a felhasználó nincs aktiválva!', 'inactive');
		}   
	}

	function getAccountData($aid, $fetch = MYSQL_BOTH, $column = '*', $dieoptions = 'no') {//MYSQL_BOTH MYSQL_ASSOC MYSQL_NUM
		$aid = esc($aid);
		if(!is_numeric($aid)) { logdie('Helytelen Account ID!', $dieoptions); }

		$query = "SELECT $column FROM tam_accounts WHERE aid = '$aid'";

		$queryresult = mysql_query($query);
		$resultnum = mysql_num_rows($queryresult);

		if($resultnum > 1) { logdie('ERROR004: WHAT THE FUCK?! o.O'); }
		if($resultnum < 1) { logdie('Helytelen Account ID!', $dieoptions); }

		$returnData = mysql_fetch_array($queryresult, $fetch);

		return $returnData;
	}

	function getUserData($uid, $fetch = MYSQL_BOTH, $column = '*', $dieoptions = 'no') {//MYSQL_BOTH MYSQL_ASSOC MYSQL_NUM
		$uid = esc($uid);
		if(!is_numeric($uid)) { logdie('Helytelen Felhasználó ID!', $dieoptions); }

		$query = "SELECT $column FROM tam_users WHERE uid = '$uid'";

		$queryresult = mysql_query($query);
		$resultnum = mysql_num_rows($queryresult);

		if($resultnum > 1) { logdie('ERROR005: WHAT THE FUCK?! o.O'); }
		if($resultnum < 1) { logdie('Helytelen Felhasználó ID!', $dieoptions); }

		$returnData = mysql_fetch_array($queryresult, $fetch);

		return $returnData;
	}

	function getSiteData($sid, $fetch = MYSQL_BOTH, $column = '*', $dieoptions = 'no') {//MYSQL_BOTH MYSQL_ASSOC MYSQL_NUM
		$sid = esc($sid);
		if(!is_numeric($sid)) { logdie('Helytelen Oldal ID!', $dieoptions); }

		$query = "SELECT $column FROM tam_sites WHERE sid = '$sid'";

		$queryresult = mysql_query($query);
		$resultnum = mysql_num_rows($queryresult);

		if($resultnum > 1) { logdie('ERROR006: WHAT THE FUCK?! o.O'); }
		if($resultnum < 1) { logdie('Helytelen Oldal ID!', $dieoptions); }

		$returnData = mysql_fetch_array($queryresult, $fetch);

		return $returnData;
	}

	function getRatio($ACCOUNTDATA, $SITEDATA) {
		include('config.php');//fix

/**/$din_accdata = 'no';//debug

		if($SITEDATA['dindata_link'] == '' || $din_accdata == 'no') {
			$upload = $ACCOUNTDATA['upload'];
			$download = $ACCOUNTDATA['download'];

			$returnData = ratioCalc($upload, $download);
		}
//		else {
//			$datalink = wildcard($SITEDATA['dindata_link'], $SITEDATA['domains'], $ACCOUNTDATA['user_id']);

//			if(($file = @fopen($datalink, 'r')) === FALSE){/*@*/
//				$upload = $ACCOUNTDATA['upload'];
//				$download = $ACCOUNTDATA['download'];
//				//die('Nem sikerült megnyitni a fájlt!');//log
//			}
//			else {
//				if(($fdata = /*@*/fread($file, 200)) === FALSE){//200 elég-e?
//					$upload = $ACCOUNTDATA['upload'];
//					$download = $ACCOUNTDATA['download'];
//					//die('Nem sikerült az adatokat kiolvasni a fájlból!');//log
//				}
//				else {
//					$data = explode(',', $fdata);
//					/*@*/fclose($file) or logdie('ERROR007: WHAT THE FUCK?! o.O');
//					if($data[0] != '') {
//						$updata = explode(' ', $data[1]);
//						$downdata = explode(' ', $data[2]);
//
//						$upload = sizeConverter($updata[0], $updata[1]);
//						$download = sizeConverter($downdata[0], $downdata[1]);
//					}
//					else {
//						$upload = $ACCOUNTDATA['upload'];
//						$download = $ACCOUNTDATA['download'];
//						//die('Helytelen Account felhasználó ID!');//log
//					}
//				}
//			}
//
//			$returnData = ratioCalc($upload, $download);
//		}

		return $returnData;
	}

	function wildcard($datalink0, $domains = '#domain#', $user_id = '#user_id#', $passkey = '#passkey#') {
		$domain = explode('|', $domains);
		$datalink1 = preg_replace('/#domain#/', $domain[0], $datalink0);
		$datalink2 = preg_replace('/#user_id#/', $user_id, $datalink1);
		$datalink3 = preg_replace('/#passkey#/', $passkey, $datalink2);

		return $datalink3;
	}

	function ratioCalc($upload, $download) {
		if(!is_numeric($upload)) { $upload = 0.001; }
		if(!is_numeric($download)) { $download = 0.001; }

		if($upload == 0) { $upload = 0.001; }
		if($download == 0) { $download = 0.001; }
	
		$ratio = $upload / $download;
		$datatraffic = $upload + $download;

    if($datatraffic < 1) { $value = $ratio; }
    else { $value = $datatraffic * $ratio; }

		$upload = number_format($upload, 2, '.', '');
		$download = number_format($download, 2, '.', '');
		$ratio = number_format($ratio, 3, '.', '');
		$datatraffic = number_format($datatraffic, 2, '.', '');
		$value = number_format($value, 0, '', '');

		$returnData = array('upload' => $upload, 'download' => $download, 'ratio' => $ratio, 'datatraffic' => $datatraffic, 'value' => $value);

		return $returnData;
	}

	function sizeConverter($number, $type) {
		if($number != 0) {
			switch ($type) {
				case 'kB':
					$returnData = $number / 1024 / 1024;
				break;
				case 'MB':
					$returnData = $number / 1024;
				break;
				case 'GB':
					$returnData = $number;
				break;
				case 'TB':
					$returnData = $number * 1024;
				break;
				case 'PB':
					$returnData = $number * 1024 * 1024;
				break;
				default:
				logdie('Nem támogatott adatmennyiség típus!');
			}
		}
		else { $returnData = 0; }

		return number_format($returnData, 2, '.', '');
	}

	function getAccStatus($status) {
		switch ($status) {
			case 0:
				$status = 'Inaktív';
			break;
			case 1:
				$status = 'Szabad account';
			break;
			case 2:
				$status = 'Szabad azonosítókulcs';
			break;
			case 3:
				$status = 'Használt';
			break;
			case 4:
				$status = 'Védett';
			break;
			default:
			logdie('Nem létezõ Account állapot típus!');
		}

		return $status;
	}

	function getUserClass($status) {
		switch ($status) {
			case 0:
				$status = 'Felhasználó';
			break;
			case 1:
				$status = 'Moderátor';
			break;
			case 2:
				$status = 'Adminisztrátor';
			break;
			case 3:
				$status = 'Fõadminisztrátor';//fix
			break;
			default:
			logdie('Nem létezõ Felhasználó rang!');
		}

		return $status;
	}

	function isMatchingOne($id, $idline) {
		if($id == '' || $idline == '') { return 'no'; }
		else {
			$idarray = explode('|', $idline);
			if(in_array($id, $idarray)) { return 'yes'; }
			else { return 'no'; }
		}
	}

	function isMatching($userid, $userAccIDs, $accid, $accUserIDs) {
		if($userid == '' || $userAccIDs == '' || $accid == '' || $accUserIDs == '') { return 'no'; }

		if(isMatchingOne($userid, $accUserIDs) == 'yes') {
			if(isMatchingOne($accid, $userAccIDs) == 'yes') {
				return 'yes';
			}
			else { logdie('ERROR008: WHAT THE FUCK?! o.O'); }
		}
		elseif(isMatchingOne($accid, $userAccIDs) == 'yes') {
			logdie('ERROR009: WHAT THE FUCK?! o.O');
		}
		else { return 'no'; }
	}

	function getAccountsOfUserOnSite($data, $sid, $fetch = MYSQL_BOTH, $orderby = 'user') {//MYSQL_BOTH MYSQL_ASSOC MYSQL_NUM
		if($data != '') {
			$sid = esc($sid);
			$accounts = explode('|', $data);
			$num = count($accounts);
			if($num < 1) { logdie('ERROR010: WHAT THE FUCK?! o.O'); }

			$account = $accounts[0];
			$query = "SELECT * FROM tam_accounts WHERE (aid = '$account'";

			if($num > 1) {
				for($i = 1; $num > $i; $i++) {
					$account = $accounts[$i];
					$query = $query." OR aid = '$account'";
				}
			}

			$query = $query.") AND site_id = '$sid' ORDER BY '$orderby' ASC";

			$queryresult = mysql_query($query);
			$resultnum = mysql_num_rows($queryresult);

			for($i = 0; $resultnum > $i; $i++) {
				$returnData[] = mysql_fetch_array($queryresult, $fetch);
			}
		}//else log('Nincs accountlista megadva!');
    else { $returnData = ''; }//debug account.php-bol meglehet hívni $data == '' állapotban, igy notice errort generál: nincs deklarálva $returnData
                              //be kell húzni itt is és a többi esetben is a fenti adatmeglétet ellenõrzõ if-be a return értéket a returndatához
		return $returnData;
	}

	function getSitesOfUsersAccounts($data, $fetch = MYSQL_BOTH, $orderby = 'name') {//MYSQL_BOTH MYSQL_ASSOC MYSQL_NUM
		if($data != '') {
			$accounts = getAccountsFromIDs($data, MYSQL_ASSOC, 'site_id');
			$numaccounts = count($accounts);
			if($numaccounts < 1) { logdie('ERROR011: WHAT THE FUCK?! o.O'); }

			for($i = 0; $numaccounts > $i; $i++) {
				$account = $accounts[$i];
				$sites[] = $account['site_id'];
			}

			$numsites = count($sites);
			if($numsites < 1) { logdie('ERROR012: WHAT THE FUCK?! o.O'); }

			$site = $sites[0];
			$query = "SELECT * FROM tam_sites WHERE sid = '$site'";

			if($numsites > 1) {
				for($j = 1; $numsites > $j; $j++) {
					$site = $sites[$j];
					$query = $query." OR sid = '$site'";
				}
			}

			$query = $query." ORDER BY '$orderby' ASC";

			$queryresult = mysql_query($query);
			$resultnum = mysql_num_rows($queryresult);

			if($resultnum != $numsites) { logdie('ERROR013: WHAT THE FUCK?! o.O'); }

			for($k = 0; $resultnum > $k; $k++) {
				$returnData[] = mysql_fetch_array($queryresult, $fetch);
			}
		}//else log('Nincs oldallista megadva!');

		return $returnData;
	}

//egy funkcióba sûríteni a lenti hármat
	function getSitesFromIDs($data, $fetch = MYSQL_BOTH, $column = '*', $orderby = 'name') {//MYSQL_BOTH MYSQL_ASSOC MYSQL_NUM
		if($data != '') {
			$sites = explode('|', $data);
			$num = count($sites);
			if($num < 1) { logdie('ERROR033: WHAT THE FUCK?! o.O'); }

			$site = $sites[0];
			$query = "SELECT $column FROM tam_sites WHERE sid = '$site'";

			if($num > 1) {
				for($i = 1; $num > $i; $i++) {
					$site = $sites[$i];
					$query = $query." OR sid = '$site'";
				}
			}

			$query = $query." ORDER BY '$orderby' ASC";

			$queryresult = mysql_query($query);
			$resultnum = mysql_num_rows($queryresult);

			if($resultnum != $num) { logdie('ERROR034: WHAT THE FUCK?! o.O'); }

			for($i = 0; $resultnum > $i; $i++) {
				$returnData[] = mysql_fetch_array($queryresult, $fetch);
			}
		}//else log('Nincs oldallista megadva!');

		return $returnData;
	}

	function getUsersFromIDs($data, $fetch = MYSQL_BOTH, $column = '*', $orderby = 'tam_user') {//MYSQL_BOTH MYSQL_ASSOC MYSQL_NUM
		if($data != '') {
			$users = explode('|', $data);
			$num = count($users);
			if($num < 1) { logdie('ERROR014: WHAT THE FUCK?! o.O'); }

			$user = $users[0];
			$query = "SELECT $column FROM tam_users WHERE uid = '$user'";

			if($num > 1) {
				for($i = 1; $num > $i; $i++) {
					$user = $users[$i];
					$query = $query." OR uid = '$user'";
				}
			}

			$query = $query." ORDER BY '$orderby' ASC";

			$queryresult = mysql_query($query);
			$resultnum = mysql_num_rows($queryresult);

			if($resultnum != $num) { logdie('ERROR015: WHAT THE FUCK?! o.O'); }

			for($i = 0; $resultnum > $i; $i++) {
				$returnData[] = mysql_fetch_array($queryresult, $fetch);
			}
		}//else log('Nincs felhasználólista megadva!');

		return $returnData;
	}

	function getAccountsFromIDs($data, $fetch = MYSQL_BOTH, $column = '*', $orderby = 'user') {//MYSQL_BOTH MYSQL_ASSOC MYSQL_NUM
		if($data != '') {
			$accounts = explode('|', $data);
			$num = count($accounts);
			if($num < 1) { logdie('ERROR016: WHAT THE FUCK?! o.O'); }

			$account = $accounts[0];
			$query = "SELECT $column FROM tam_accounts WHERE aid = '$account'";

			if($num > 1) {
				for($i = 1; $num > $i; $i++) {
					$account = $accounts[$i];
					$query = $query." OR aid = '$account'";
				}
			}

			$query = $query." ORDER BY '$orderby' ASC";

			$queryresult = mysql_query($query);
			$resultnum = mysql_num_rows($queryresult);

			if($resultnum != $num) { logdie('ERROR017: WHAT THE FUCK?! o.O'); }

			for($i = 0; $resultnum > $i; $i++) {
				$returnData[] = mysql_fetch_array($queryresult, $fetch);
			}
		}//else log('Nincs accountlista megadva!');

		return $returnData;
	}
//egy funkcióba sûríteni a fenti hármat

	function getAccountsOfSite($sid, $fetch = MYSQL_BOTH, $orderby = 'user') {//MYSQL_BOTH MYSQL_ASSOC MYSQL_NUM
		$sid = esc($sid);
		$query = "SELECT * FROM tam_accounts WHERE site_id = '$sid' ORDER BY '$orderby' ASC";

		$queryresult = mysql_query($query);
		$resultnum = mysql_num_rows($queryresult);

		$returnData = Array();
		for($i = 0; $resultnum > $i; $i++) {
			$returnData[] = mysql_fetch_array($queryresult, $fetch);
		}

		return $returnData;
	}

	function delAccIDFromUsers($ACCOUNTDATA, $typeID) {
		$aid = $ACCOUNTDATA['aid'];
		$typeAcc = 'tam_'.$typeID;
		$type = 'tam_'.$typeID.'s';
		$USERS = getUsersFromIDs($ACCOUNTDATA[$typeAcc], MYSQL_ASSOC, 'uid, '.$type);

		if($USERS != '') {
			$numUsers = count($USERS);
			for($i = 0; $numUsers > $i; $i++) {
				$USERDATA = $USERS[$i];
				$accounts = explode('|', $USERDATA[$type]);

				$key = array_search($aid, $accounts);
				if($key === FALSE) {
					logdie('ERROR023: WHAT THE FUCK?! o.O');
				}
				else {
					unset($accounts[$key]);
					natcasesort($accounts);

					$accountList = implode('|', $accounts);
					$uid = $USERDATA['uid'];

					$query = "UPDATE tam_users SET $type = '$accountList' WHERE uid = '$uid'";
					mysql_query($query);
				}
			}
		}
	}

	function delUserIDFromAccounts($USERDATA, $typeID) {
		$uid = $USERDATA['uid'];
		$typeUser = 'tam_'.$typeID.'s';
		$type = 'tam_'.$typeID;
		$ACCOUNTS = getAccountsFromIDs($USERDATA[$typeUser], MYSQL_ASSOC, 'aid, '.$type);

		if($ACCOUNTS != '') {
			$numAccounts = count($ACCOUNTS);
			for($i = 0; $numAccounts > $i; $i++) {
				$ACCOUNTDATA = $ACCOUNTS[$i];
				$users = explode('|', $ACCOUNTDATA[$type]);

				$key = array_search($uid, $users);
				if($key === FALSE) {
					logdie('ERROR026: WHAT THE FUCK?! o.O');
				}
				else {
					unset($users[$key]);
					natcasesort($users);

					$userList = implode('|', $users);
					$aid = $ACCOUNTDATA['aid'];

					$query = "UPDATE tam_accounts SET $type = '$userList' WHERE aid = '$aid'";
					mysql_query($query);
				}
			}
		}
	}

	function ident($pass, $reg_time) {
		$passB64hex = bin2hex(base64_encode($pass));
		$reg_timeB64hex = bin2hex(base64_encode($reg_time));
		$SaltedHash = bin2hex(sha1($reg_timeB64hex.$passB64hex.$reg_timeB64hex, TRUE));
		$ident = bin2hex(md5($SaltedHash, TRUE));
		return $ident;
	}

	function addCookie($uid, $pass_hash) {
		$time = time() + 60 * 60 * 12;//12 órán át érvényes a süti
		setcookie('uid', $uid, $time, '/', $_SERVER['HTTP_HOST']);
		setcookie('tam_pass', $pass_hash, $time, '/', $_SERVER['HTTP_HOST']);
	}//$cookie_life változót bevezetni

	function delCookie() {
		$time = time() - 60 * 60 * 24 * 1;
		setcookie('uid', '', $time, '/', $_SERVER['HTTP_HOST']);
		setcookie('tam_pass', '', $time, '/', $_SERVER['HTTP_HOST']);
	}

	function siteList($selected = '', $disabled = FALSE) {
		$query = "SELECT sid, name FROM tam_sites ORDER BY name ASC";
		$result = mysql_query($query);
		$nrows = mysql_num_rows($result);

    if($disabled == TRUE) { $disabled = ' disabled'; }
    else { $disabled = ''; }

		echo '<select name="site_id" size="1"'.$disabled.'>';

		for($i = 1; $nrows >= $i; $i++) {
			$row = mysql_fetch_assoc($result);
      if($row['sid'] == $selected) { $isSelected = ' selected'; }
      else { $isSelected = ''; }

			echo '<option'.$isSelected.' value="'.$row['sid'].'">';
			echo $row['name'];
			echo '</option>';
		}

		echo '</select>';
	}

	function bb($text) {
		$text = htmlentities($text);//, ENT_QUOTES);
		//bbkód átalakítások
		$text = nl2br($text);

		return $text;
	}
?>