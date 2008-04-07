<?php
/* SETTINGS */

include('functions.php');

$USER = checkLogin();

head();

if(rights($USER, 1) > 0) {
  echo '<form action="settings_handler.php" method="post">';
  box(0, 'Beállítások');
?>

  <div class="settings_row">
    <div class="settings_text">Felhasználónév:</div>
    <div class="settings_input"><?php echo (rights($USER, 1) > 2) ? '<a href="user.php?uid='.$USER['uid'].'">'.esc($USER['username'], 2).'</a>' : esc($USER['username'], 2); ?></div>
  </div>
  <div class="settings_row">
    <div class="settings_text">E-Mail cím:</div>
    <div class="settings_input"><input value="<?php echo esc($USER['email'], 2); ?>" type="text" maxlength="200" name="email" class="input settings_inputfield<?php if(rights($USER, 1) < 2) { echo ' input_disabled'; } ?>"<?php if(rights($USER, 1) < 2) { echo ' disabled'; } ?>/></div>
  </div>
  <div class="settings_row">
    <div class="settings_text">Értesítés rendszer üzenet érkezésérõl:</div>
    <div class="settings_input"><input value="1" type="checkbox" name="notif0" class="input settings_checkbox"<?php echo (rights($USER, 0, 'notif') == 0) ? '' : ' checked'; ?>/></div>
  </div>
  <div class="settings_row">
    <div class="settings_text">Értesítés használt accountokkal kapcsolatos üzenet érkezésérõl:</div>
    <div class="settings_input"><input value="1" type="checkbox" name="notif1" class="input settings_checkbox"<?php echo (rights($USER, 1, 'notif') == 0) ? '' : ' checked'; ?>/></div>
  </div>
    <div class="settings_row">
    <div class="settings_text">Értesítés privát üzenet érkezésérõl:</div>
    <div class="settings_input"><input value="1" type="checkbox" name="notif2" class="input settings_checkbox"<?php echo (rights($USER, 2, 'notif') == 0) ? '' : ' checked'; ?>/></div>
  </div>
  <div class="settings_row">
    <div class="settings_text">Új jelszó:</div>
    <div class="settings_input"><input type="password" maxlength="32" name="newpass1" class="input settings_inputfield"/></div>
  </div>
  <div class="settings_row">
    <div class="settings_text">Új jelszó megerõsítése:</div>
    <div class="settings_input"><input type="password" maxlength="32" name="newpass2" class="input settings_inputfield"/></div>
  </div>
  <?php box(1); ?>

  <?php box(0); ?>
  <div class="settings_row">
    <div class="settings_text">Jelenlegi jelszó:</div>
    <div class="settings_input"><input type="password" maxlength="32" name="password" class="input settings_inputfield"/></div>
  </div>
  <div class="settings_row"><input type="submit" class="button settings_button" value="Mehet"/></div>

<?php
  if($_GET['error'] == 1) { echo '<br/><br/><b class="settings_error">Hibás jelszó.</b>'; }
  elseif($_GET['error'] == 2) { echo '<br/><br/><b class="settings_error">Valós e-mail címet adj meg!</b>'; }
  elseif($_GET['error'] == 3) { echo '<br/><br/><b class="settings_error">Az új jelszó megerõsítése helytelen!</b>'; }
  //elseif($_GET['error'] == 4) { echo '<br/><br/><b class="settings_error">Az új jelszó minimum 6, maximum 32 karakter hosszú legyen, szerepeljen benne kisbetû, nagybetû, és szám, és a jelszó ne tartalmazza a felhasználónevet, vagy a felhasználónév a jelszót!</b>'; }
  elseif($_GET['error'] == 4) { echo '<br/><br/><b class="settings_error">Az új jelszó minimum 6, maximum 32 karakter hosszú legyen, szerepeljen benne betû és szám, és a jelszó ne tartalmazza a felhasználónevet, vagy a felhasználónév a jelszót!</b>'; }
  elseif($_GET['error'] == 5) { echo '<br/><br/><b class="settings_error">Az új jelszó megerõsítése helytelen!</b>'; }
  //elseif($_GET['error'] == 6) { echo '<br/><br/><b class="settings_error">A megadott e-mail cím foglalt!</b>'; }

  box(1);
  echo '</form>';
}
else {
  box(0, 'Hiba');
  echo '<b class="settings_error">Nincs jogod megváltoztatni a beállításokat.</b>';
  box(1);
}

foot();
?>