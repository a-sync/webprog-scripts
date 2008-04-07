<?php
/* REGISTER_HANDLER */

include('functions.php');

if(checkLogin(false) !== false) { redir('index.php', true); }

$USERDATA = checkVerif($_POST['uid'], $_POST['verif']);
if($USERDATA !== false) {

  if($USERDATA['username'] == '') {
    $username = esc($_POST['username'], 1);
    if(!preg_match('/^[A-Za-z0-9]{3,32}$/', $username)) { redir('register.php?uid='.$USERDATA['uid'].'&verif='.$USERDATA['verif'].'&error=1', true); }
    if(checkDupeUsername($username)) {
      redir('register.php?uid='.$USERDATA['uid'].'&verif='.$USERDATA['verif'].'&error=2', true);
    }
  }
  else { $username = $USERDATA['username']; }


  if($USERDATA['email'] == '') {
    $email = esc($_POST['email'], 1);
    if(preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $email)) {
      if(checkDupeEmail($email)) {
        //redir('register.php?uid='.$USERDATA['uid'].'&verif='.$USERDATA['verif'].'&error=3', true);
      }
    }
    else { redir('register.php?uid='.$USERDATA['uid'].'&verif='.$USERDATA['verif'].'&error=4', true); }        
  }
  else { $email = $USERDATA['email']; }


  if($USERDATA['password'] == '') {
    if($_POST['newpass1'] != '' || $_POST['newpass2'] != '') {
      $password1 = $_POST['newpass1'];
      $password2 = $_POST['newpass2'];

      if($password1 != $password2) { redir('register.php?uid='.$USERDATA['uid'].'&verif='.$USERDATA['verif'].'&error=5', true); }
      else {
        if(strlen($password1) < 6 || strlen($password1) > 32) { redir('register.php?uid='.$USERDATA['uid'].'&verif='.$USERDATA['verif'].'&error=6&1', true); }
        if(preg_match('/'.strtolower($username).'/', strtolower($password1)) || preg_match('/'.strtolower($password1).'/', strtolower($username))) { redir('register.php?uid='.$USERDATA['uid'].'&verif='.$USERDATA['verif'].'&error=6&2', true); }
        //if(!preg_match('/[a-zαινσφυϊόϋ]/', $password1)) { redir('register.php?uid='.$USERDATA['uid'].'&verif='.$USERDATA['verif'].'&error=6&3', true); }
        //if(!preg_match('/[A-ZΑΙΝΣΦΥΪάΫ]/', $password1)) { redir('register.php?uid='.$USERDATA['uid'].'&verif='.$USERDATA['verif'].'&error=6&4', true); }
        if(!preg_match('/[a-zA-ZαινσφυϊόϋΑΙΝΣΦΥΪάΫ]/', $password1)) { redir('register.php?uid='.$USERDATA['uid'].'&verif='.$USERDATA['verif'].'&error=6&3&4', true); }
        if(!preg_match('/[0-9]/', $password1)) { redir('register.php?uid='.$USERDATA['uid'].'&verif='.$USERDATA['verif'].'&error=6&5', true); }
      }

      $password = ident($username, $password1);
    }
    else { $password = $USERDATA['password']; }
  }
  elseif($USERDATA['username'] == '') {
    $password = ident($username, hex2bin($USERDATA['password']));
  }
  else { $password = $USERDATA['password']; }

  $reg_time = time();

  $query = "UPDATE `tam_users` SET `username` = '$username', `password` = '$password', `email` = '$email', `verif` = '', `reg_time` = '$reg_time' WHERE `uid` = '{$USERDATA['uid']}'";
  sql_query($query);

  logger(1, 0, 'Sikeres regisztrαciσ: '.$username.' (uID: '.$USERDATA['uid'].')');
  redir('login.php?error=5', true);
}
else { redir('register.php', true); }
?>