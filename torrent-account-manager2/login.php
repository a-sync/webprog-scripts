<?php
/* LOGIN */

include('functions.php');

if(checkLogin(false) !== false) { redir('index.php', true); }

head();

box(0, 'Bejelentkez�s');

?>

<form action="login_handler.php" method="post">
  <div class="login_row">
    <div class="login_text">Felhaszn�l�n�v:</div>
    <div class="login_input"><input type="text" maxlength="32" name="username" class="input login_inputfield"/></div>
  </div>
  <div class="login_row">
    <div class="login_text">Jelsz�:</div>
    <div class="login_input"><input type="password" maxlength="32" name="password" class="input login_inputfield"/></div>
  </div>
  <div class="login_row"><input type="submit" class="button login_button" value="Mehet"/></div>
</form>

<?php
if($_GET['error'] == 1) { echo '<br/><br/><b class="login_error">Hib�s felhaszn�l�n�v / jelsz�.</b>'; }
elseif($_GET['error'] == 2) { echo '<br/><br/><b class="login_error">Ki lett�l tiltva.</b>'; }
elseif($_GET['error'] == 3) { echo '<br/><br/><b class="login_error">Hib�sak a s�tijeid.</b>'; }
elseif($_GET['error'] == 4) { echo '<br/><br/><b class="login_error">Ez a felhaszn�l� nincs meger�s�tve.</b>'; }
elseif($_GET['error'] == 5) { echo '<br/><br/><b class="login_important">Sikeresen regisztr�lt�l. Jelentkezz be!</b>'; }

box(1);

foot();
?>