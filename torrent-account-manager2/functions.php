<?php
/* FUNCTIONS */

//error_reporting(E_ALL);//debug: E_ALL
error_reporting(E_ALL ^ E_NOTICE);//normál
//error_reporting(0);//nemkellenek hibaüzenetek: 0

require('config.php');

$tam_connection = /*@*/mysql_connect($tam_dbhost, $tam_dbuser, $tam_dbpass) or die('Nem sikerült kapcsolódni az adatbázisszerverhez!');
/*@*/mysql_select_db($tam_dbname, $tam_connection) or die('Nem sikerült kapcsolódni az adatbázishoz!');

/* - - - - - Document Functions - - - - - */
function head() {
  header("Content-Type: text/html; charset=iso-8859-2");
  $USER = checkLogin(false);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/2002/REC-xhtml1-20020801/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>TAM</title>
    <link rel="stylesheet" href="default.css" type="text/css" />
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-2" />
  </head>
  <body>

    <div id="container">

      <?php if($USER !== false) { echo '<div id="head"></div>'; } ?>
      <div id="navigate"><?php navigate($USER); ?></div>

      <div id="content">

<?php
}

function navigate($USER) {
  $sep = ' :: ';
  if($USER === false) {
    echo '<a href="login.php">Bejelentkezés</a>'.$sep;
    echo '<a href="secret.php">Adatcsere</a>'.$sep;
    echo '<a href="register.php">Regisztráció</a>';
  }
  else {
    echo '<a href="index.php">Főoldal</a>'.$sep;
    if(rights($USER, 1) > 0) { echo '<a href="settings.php">Beállítások</a>'.$sep; }
    if($USER['invites'] != 0 || rights($USER, 1) > 2) { echo '<a href="invite.php">Meghívás</a>'.$sep; }
    if(rights($USER, 1) > 2) { echo '<a href="users.php">Felhasználók</a>'.$sep; }
    if(rights($USER, 2) > 0) { echo '<a href="logs.php">Logok</a>'.$sep; }
    echo '<a href="logout.php">Kijelentkezés</a>';
  }
}

function foot() {
?>

      </div>

      <div id="foot">&copy; 2008</div>
    </div>

  </body>
</html>

<?php
}

function box($p, $text = false) {
  if($p == 0) {
    if($text !== false) { echo '<div class="boxhead">'.$text.'</div>'; }
    echo '<div class="box">';
  }
  elseif($p == 1) {
    echo '</div>';
  }
}

function redir($redir, $delay = 0, $exit = true) {
  $redirLoc = $redir;//szűrés?
  if(is_numeric($delay)) {
    echo '<script type="text/javascript">setTimeout("window.location=\''.$redirLoc.'\'", '.$delay.');</script>';
    if($exit) { exit; }
  }
  else {
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    header("Location: http://$host$uri/$redirLoc");
    exit;
  }
}

/* - - - - - Log Handling Functions - - - - - */
/**
 * Logoló funkció.
 *
 * @param integer $type (0=SQL, 1=User, 2=Mail)
 * @param integer $class (0-4)
 * @param string or array $message
 * @param integer $status (0=Rendben, 1=Ellenőrizetlen, 2=Kiemelt)
 * @param bool $die
 */
function logger($type, $class = 0, $message = '', $status = 1, $die = false) {

  $t = esc($type, 1);
  $c = esc($class, 1);
  $m = (is_array($message)) ? esc(var_dump($message), 1) : esc($message, 1);
  $s = esc($status, 1);

  $USERDATA = checkLogin(false);
  $lu = ($USERDATA === false) ? 0 : $USERDATA['uid'];
  $lt = time();
  $li = esc($_SERVER['REMOTE_ADDR'], 1);

  $query0 = "INSERT INTO `tam_logs` (`lid`, `type`, `class`, `message`, `status`, `log_uid`, `log_time`, `log_ip`) VALUES (NULL, '$t', '$c', '$m', '$s', '$lu', '$lt', '$li')";
  sql_query($query0);

  if($die !== false) { die($t.'::'.$c.' - '.$m); }
}

