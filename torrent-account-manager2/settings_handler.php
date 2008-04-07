<?php
/* SETTINGS_HANDLER */

include('functions.php');

$USER = checkLogin();

if(rights($USER, 1) > 0) {
  if(ident($USER['username'], $_POST['password']) == $USER['password']) {

    if(rights($USER, 1) > 1) {
      if($_POST['email'] != '') {
        $email = esc($_POST['email'], 1);
        if(preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $email)) {
          if(checkDupeEmail($email, true, 1)) {
            //redir('settings.php?error=6', true);
          }
        }
        else { redir('settings.php?error=2', true); }        
      }
      else { $email = $USER['email']; }
    }
    else { $email = $USER['email']; }

    if($_POST['notif0'] == 1) { $notif = '1|'; }
    else { $notif = '0|'; }
    if($_POST['notif1'] == 1) { $notif = $notif.'1|'; }
    else { $notif = $notif.'0|'; }
    if($_POST['notif2'] == 1) { $notif = $notif.'1'; }
    else { $notif = $notif.'0'; }

    if($_POST['newpass1'] != '' || $_POST['newpass2'] != '') {
      $password1 = $_POST['newpass1'];
      $password2 = $_POST['newpass2'];

      if($password1 != $password2) { redir('settings.php?error=3', true); }
      else {
        if(strlen($password1) < 6 || strlen($password1) > 32) { redir('settings.php?error=4&1', true); }
        if(preg_match('/'.strtolower($USER['username']).'/', strtolower($password1)) || preg_match('/'.strtolower($password1).'/', strtolower($USER['username']))) { redir('settings.php?error=4&2', true); }
        //if(!preg_match('/[a-záéíóöõúüû]/', $password1)) { redir('settings.php?error=4&3', true); }
        //if(!preg_match('/[A-ZÁÉÍÓÖÕÚÜÛ]/', $password1)) { redir('settings.php?error=4&4', true); }
        if(!preg_match('/[a-zA-ZáéíóöõúüûÁÉÍÓÖÕÚÜÛ]/', $password1)) { redir('settings.php?error=4&3&4', true); }
        if(!preg_match('/[0-9]/', $password1)) { redir('settings.php?error=4&5', true); }
      }

      $password = ident($USER['username'], $password1);
    }
    else { $password = $USER['password']; }

    $query = "UPDATE `tam_users` SET `email` = '$email', `notif` = '$notif', `password` = '$password' WHERE `uid` = '{$USER['uid']}'";
    sql_query($query);

    addCookie($USER['uid'], $password);
    redir('settings.php', true);
  }
  else { redir('settings.php?error=1', true); }
}
else { redir('settings.php', true); }
?>