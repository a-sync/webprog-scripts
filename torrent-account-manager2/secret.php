<?php
/* SECRET */

include('functions.php');

if(checkLogin(false) !== false) { redir('index.php', true); }

head();

$USERDATA = checkSecret($_GET['uid'], $_GET['secret']);
if($USERDATA !== false) {
  box(0, 'Adatcsere');

  $the_secret = explode('|', $USERDATA['secret']);
?>

<form action="secret_handler.php" method="post">
  <?php if($the_secret[0] == 'passw') { ?>
  <div class="secret_row">
    <div class="secret_text">Új jelszó:</div>
    <div class="secret_input"><input type="password" maxlength="32" name="newpass1" class="input secret_inputfield"/></div>
  </div>
  <div class="secret_row">
    <div class="secret_text">Új jelszó megerõsítése:</div>
    <div class="secret_input"><input type="password" maxlength="32" name="newpass2" class="input secret_inputfield"/></div>
  </div>
  <?php } /*elseif($the_secret[0] == 'email') { ?>
  <div class="secret_row">
    <div class="secret_text">Új e-mail cím:</div>
    <div class="secret_input"><input type="text" maxlength="200" name="email" class="input secret_inputfield"/></div>
  </div>
  <?php }*/ ?>
  <div class="secret_row">
    <input type="hidden" name="uid" value="<?php echo esc($_GET['uid'], 2); ?>"/>
    <input type="hidden" name="secret" value="<?php echo esc($_GET['secret'], 2); ?>"/>
    <input type="submit" class="button secret_button" value="Mehet"/>
  </div>
</form>

<?php
  if($_GET['error'] == 5) { echo '<br/><br/><b class="secret_error">A jelszó megerõsítése helytelen!</b>'; }
  elseif($_GET['error'] == 6) { echo '<br/><br/><b class="secret_error">A jelszó minimum 6, maximum 32 karakter hosszú legyen, szerepeljen benne betû és szám, és a jelszó ne tartalmazza a felhasználónevet, vagy a felhasználónév a jelszót!</b>'; }
  //elseif($_GET['error'] == 7) { echo '<br/><br/><b class="secret_error">Ez az e-mail cím foglalt!</b>'; }
  //elseif($_GET['error'] == 8) { echo '<br/><br/><b class="secret_error">Valós e-mail címet adj meg!</b>'; }

  box(1);
}
else {
  box(0, 'Adatcsere kérése');
?>

<form action="secret_handler.php" method="post">
  <div class="secret_row">
    <div class="secret_text">Adat:</div>
    <div class="secret_input"><select name="secret_type" class="input secret_select">
      <?php /*<option value="false" selected>Válassz...</option>*/ ?>
      <option value="passw">Jelszó csere</option>
      <?php /*<option value="email">E-Mail csere</option>*/ ?>
    </select></div>
  </div>
  <div class="secret_row">
    <div class="secret_text">Felhasználónév:</div>
    <div class="secret_input"><input type="text" maxlength="32" name="username" class="input secret_inputfield"/></div>
  </div>
  <div class="secret_row">
    <div class="secret_text">E-Mail:</div>
    <div class="secret_input"><input type="text" maxlength="200" name="email" class="input secret_inputfield"/></div>
  </div>
  <div class="secret_row"><input type="submit" class="button secret_button" value="Mehet"/></div>
</form>

<?php
  if($_GET['error'] == 1) { echo '<br/><br/><b class="secret_error">A regisztrált felhasználónevet és e-mail címet is meg kell adnod.</b>'; }
  elseif($_GET['error'] == 2) { echo '<br/><br/><b class="secret_error">Add meg, hogy melyik adatot szeretnéd lecserélni!</b>'; }
  elseif($_GET['error'] == 3) { echo '<br/><br/><b class="secret_error">Nem létezik aktív felhasználó a megadott adatokkal.</b>'; }
  elseif($_GET['error'] == 4) { echo '<br/><br/><b class="secret_important">A megerõsítéshez szükséges adatok el lettek küldve e-mailben!</b>'; }
  else { echo '<br/><br/><b class="secret_important">A megerõsítéshez szükséges adatokat e-mailben kapod meg!</b>'; }

  box(1);

  box(0, 'Adatcsere megerõsítése');
?>

<form action="secret.php" method="get">
  <div class="secret_row">
    <div class="secret_text">uID:</div>
    <div class="secret_input"><input value="<?php echo esc($_GET['uid'], 2); ?>" type="text" maxlength="10" name="uid" class="input secret_inputfield"/></div>
  </div>
  <div class="secret_row">
    <div class="secret_text">Megerõsítõ kód:</div>
    <div class="secret_input"><input value="<?php echo esc($_GET['secret'], 2); ?>" type="text" maxlength="32" name="secret" class="input secret_inputfield"/></div>
  </div>
  <div class="secret_row"><input type="submit" class="button secret_button" value="Mehet"/></div>
</form>

<?php
  if($_GET['uid'] != '' || $_GET['verif'] != '') { echo '<br/><br/><b class="secret_error">Érvénytelen uID / megerõsítõ kód.</b>'; }

  box(1);
}

foot();
?>