/* - - - - - E-Mail Handling Functions - - - - - */
function send_mail($function, $sender, $address, $subject, $message_text, $html = false, $message_html = false) {
  $name = 'TAM';
  $domain = 'akashi.hu';

  $message = str_replace('#nl#', "\r\n", esc($message_text, 1));
  $message_text = str_replace('#domain#', $domain, esc($message_text, 1));
  $message_html = str_replace('#domain#', $domain, $message_html);

  if($function == 0) {
    if($html) {
      $boundary_hash = rand_str(8);

      $headers ='From: '.$name.' <'.$sender.'@'.$domain.'>'."\r\n"
      .'Reply-To: noreplay@'.$domain."\r\n"
      .'X-Mailer: PHP/'.phpversion()."\r\n"
      .'Content-Type: multipart/alternative; boundary="PHP-alt-'.$boundary_hash.'"';

      $message_text = str_replace('#nl#', "\r\n", esc($message_text, 1));
      if($message_html == '') { $message_html = nl2br($message_text); }
      if($message_text == '') { $message_text = strip_tags($message_html); }

      $message = '--PHP-alt-'.$boundary_hash."\r\n"
      .'Content-Type: text/plain; charset="iso-8859-2"'."\r\n"
      .'Content-Transfer-Encoding: 7bit'."\r\n"
      ."\r\n"
      .$message_text."\r\n"
      ."\r\n"
      .'--PHP-alt-'.$boundary_hash."\r\n"
      .'Content-Type: text/html; charset="iso-8859-2"'."\r\n"
      .'Content-Transfer-Encoding: 7bit'."\r\n"
      ."\r\n"
      .$message_html."\r\n"
      ."\r\n"
      .'--PHP-alt-'.$boundary_hash.'--';
    }
    else {
      $headers ='From: '.$name.' <'.$sender.'@'.$domain.'>'."\r\n"
      .'Reply-To: noreplay@'.$domain."\r\n"
      .'X-Mailer: PHP/'.phpversion();
    }

    if(!@mail($address, $subject, $message, $headers)) { logger(2, 3, 'Az üzenet elküldése sikertelen volt. - '.$address.' - '.$subject.' - '.$message); }
  }
}

/* - - - - - Data Handling Functions - - - - - */
function _date($u = false, $t = 'Y.m.d - H:i:s') {
  if($u === false) { return date($t); }
  else { return date($t, $u); }
}

function hex2bin($hex) {
  return pack('H*', $hex);
}

function ident($name, $rpass) {
  $nameB64hex = bin2hex(base64_encode(strtolower($name)));
  $rpassB64hex = bin2hex(base64_encode($rpass));
  $SaltedHash = sha1($nameB64hex.$rpassB64hex.$nameB64hex);
  $ident = md5($SaltedHash);
  return $ident;
}

function rand_str($l = 32, $t = 2, $p = '') {
  $c[0] = '0123456789';
  $c[1] = 'abcdefghijklmnopqrstuvwxyz';
  $c[2] = '0123456789abcdefghijklmnopqrstuvwxyz';
  $c[3] = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $c[4] = '0123456789abcdef';//hexadecimal hash style

  $s = (is_numeric($t)) ? $c[$t].$p : $t.$p;

  $rs = '';
  for($i = 0; $i < $l; $i++) {
    $rs .= $s{mt_rand(0, strlen($s) - 1)};
  }

  return $rs;
}

function esc($str, $type = 0, $fix = true) {
  if(isset($str)) {
    if(get_magic_quotes_gpc()) {
      $str = stripslashes($str);
    }
    if($type == 1) {
      if($fix === true) {
        //Ű,ű, Ő,ő fix
        $str = str_replace('Ő', '&#336;', $str);
        $str = str_replace('ő', '&#337;', $str);
        $str = str_replace('Ű', '&#368;', $str);
        $str = str_replace('ű', '&#369;', $str);
        //Ű,ű, Ő,ő fix
      }

      $str = mysql_real_escape_string($str);
    }
    if($type == 2) {
      if($fix !== true) { $str = str_replace('&', '&amp;', $str); }
      $str = str_replace('$', '&#36;', $str);
      $str = str_replace('"', '&quot;', $str);
      $str = str_replace("'", "&#039;", $str);
      $str = str_replace('<', '&lt;', $str);
      $str = str_replace('>', '&gt;', $str);
    }
  }
  return $str;
}

