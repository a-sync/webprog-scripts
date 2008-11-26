<?php
$db['dbhost'] = 'localhost';// sql szerver
$db['dbuser'] = 'presidance';// adatbázis felhasználó
$db['dbpass'] = '8Ibf@8vF';// felhasználó jelszó
$db['dbname'] = 'presidance';//adatbázis neve
$_msgnum = 100;//hány üzenet jelenjen meg
/*
$db['dbhost'] = 'localhost';// sql szerver
$db['dbuser'] = 'root';// adatbázis felhasználó
$db['dbpass'] = '';// felhasználó jelszó
$db['dbname'] = 'presidance';//adatbázis neve
*/

//funkciók alap változók
error_reporting(E_ALL ^ E_NOTICE);//normál
//error_reporting(0);//nemkellenek hibaüzenetek: 0
//set_time_limit(0);
$_connection = @mysql_connect($db['dbhost'], $db['dbuser'], $db['dbpass']) or die('Nem sikerült csatlakozni a szerverhez! (Ellenőrizd a config.php fájlt.)');
//@mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $_connection);
@mysql_query("SET character_set_results = 'utf8', character_set_server = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8'", $_connection);


@mysql_select_db($db['dbname'], $_connection) or die('Nem sikerült csatlakozni az adatbázishoz! (Ellenőrizd a config.php fájlt.)');


function addCookie($usid, $passhash) {
  $time = time() + 60 * 60 * 24 * 30;//30 napon át érvényes a süti

  setcookie('usid', $usid, $time, '/', $_SERVER['SERVER_NAME']);
  setcookie('passhash', $passhash, $time, '/', $_SERVER['SERVER_NAME']);
}

function delCookie() {
  $time = time() - 60 * 60 * 24 * 1;

  setcookie('usid', '', $time, '/', $_SERVER['SERVER_NAME']);
  setcookie('passhash', '', $time, '/', $_SERVER['SERVER_NAME']);
}

function ident($name, $rpass, $salt = 'titkos titok') {
  $nameB64hex = bin2hex(base64_encode(strtolower($name).$salt));
  $rpassB64hex = bin2hex(base64_encode($rpass.$salt));

  return md5($nameB64hex.$rpassB64hex.$nameB64hex);
}

function checkLogin() {
  if(isset($_COOKIE['usid']) && isset($_COOKIE['passhash'])) {
    $usid = mysql_real_escape_string($_COOKIE['usid']);
    $passhash = mysql_real_escape_string($_COOKIE['passhash']);

    $query = "SELECT * FROM `users` WHERE `usid` = '$usid' AND `passhash` = '$passhash' AND `confirm` = ''";
    $result = mysql_query($query);
    $resultnum = mysql_num_rows($result);

    if($resultnum < 1 || $usid == '' || $passhash == '') {
      delCookie();
      return false;
    }
    else {
      $_USER = mysql_fetch_array($result, MYSQL_ASSOC);
      return $_USER;
    }
  }
  else {
    return false;
  }
}


foreach($_POST as $key => $val) {
  $_P[mysql_real_escape_string($key, $_connection)] = mysql_real_escape_string($val, $_connection);
}
foreach($_GET as $key => $val) {
  $_G[mysql_real_escape_string($key, $_connection)] = mysql_real_escape_string($val, $_connection);
}

$error = '';
$head = '';

?>