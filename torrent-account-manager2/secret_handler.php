<?php
/* SECRET_HANDLER */

include('functions.php');

if(checkLogin(false) !== false) { redir('index.php', true); }

$USERDATA = checkSecret($_POST['uid'], $_POST['secret']);
//$secret_valid = 60 * 60 * 24 * 3;

if($USERDATA !== false) {
  $the_secret = explode('|', $USERDATA['secret']);

  if($the_secret[0] == 'passw') {
    if($_POST['newpass1'] != '' || $_POST['newpass2'] != '') {
      $password1 = $_POST['newpass1'];
      $password2 = $_POST['newpass2'];

      if($password1 != $password2) { redir('secret.php?uid='.$USERDATA['uid'].'&secret='.$the_secret[2].'&error=5', true); }
      else {
        if(strlen($password1) < 6 || strlen($password1) > 32) { redir('secret.php?uid='.$USERDATA['uid'].'&secret='.$the_secret[2].'&error=6&1', true); }
        if(preg_match('/'.strtolower($USERDATA['username']).'/', strtolower($password1)) || preg_match('/'.strtolower($password1).'/', strtolower($USERDATA['username']))) { redir('secret.php?uid='.$USERDATA['uid'].'&secret='.$the_secret[2].'&error=6&2', true); }
        //if(!preg_match('/[a-z���������]/', $password1)) { redir('secret.php?uid='.$USERDATA['uid'].'&secret='.$the_secret[2].'&error=6&3', true); }
        //if(!preg_match('/[A-Z���������]/', $password1)) { redir('secret.php?uid='.$USERDATA['uid'].'&secret='.$the_secret[2].'&error=6&4', true); }
        if(!preg_match('/[a-zA-Z������������������]/', $password1)) { redir('secret.php?uid='.$USERDATA['uid'].'&secret='.$the_secret[2].'&error=6&3&4', true); }
        if(!preg_match('/[0-9]/', $password1)) { redir('secret.php?uid='.$USERDATA['uid'].'&secret='.$the_secret[2].'&error=6&5', true); }
      }

      $password = ident($USERDATA['username'], $password1);
    }
    else { redir('secret.php?uid='.$USERDATA['uid'].'&secret='.$the_secret[2].'&error=6&0', true); }

    $query = "UPDATE `tam_users` SET `password` = '$password', `secret` = '' WHERE `uid` = '{$USERDATA['uid']}'";
    sql_query($query);

    redir('login.php', true);
  }
  /*elseif($the_secret[0] == 'email') {
    $email = esc($_POST['email'], 1);
    if(preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $email)) {
      if(checkDupeEmail($email)) {
        //redir('secret.php?uid='.$USERDATA['uid'].'&secret='.$the_secret[2].'&error=7', true);
      }
    }
    else { redir('secret.php?uid='.$USERDATA['uid'].'&secret='.$the_secret[2].'&error=8', true); }        

    $query = "UPDATE `tam_users` SET `email` = '$email', `secret` = '' WHERE `uid` = '{$USERDATA['uid']}'";
    sql_query($query);

    redir('login.php', true);
  }*/
}
elseif(isset($_POST['secret_type']) && isset($_POST['username']) && isset($_POST['email'])) {
  if($_POST['username'] == '' || $_POST['email'] == '') { redir('secret.php?error=1', true); }

  if($_POST['secret_type'] == 'passw') {

    $username = esc($_POST['username'], 1);
    $email = esc($_POST['email'], 1);

    $query0 = "SELECT `uid`, `email` FROM `tam_users` WHERE `username` = '$username' AND `email` = '$email' AND `verif` = ''";
    $result = sql_query($query0);
    $resultnum = mysql_num_rows($result);

    if($resultnum != 1) { redir('secret.php?error=3', true); }
    else {
      $USERDATA = mysql_fetch_array($result, MYSQL_ASSOC);

      $secret_type = 'passw';
      $secret_time = time();
      $secret_verif = rand_str();
      $secret = $secret_type.'|'.$secret_time.'|'.$secret_verif;

      $query1 = "UPDATE `tam_users` SET `secret` = '$secret' WHERE `uid` = '{$USERDATA['uid']}'";
      sql_query($query1);

      $mail_msg = 'Valaki a felhaszn�l�neveddel / e-mail c�meddel jelsz�cser�t kezdem�nyezett a Torrent Account Manager oldalon.'
      .'#nl##nl#'
      .'A k�r�st kezdem�nyez� IP c�me:'.esc($_SERVER['REMOTE_ADDR'], 1)
      .'#nl#'
      .'Ha nem te kezdem�nyezted hagyd figyelmen k�v�l ezt a levelet, egy�b esetben kattints az al�bbi linkre, vagy m�sold azt be a b�ng�sz�dbe:'
      .'#nl#'
      .'http://#domain#/secret.php?uid='.$USERDATA['uid'].'&secret='.$secret_verif
      .'#nl##nl##nl#'
      .'Ha nem m�k�dik a link, menj az oldalra �s v�laszd ki az Adatcsere men�pontot, majd add meg a k�vetkez� adatokat:'
      .'#nl#'
      .'uID: '.$USERDATA['uid']
      .'#nl#'
      .'Meger�s�t� k�d: '.$secret_verif
      .'#nl##nl##nl#'
//      .'A link / meger�s�t� k�d '._date($secret_time + $secret_valid).'-ig haszn�lhat� fel, ut�na �jat kell k�rned.'
//      .'#nl##nl#'
      .'http://#domain#/';

      send_mail(0, 'adatcsere', $USERDATA['email'], 'TAM Felhaszn�l�i adatcsere', $mail_msg);

      redir('secret.php?error=4', true);
    }
  }
  //elseif($_POST['secret_type'] == 'email') {}
  else { redir('secret.php?error=2', true); }
}
else { redir('secret.php?debug', true); }
?>