function cutString($str, $max = 32, $end = '...', $cut = false, $start = 0) {
  $max = (is_numeric($max)) ? $max : 32;
  $cut = ($cut !== false && is_numeric($cut)) ? $cut : $max;
  if(0 < $cut) { $cut = $cut-1; }
  elseif(0 > $cut) { $cut = $cut; }
  if($cut == 0) { return ''; }

  if(strlen($str) > $max) { return substr($str, $start, $cut).$end; }
  else { return $str; }
}

function wc($s) {
  $s = str_replace(' ', '%', $s);
  $s = str_replace('*', '%', $s);
  $s = str_replace('?', '_', $s);

  return $s;
}

function sql_query($query) {
  return mysql_query($query);
}

/* - - - - - User Functions - - - - - */
function checkLogin($exit = true) {
  if(isset($_COOKIE['tam_uid']) && isset($_COOKIE['tam_password'])) {
    $uid = esc($_COOKIE['tam_uid'], 1);
    $password = esc($_COOKIE['tam_password'], 1);

    $query = "SELECT * FROM `tam_users` WHERE `uid` = '$uid' AND `password` = '$password'";
    $result = sql_query($query);
    $resultnum = mysql_num_rows($result);

    if($resultnum < 1 || $uid == '' || $password == '') {
      if($exit) {
        delCookie();
        redir('login.php?error=3', true);
      }
      return false;
    }

    $USER = mysql_fetch_array($result, MYSQL_ASSOC);

    if($USER['status'] == '0') {
      if($exit) {
        delCookie();
        redir('login.php?error=2', true);
      }
      return false;
    }
    elseif($USER['verif'] != '') {
      if($exit) {
        delCookie();
        redir('login.php?error=4', true);
      }
      return false;
    }
    else {
      return $USER;
    }
  }
  else {
    if($exit) { redir('login.php', true); }
    return false;
  }
}

function getUserData($uid, $col = false) {
  $uid = esc($uid, 1);
  if(!is_numeric($uid)) { return false; }

  $query = "SELECT * FROM `tam_users` WHERE `uid` = '$uid'";
  $result = sql_query($query);
  $resultnum = mysql_num_rows($result);

  if($resultnum == 1) { return mysql_fetch_array($result, MYSQL_ASSOC); }
  else { return false; }
}

function checkVerif($uid, $verif, $clear = true) {
  if(is_numeric($uid) && preg_match('/^[0-9A-Za-z_]{8,32}$/', $verif)) {
//    $verif_valid = 60 * 60 * 24 * 3;//mennyi ideig érvényes a meghívott érintetlen felhasználó

    $query0 = "SELECT * FROM `tam_users` WHERE `uid` = '$uid' AND `verif` = '$verif'";
    $result = sql_query($query0);
    $resultnum = mysql_num_rows($result);

    $USERDATA = mysql_fetch_array($result, MYSQL_ASSOC);

    if($resultnum < 1) {
      return false;
    }
//    //ha az érvényesség lejárt és érintetlen a felhasználó
//    elseif((($USERDATA['reg_time'] + $verif_valid) < time()) && $USERDATA['last_login'] == 0) {
//      $query1 = "DELETE FROM `tam_users` WHERE `uid` = '{$USERDATA['uid']}'";
//      if($clear) { sql_query($query1); }
//
//      return false;
//    }
    else {
      return $USERDATA;
    }
  }
  else { return false; }
}

function checkSecret($uid, $secret, $clear = true) {
  if(is_numeric($uid) && preg_match('/^[0-9A-Za-z_]{8,32}$/', $secret)) {
//    $secret_valid = 60 * 60 * 24 * 3;//mennyi ideig érvényes a megerősítő kód

    $query0 = "SELECT * FROM `tam_users` WHERE `uid` = '$uid' AND `secret` != '' AND `verif` = ''";
    $result = sql_query($query0);
    $resultnum = mysql_num_rows($result);

    $USERDATA = mysql_fetch_array($result, MYSQL_ASSOC);

    if($resultnum < 1) {
      return false;
    }
    else {
      $query1 = "UPDATE `tam_users` SET `secret` = '' WHERE `uid` = '{$USERDATA['uid']}'";

      $the_secret = explode('|', $USERDATA['secret']);
//      if($the_secret[1] + $secret_valid < time()) {//ha az érvényesség lejárt
//        if($clear) { sql_query($query1); }
//        return false;
//      }
      if($the_secret[0] == 'passw') {
        if($the_secret[2] != $secret) {
          //if($clear) { sql_query($query1); }
          return false;
        }
      }
      //elseif($the_secret[0] == 'email') {}
      else {
        if($clear) { sql_query($query1); }
        return false;
      }

      return $USERDATA;
    }
  }
  else { return false; }
}

