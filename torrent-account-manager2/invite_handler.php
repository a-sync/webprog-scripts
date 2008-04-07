<?php
/* INVITE_HANDLER */
global $tam_rights;

include('functions.php');

$USER = checkLogin();
//$verif_valid = 60 * 60 * 24 * 3;

if($USER['invites'] != 0) {
  if(rights($USER, 1) > 4) {
  $full_admin = (rights($USER, 1) > 5) ? true : false;
  $full_log = (rights($USER, 2) > 3) ? true : false;

    if(($_POST['invite_email'] == '' || $_POST['verif'] == '') && ($_POST['username'] == '' || $_POST['password'] == '')) { redir('invite.php?error=8&1', true); }

    if($_POST['invite_email'] != '') {
      $invite_email = esc($_POST['invite_email'], 1);
      if(preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $invite_email)) {
        if(checkDupeEmail($invite_email)) {
          //redir('invite.php?error=2&1', true);
        }
      }
      else { redir('invite.php?error=1', true); }
    }
    else { $invite_email = ''; }

    if($_POST['username'] != '') {
      $username = esc($_POST['username'], 1);
      if(!preg_match('/^[A-Za-z0-9]{3,32}$/', $username)) { redir('invite.php?error=3', true); }
      if(checkDupeUsername($username)) {
        redir('invite.php?error=4', true);
      }
    }
    else { $username = ''; }

    if($_POST['password'] != '') {
      $rpass = $_POST['password'];

      if(strlen($rpass) < 6 || strlen($rpass) > 32) { redir('invite.php?error=5&1', true); }
      if($username != '') { if(preg_match('/'.strtolower($username).'/', strtolower($rpass)) || preg_match('/'.strtolower($rpass).'/', strtolower($username))) { redir('invite.php?error=5&2', true); } }
      //if(!preg_match('/[a-z���������]/', $password)) { redir('invite.php?error=5&3', true); }
      //if(!preg_match('/[A-Z���������]/', $password)) { redir('invite.php?error=5&4', true); }
      if(!preg_match('/[a-zA-Z������������������]/', $rpass)) { redir('invite.php?error=5&3&4', true); }
      if(!preg_match('/[0-9]/', $rpass)) { redir('invite.php?error=5&5', true); }

      if($username != '') { $password = ident($username, $rpass); }
      else { $password = bin2hex($rpass); }
    }
    else { $password = ''; }

    if($_POST['email'] != '') {
      $email = esc($_POST['email'], 1);
      if(preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $email)) {
        if(checkDupeEmail($email)) {
          //redir('invite.php?error=2&1', true);
        }
      }
      else { redir('invite.php?error=6', true); }
    }
    else { $email = ''; }

    if($_POST['notif0'] == 1) { $notif = '1|'; }
    else { $notif = '0|'; }
    if($_POST['notif1'] == 1) { $notif = $notif.'1|'; }
    else { $notif = $notif.'0|'; }
    if($_POST['notif2'] == 1) { $notif = $notif.'1'; }
    else { $notif = $notif.'0'; }

    if($_POST['invites'] != '' && is_numeric($_POST['invites'])) {
      $invites = esc($_POST['invites'], 1);
    }
    else { $invites = '0'; }

    if($full_admin) {
      if($_POST['status'] == 1) { $status = '1'; }
      else { $status = '0'; }

      if(is_numeric($_POST['user_class']) && rights($USER, 0) >= $_POST['user_class']) {
        $user_class = esc($_POST['user_class'], 1);
      }
      else { $user_class = rights($tam_rights, 0, false); }

      if(is_numeric($_POST['user_rights']) && rights($USER, 1) >= $_POST['user_rights']) {
        $user_rights = esc($_POST['user_rights'], 1);
      }
      else { $user_rights = rights($tam_rights, 1, false); }

      if($full_log) {
        if(is_numeric($_POST['log_rights']) && rights($USER, 2) >= $_POST['log_rights']) {
          $log_rights = esc($_POST['log_rights'], 1);
        }
        else { $log_rights = rights($tam_rights, 2, false); }
      }
      else { $log_rights = rights($tam_rights, 2, false); }
    }
    else {
      $status = 1;
      $user_class = rights($tam_rights, 0, false);
      $user_rights = rights($tam_rights, 1, false);
      $log_rights = rights($tam_rights, 2, false);
    }

    if($_POST['verif'] != '') {
      $verif = esc($_POST['verif'], 1);
      if(!preg_match('/^[0-9A-Za-z_]{8,32}$/', $verif)) { redir('invite.php?error=7', true); }
    }
    else { $verif = ''; }

    $rights = $user_class.'|'.$user_rights.'|'.$log_rights.'|';
    //$own_acc = esc($_POST['own_acc'], 1);
    $own_acc = '';
    $inviter = $USER['uid'];
    $reg_time = time();
    $admin_comm = esc($_POST['admin_comm'], 1);
    if($invite_email != '') { $admin_comm = _date().' Megh�vott e-mail: '.$invite_email."\r\n".$admin_comm; }
    else { $admin_comm = _date().' L�trehoz�s.'."\r\n".$admin_comm; }

    if(($invite_email == '' || $verif == '') && ($username == '' || $password == '')) { redir('invite.php?error=8&2', true); }

    //p� a meger�s�t� kulcsal / egy�b adattal
    $own_invites = $USER['invites'] - 1;
    $query0 = "UPDATE `tam_users` SET `invites` = '$own_invites' WHERE `uid` = '{$USER['uid']}'";
    sql_query($query0);

    $query1 = "INSERT INTO `tam_users` (`uid`, `username`, `password`, `email`, `notif`, `status`, `verif`, `rights`, `invites`, `own_acc`, `inviter`, `reg_time`, `admin_comm`) VALUES (NULL, '$username', '$password', '$email', '$notif', '$status', '$verif', '$rights', '$invites', '$own_acc', '$inviter', '$reg_time', '$admin_comm')";
    sql_query($query1);
    $invite_uid = mysql_insert_id();

    if($invite_email != '') {
      $mail_msg = 'Megh�v�t kapt�l a Torrent Account Manager oldalra.';
      if($verif != '') {
        $mail_msg .= '#nl##nl#'
        .'A regisztr�ci�hoz kattints az al�bbi linkre, vagy m�sold azt be a b�ng�sz�dbe:'
        .'#nl#'
        .'http://#domain#/register.php?uid='.$invite_uid.'&verif='.$verif
        .'#nl##nl##nl#'
        .'Ha nem m�k�dik a link, menj az oldalra �s v�laszd ki a Regisztr�ci� men�pontot, majd add meg a k�vetkez� adatokat:'
        .'#nl#'
        .'uID: '.$invite_uid
        .'#nl#'
        .'Meger�s�t� k�d: '.$verif;
      }
      if($username != '') {
        $mail_msg .= '#nl##nl#'
        .'Felhaszn�l�neved: '.$username;
      }
      if($password != '') {
        $mail_msg .= '#nl##nl#'
        .'Jelszavad: '.$rpass;
      }
//      $mail_msg .= '#nl##nl#A link / meger�s�t� k�d '._date($reg_time + $verif_valid).'-ig haszn�lhat� fel.';
      $mail_msg .= '#nl##nl##nl#'
      .'Ha tov�bbra sem m�k�dik, pr�b�lj kapcsolatba l�pni a megh�v� felt�telezett k�ld�j�vel...'
      .'#nl##nl#'
      .'http://#domain#/';

      send_mail(0, 'meghivas', $invite_email, 'TAM Megh�v�', $mail_msg);
    }

    redir('invite.php', true);
  }
  else {
    $invite_email = esc($_POST['invite_email'], 1);
    if(preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $invite_email)) {
      if(checkDupeEmail($invite_email)) {
        //redir('invite.php?error=2', true);
      }
    }
    else { redir('invite.php?error=1', true); }

    $notif = '0|0|0';
    $verif = rand_str();
    $rights = $tam_rights;
    $inviter = $USER['uid'];
    $reg_time = time();
    $admin_comm = _date().' Megh�vott e-mail: '.$invite_email."\r\n";

    //p� a meger�s�t� kulcsal
    $own_rights = set_rights($USER, 2, rights($USER, 2) - 1);
    $query0 = "UPDATE `tam_users` SET `rights` = '$own_rights' WHERE `uid` = '{$USER['uid']}'";
    sql_query($query0);

    $query1 = "INSERT INTO `tam_users` (`uid`, `notif`, `verif`, `rights`, `inviter`, `reg_time`, `admin_comm`) VALUES (NULL, '$notif', '$verif', '$rights', '$inviter', '$reg_time', '$admin_comm')";
    sql_query($query1);
    $invite_uid = mysql_insert_id();

    $mail_msg = 'Megh�v�t kapt�l a Torrent Account Manager oldalra.'
    .'#nl##nl#'
    .'A regisztr�ci�hoz kattints az al�bbi linkre, vagy m�sold azt be a b�ng�sz�dbe:'
    .'#nl#'
    .'http://#domain#/register.php?uid='.$invite_uid.'&verif='.$verif
    .'#nl##nl##nl#'
    .'Ha nem m�k�dik a link, menj az oldalra �s v�laszd ki a Regisztr�ci� men�pontot, majd add meg a k�vetkez� adatokat:'
    .'#nl#'
    .'uID: '.$invite_uid
    .'#nl#'
    .'Meger�s�t� k�d: '.$verif
//  .'#nl##nl#'
//  .'A link / meger�s�t� k�d '._date($reg_time + $verif_valid).'-ig haszn�lhat� fel.'
    .'#nl##nl##nl#'
    .'Ha tov�bbra sem m�k�dik, pr�b�lj kapcsolatba l�pni a megh�v� felt�telezett k�ld�j�vel...'
    .'#nl##nl#'
    .'http://#domain#/';

    send_mail(0, 'meghivas', $invite_email, 'TAM Megh�v�', $mail_msg);

    //redir p�-kh�z?
    redir('invite.php', true);
  }
}
else { redir('invite.php', true); }
?>