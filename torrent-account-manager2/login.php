<?php
/* LOGIN */

include('functions.php');

if(checkLogin(false) !== false) { redir('index.php', true); }

head();

box(0, 'Bejelentkezés');

?>

<form action="login_handler.php" method="post">
  <div class="login_row">
    <div class="login_text">Felhasználónév:</div>
    <div class="login_input"><input type="text" maxlength="32" name="username" class="input login_inputfield"/></div>
  </div>
  <div class="login_row">
    <div class="login_text">Jelszó:</div>
    <div class="login_input"><input type="password" maxlength="32" name="password" class="input login_inputfield"/></div>
  </div>
  <div class="login_row"><input type="submit" class="button login_button" value="Mehet"/></div>
</form>

<?php
if($_GET['error'] == 1) { echo '<br/><br/><b class="login_error">Hibás felhasználónév / jelszó.</b>'; }
elseif($_GET['error'] == 2) { echo '<br/><br/><b class="login_error">Ki lettél tiltva.</b>'; }
elseif($_GET['error'] == 3) { echo '<br/><br/><b class="login_error">Hibásak a sütijeid.</b>'; }
elseif($_GET['error'] == 4) { echo '<br/><br/><b class="login_error">Ez a felhasználó nincs megerõsítve.</b>'; }
elseif($_GET['error'] == 5) { echo '<br/><br/><b class="login_important">Sikeresen regisztráltál. Jelentkezz be!</b>'; }

box(1);

foot();
?>