<?php
/* LOGIN_HANDLER */

include('functions.php');

if($_POST['username'] != '' && $_POST['password'] != '' && checkLogin(false) === false) {
  delCookie();

  $username = esc($_POST['username'], 1);
  $password = ident($username, $_POST['password']);

  $query = "SELECT `uid`, `status`, `last_ips` FROM `tam_users` WHERE `username` = '$username' AND `password` = '$password'";
  $result = sql_query($query);
  $resultnum = mysql_num_rows($result);

  if($resultnum > 1) { logger(0, 4, 'Dupla név / jelszó páros az adatbázisban. - '.$username); }
  if($resultnum < 1) { redir('login.php?error=1', true); }

  $USERDATA = mysql_fetch_array($result, MYSQL_ASSOC);

  if($USERDATA['status'] == '0') { redir('login.php?error=2', true); }
  elseif($USERDATA['verif'] != '') { redir('login.php?error=4', true); }
  else {
    last_login($USERDATA['uid']);
    last_ips($USERDATA);

    addCookie($USERDATA['uid'], $password);
    redir('index.php', true);
  }
}
else { redir('index.php', true); }
?>