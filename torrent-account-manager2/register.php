<?php
/* REGISTER */

include('functions.php');

if(checkLogin(false) !== false) { redir('index.php', true); }

head();

$USERDATA = checkVerif($_GET['uid'], $_GET['verif']);
if($USERDATA !== false) {
  box(0, 'Regisztr�ci�');
?>

<form action="register_handler.php" method="post">
  <div class="register_row">
    <div class="register_text">Felhaszn�l�n�v:</div>
    <div class="register_input">
    <?php
      if($USERDATA['username'] == '') { echo '<input type="text" maxlength="32" name="username" class="input register_inputfield"/>'; }
      else { echo $USERDATA['username']; }
    ?>
    </div>
  </div>
  <div class="register_row">
    <div class="register_text">E-Mail c�m:</div>
    <div class="register_input">
    <?php
      if($USERDATA['email'] == '') { echo '<input type="text" maxlength="200" name="email" class="input register_inputfield"/>'; }
      else { echo $USERDATA['email']; }
    ?>
    </div>
  </div>
  <?php if($USERDATA['password'] == '') { ?>
  <div class="register_row">
    <div class="register_text">Jelsz�:</div>
    <div class="register_input"><input type="password" maxlength="32" name="newpass1" class="input register_inputfield"/></div>
  </div>
  <div class="register_row">
    <div class="register_text">Jelsz� meger�s�t�se:</div>
    <div class="register_input"><input type="password" maxlength="32" name="newpass2" class="input register_inputfield"/></div>
  </div>
  <?php } ?>
  <div class="register_row">
    <input type="hidden" name="uid" value="<?php echo esc($_GET['uid'], 2); ?>"/>
    <input type="hidden" name="verif" value="<?php echo esc($_GET['verif'], 2); ?>"/>
    <input type="submit" class="button register_button" value="<?php if($USERDATA['username'] != '' && $USERDATA['email'] != '' && $USERDATA['password'] != '') { echo 'Felhaszn�l� meger�s�t�se'; } else { echo 'Mehet'; } ?>"/>
  </div>
</form>

<?php
  if($_GET['error'] == 1) { echo '<br/><br/><b class="register_error">A felhaszn�l�n�v minimum 3, maximum 32 karakter hossz� legyen, csak kisbet�, nagybet� �s sz�m szerepelhet benne az angol �b�c�-b�l!</b>'; }
  elseif($_GET['error'] == 2) { echo '<br/><br/><b class="register_error">Ez a felhaszn�l�n�v foglalt!</b>'; }
  //elseif($_GET['error'] == 3) { echo '<br/><b class="register_error">Ez az e-mail c�m foglalt!</b>'; }
  elseif($_GET['error'] == 4) { echo '<br/><br/><b class="register_error">Val�s e-mail c�met adj meg!</b>'; }
  elseif($_GET['error'] == 5) { echo '<br/><br/><b class="register_error">A jelsz� meger�s�t�se helytelen!</b>'; }
  elseif($_GET['error'] == 6) { echo '<br/><br/><b class="register_error">A jelsz� minimum 6, maximum 32 karakter hossz� legyen, szerepeljen benne bet� �s sz�m, �s a jelsz� ne tartalmazza a felhaszn�l�nevet, vagy a felhaszn�l�n�v a jelsz�t!</b>'; }

  box(1);
}
else {
  box(0, 'Megh�v� bev�lt�sa');
?>

<form action="register.php" method="get">
  <div class="register_row">
    <div class="register_text">uID:</div>
    <div class="register_input"><input value="<?php echo esc($_GET['uid'], 2); ?>" type="text" maxlength="10" name="uid" class="input register_inputfield"/></div>
  </div>
  <div class="register_row">
    <div class="register_text">Meger�s�t� k�d:</div>
    <div class="register_input"><input value="<?php echo esc($_GET['verif'], 2); ?>" type="text" maxlength="32" name="verif" class="input register_inputfield"/></div>
  </div>
  <div class="register_row"><input type="submit" class="button register_button" value="Mehet"/></div>
</form>

<?php
  if($_GET['uid'] != '' || $_GET['verif'] != '') { echo '<br/><br/><b class="register_error">�rv�nytelen uID / meger�s�t� k�d.</b>'; }

  box(1);
}

foot();
?>