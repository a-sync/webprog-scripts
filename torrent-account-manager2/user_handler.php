<?php
/* USER_HANDLER */

include('functions.php');

$USER = checkLogin();

if(rights($USER, 1) > 4 && $_POST['uid'] != '') {
  $USERDATA = getUserData($_POST['uid']);
  $full_admin = (rights($USER, 1) > 5) ? true : false;
  $full_log = (rights($USER, 2) > 3) ? true : false;

  if($USERDATA === false || rights($USER, 0) < rights($USERDATA, 0)) {
    if(is_numeric($_POST['uid'])) { redir('user.php?uid='.esc($_POST['uid'], 1), true); }
    else { redir('index.php', true); }
  }
  else {
    $uid = $USERDATA['uid'];

    if($_POST['username'] != '' && $USERDATA['username'] == '') {
      $username = esc($_POST['username'], 1);
      if(!preg_match('/^[A-Za-z0-9]{3,32}$/', $username)) { redir('user.php?uid='.$uid.'&error=1', true); }
      if(checkDupeUsername($username)) {
        redir('user.php?uid='.$uid.'&error=2', true);
      }
    }
    else { $username = $USERDATA['username']; }

    if($_POST['password'] != '') {
      $rpass = $_POST['password'];

      if(strlen($rpass) < 6 || strlen($rpass) > 32) { redir('user.php?uid='.$uid.'&error=3&1', true); }
      if($username != '') { if(preg_match('/'.strtolower($username).'/', strtolower($rpass)) || preg_match('/'.strtolower($rpass).'/', strtolower($username))) { redir('user.php?uid='.$uid.'&error=3&2', true); } }
      //if(!preg_match('/[a-záéíóöõúüû]/', $password)) { redir('user.php?uid='.$uid.'&error=3&3', true); }
      //if(!preg_match('/[A-ZÁÉÍÓÖÕÚÜÛ]/', $password)) { redir('user.php?uid='.$uid.'&error=3&4', true); }
      if(!preg_match('/[a-zA-ZáéíóöõúüûÁÉÍÓÖÕÚÜÛ]/', $rpass)) { redir('user.php?uid='.$uid.'&error=3&3&4', true); }
      if(!preg_match('/[0-9]/', $rpass)) { redir('user.php?uid='.$uid.'&error=3&5', true); }

      if($username != '') { $password = ident($username, $rpass); }
      else { $password = bin2hex($rpass); }
    }
    else {
      if($USERDATA['username'] != $username) { $password = ident($username, hex2bin($USERDATA['password'])); }
      else { $password = $USERDATA['password']; }
    }

    if($_POST['email'] != '') {
      $email = esc($_POST['email'], 1);
      if(preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $email)) {
        if(checkDupeEmail($email, true, 1)) {
          //redir('user.php?uid='.$uid.'&error=4', true);
        }
      }
      else { redir('user.php?uid='.$uid.'&error=5', true); }
    }
    else { $email = $USERDATA['email']; }

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
      else { $status = 0; }

      if(is_numeric($_POST['user_class']) && rights($USER, 0) >= $_POST['user_class']) {
        $user_class = esc($_POST['user_class'], 1);
      }
      else { $user_class = rights($USERDATA, 0); }

      if(is_numeric($_POST['user_rights']) && rights($USER, 1) >= $_POST['user_rights']) {
        $user_rights = esc($_POST['user_rights'], 1);
      }
      else { $user_rights = rights($USERDATA, 1); }

      if($full_log) {
        if(is_numeric($_POST['log_rights']) && rights($USER, 2) >= $_POST['log_rights']) {
          $log_rights = esc($_POST['log_rights'], 1);
        }
        else { $log_rights = rights($USERDATA, 2); }
      }
      else { $log_rights = rights($USERDATA, 2); }

      if($_POST['verif'] != '') {
        $verif = esc($_POST['verif'], 1);
        if(!preg_match('/^[0-9A-Za-z_]{8,32}$/', $verif)) { redir('user.php?uid='.$uid.'&error=6', true); }
      }
      else { $verif = $USERDATA['verif']; }

      if($_POST['secret0'] != 'false') {
        $secret0 = esc($_POST['secret0'], 1);
        if($secret0 == 'passw') {
          $secret1 = mktime($_POST['secret1_hour'], $_POST['secret1_minute'], $_POST['secret1_second'], $_POST['secret1_month'], $_POST['secret1_day'], $_POST['secret1_year']);
          if($secret1 == (-1) || $secret1 === false) { redir('user.php?uid='.$uid.'&error=7', true); }
          $secret2 = esc($_POST['secret2'], 1);
          if(!preg_match('/^[0-9A-Za-z_]{8,32}$/', $secret2)) { redir('user.php?uid='.$uid.'&error=8', true); }
          $secret = $secret0.'|'.$secret1.'|'.$secret2;
        }
        //elseif($secret0 == 'email') {}
        else { $secret = ''; }
      }
      else { $secret = ''; }
    }
    else {
      $status = $USERDATA['status'];
      $user_class = rights($USERDATA, 0);
      $user_rights = rights($USERDATA, 1);
      $log_rights = rights($USERDATA, 2);
      $verif = $USERDATA['verif'];
      $secret = $USERDATA['secret'];
    }

    $rights = $user_class.'|'.$user_rights.'|'.$log_rights.'|';

    //$own_acc = esc($_POST['own_acc'], 1);
    $own_acc = '';

    $admin_comm = esc($_POST['admin_comm'], 1);

    if($verif == '' && ($username == '' || $password == '')) { redir('user.php?uid='.$uid.'&error=9', true); }

    $query0 = "UPDATE `tam_users` SET `username` = '$username', `password` = '$password', `email` = '$email', `notif` = '$notif', `status` = '$status', `verif` = '$verif', `rights` = '$rights', `invites` = '$invites', `secret` = '$secret', `own_acc` = '$own_acc', `admin_comm` = '$admin_comm' WHERE `uid` = '$uid'";
    sql_query($query0);

    redir('user.php?uid='.$uid, true);
  }
}
else { redir('index.php', true); }
?>