function getInvited($uid, $blank = false) {
  if(is_numeric($uid)) {
    $query = "SELECT * FROM `tam_users` WHERE `inviter` = '$uid' ORDER BY `reg_time` DESC";
    $result = sql_query($query);
    $resultnum = mysql_num_rows($result);

    if($resultnum < 1) {
      return false;
    }
    else {
      for($i = 0; $i < $resultnum; $i++) {
        $INVITED[] = mysql_fetch_array($result, MYSQL_ASSOC);
      }

      return $INVITED;
    }
  }
  else { return false; }
}

function last_login($uid) {
  $uid = esc($uid, 1);
  $time = time();

  $query = "UPDATE `tam_users` SET `last_login` = '$time' WHERE `uid` = '$uid'";
  sql_query($query);
}

function last_ips($USERDATA) {
  $stored = 10;//tárolt IP-k száma

  $uid = esc($USERDATA['uid'], 1);
  $last_ips = esc($USERDATA['last_ips'], 1);

  $ips = explode('|', $last_ips);
  if(count($ips) >= $stored) { $del = array_pop($ips); }
  $num = array_unshift($ips, esc($_SERVER['REMOTE_ADDR'], 1));
  //if($num > $stored) { logdie('sql:0003'); }
  $last_ips = implode('|', $ips);
  if(substr($last_ips, -1) == '|') { $last_ips = substr($last_ips, 0, -1); }

  $query = "UPDATE `tam_users` SET `last_ips` = '$last_ips' WHERE `uid` = '$uid'";
  sql_query($query);
}

function addCookie($uid, $password) {
  $time = time() + 60 * 60 * 24 * 1;//1 napon át érvényes a süti

  setcookie('tam_uid', $uid, $time, '/', $_SERVER['SERVER_NAME']);
  setcookie('tam_password', $password, $time, '/', $_SERVER['SERVER_NAME']);
}

function delCookie() {
  $time = time() - 60 * 60 * 24 * 1;

  setcookie('tam_uid', '', $time, '/', $_SERVER['SERVER_NAME']);
  setcookie('tam_password', '', $time, '/', $_SERVER['SERVER_NAME']);
}

function checkDupeEmail($email, $log = true, $num = 0) {
  $email = esc($email, 1);

  $query = "SELECT `uid` FROM `tam_users` WHERE `email` = '$email'";
  $result = sql_query($query);
  $resultnum = mysql_num_rows($result);

  if($resultnum > $num) {
    return true;
    if($log) { logger(1, 2, 'Többszörös e-mail cím használat. ('.$email.')'); }
  }

  return false;
}

function checkDupeUsername($username, $log = true) {
  $username = esc($username, 1);

  $query = "SELECT `uid` FROM `tam_users` WHERE `username` = '$username'";
  $result = sql_query($query);
  $resultnum = mysql_num_rows($result);

  if($resultnum > 0) {
    if($log) { logger(1, 1, 'Többszörös felhasználó név használat. ('.$username.')'); }
    return true;
  }

  return false;
}

function set_rights($USERDATA, $n, $v = 0, $col = 'rights', $a = false) {
  if($col === false) { $rights = explode('|', $USERDATA); }
  else { $rights = explode('|', $USERDATA[$col]); }
  $rights[$n] = $v;
  $rights = implode('|', $rights);
  //if(substr($rights, -1) == '|') { $rights = substr($rights, 0, -1); }//debug
  if($a === true && $col !== false) {
    $USERDATA[$col] = $rights;
    return $USERDATA;
  }
  return $rights;
}

function rights($USERDATA, $n = false, $col = 'rights') {
  if($col === false) { $rights = explode('|', $USERDATA); }
  else { $rights = explode('|', $USERDATA[$col]); }
  if(is_numeric($n)) { return $rights[$n]; }
  else { return $rights; }
}
?>