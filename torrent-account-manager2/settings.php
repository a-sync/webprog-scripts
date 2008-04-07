<?php
/* SETTINGS */

include('functions.php');

$USER = checkLogin();

head();

if(rights($USER, 1) > 0) {
  echo '<form action="settings_handler.php" method="post">';
  box(0, 'Be�ll�t�sok');
?>

  <div class="settings_row">
    <div class="settings_text">Felhaszn�l�n�v:</div>
    <div class="settings_input"><?php echo (rights($USER, 1) > 2) ? '<a href="user.php?uid='.$USER['uid'].'">'.esc($USER['username'], 2).'</a>' : esc($USER['username'], 2); ?></div>
  </div>
  <div class="settings_row">
    <div class="settings_text">E-Mail c�m:</div>
    <div class="settings_input"><input value="<?php echo esc($USER['email'], 2); ?>" type="text" maxlength="200" name="email" class="input settings_inputfield<?php if(rights($USER, 1) < 2) { echo ' input_disabled'; } ?>"<?php if(rights($USER, 1) < 2) { echo ' disabled'; } ?>/></div>
  </div>
  <div class="settings_row">
    <div class="settings_text">�rtes�t�s rendszer �zenet �rkez�s�r�l:</div>
    <div class="settings_input"><input value="1" type="checkbox" name="notif0" class="input settings_checkbox"<?php echo (rights($USER, 0, 'notif') == 0) ? '' : ' checked'; ?>/></div>
  </div>
  <div class="settings_row">
    <div class="settings_text">�rtes�t�s haszn�lt accountokkal kapcsolatos �zenet �rkez�s�r�l:</div>
    <div class="settings_input"><input value="1" type="checkbox" name="notif1" class="input settings_checkbox"<?php echo (rights($USER, 1, 'notif') == 0) ? '' : ' checked'; ?>/></div>
  </div>
    <div class="settings_row">
    <div class="settings_text">�rtes�t�s priv�t �zenet �rkez�s�r�l:</div>
    <div class="settings_input"><input value="1" type="checkbox" name="notif2" class="input settings_checkbox"<?php echo (rights($USER, 2, 'notif') == 0) ? '' : ' checked'; ?>/></div>
  </div>
  <div class="settings_row">
    <div class="settings_text">�j jelsz�:</div>
    <div class="settings_input"><input type="password" maxlength="32" name="newpass1" class="input settings_inputfield"/></div>
  </div>
  <div class="settings_row">
    <div class="settings_text">�j jelsz� meger�s�t�se:</div>
    <div class="settings_input"><input type="password" maxlength="32" name="newpass2" class="input settings_inputfield"/></div>
  </div>
  <?php box(1); ?>

  <?php box(0); ?>
  <div class="settings_row">
    <div class="settings_text">Jelenlegi jelsz�:</div>
    <div class="settings_input"><input type="password" maxlength="32" name="password" class="input settings_inputfield"/></div>
  </div>
  <div class="settings_row"><input type="submit" class="button settings_button" value="Mehet"/></div>

<?php
  if($_GET['error'] == 1) { echo '<br/><br/><b class="settings_error">Hib�s jelsz�.</b>'; }
  elseif($_GET['error'] == 2) { echo '<br/><br/><b class="settings_error">Val�s e-mail c�met adj meg!</b>'; }
  elseif($_GET['error'] == 3) { echo '<br/><br/><b class="settings_error">Az �j jelsz� meger�s�t�se helytelen!</b>'; }
  //elseif($_GET['error'] == 4) { echo '<br/><br/><b class="settings_error">Az �j jelsz� minimum 6, maximum 32 karakter hossz� legyen, szerepeljen benne kisbet�, nagybet�, �s sz�m, �s a jelsz� ne tartalmazza a felhaszn�l�nevet, vagy a felhaszn�l�n�v a jelsz�t!</b>'; }
  elseif($_GET['error'] == 4) { echo '<br/><br/><b class="settings_error">Az �j jelsz� minimum 6, maximum 32 karakter hossz� legyen, szerepeljen benne bet� �s sz�m, �s a jelsz� ne tartalmazza a felhaszn�l�nevet, vagy a felhaszn�l�n�v a jelsz�t!</b>'; }
  elseif($_GET['error'] == 5) { echo '<br/><br/><b class="settings_error">Az �j jelsz� meger�s�t�se helytelen!</b>'; }
  //elseif($_GET['error'] == 6) { echo '<br/><br/><b class="settings_error">A megadott e-mail c�m foglalt!</b>'; }

  box(1);
  echo '</form>';
}
else {
  box(0, 'Hiba');
  echo '<b class="settings_error">Nincs jogod megv�ltoztatni a be�ll�t�sokat.</b>';
  box(1);
}

foot();
?>