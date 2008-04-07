<?php
/* REGISTER */

include('functions.php');

if(checkLogin(false) !== false) { redir('index.php', true); }

head();

$USERDATA = checkVerif($_GET['uid'], $_GET['verif']);
if($USERDATA !== false) {
  box(0, 'Regisztráció');
?>

<form action="register_handler.php" method="post">
  <div class="register_row">
    <div class="register_text">Felhasználónév:</div>
    <div class="register_input">
    <?php
      if($USERDATA['username'] == '') { echo '<input type="text" maxlength="32" name="username" class="input register_inputfield"/>'; }
      else { echo $USERDATA['username']; }
    ?>
    </div>
  </div>
  <div class="register_row">
    <div class="register_text">E-Mail cím:</div>
    <div class="register_input">
    <?php
      if($USERDATA['email'] == '') { echo '<input type="text" maxlength="200" name="email" class="input register_inputfield"/>'; }
      else { echo $USERDATA['email']; }
    ?>
    </div>
  </div>
  <?php if($USERDATA['password'] == '') { ?>
  <div class="register_row">
    <div class="register_text">Jelszó:</div>
    <div class="register_input"><input type="password" maxlength="32" name="newpass1" class="input register_inputfield"/></div>
  </div>
  <div class="register_row">
    <div class="register_text">Jelszó megerõsítése:</div>
    <div class="register_input"><input type="password" maxlength="32" name="newpass2" class="input register_inputfield"/></div>
  </div>
  <?php } ?>
  <div class="register_row">
    <input type="hidden" name="uid" value="<?php echo esc($_GET['uid'], 2); ?>"/>
    <input type="hidden" name="verif" value="<?php echo esc($_GET['verif'], 2); ?>"/>
    <input type="submit" class="button register_button" value="<?php if($USERDATA['username'] != '' && $USERDATA['email'] != '' && $USERDATA['password'] != '') { echo 'Felhasználó megerõsítése'; } else { echo 'Mehet'; } ?>"/>
  </div>
</form>

<?php
  if($_GET['error'] == 1) { echo '<br/><br/><b class="register_error">A felhasználónév minimum 3, maximum 32 karakter hosszú legyen, csak kisbetû, nagybetû és szám szerepelhet benne az angol ábécé-bõl!</b>'; }
  elseif($_GET['error'] == 2) { echo '<br/><br/><b class="register_error">Ez a felhasználónév foglalt!</b>'; }
  //elseif($_GET['error'] == 3) { echo '<br/><b class="register_error">Ez az e-mail cím foglalt!</b>'; }
  elseif($_GET['error'] == 4) { echo '<br/><br/><b class="register_error">Valós e-mail címet adj meg!</b>'; }
  elseif($_GET['error'] == 5) { echo '<br/><br/><b class="register_error">A jelszó megerõsítése helytelen!</b>'; }
  elseif($_GET['error'] == 6) { echo '<br/><br/><b class="register_error">A jelszó minimum 6, maximum 32 karakter hosszú legyen, szerepeljen benne betû és szám, és a jelszó ne tartalmazza a felhasználónevet, vagy a felhasználónév a jelszót!</b>'; }

  box(1);
}
else {
  box(0, 'Meghívó beváltása');
?>

<form action="register.php" method="get">
  <div class="register_row">
    <div class="register_text">uID:</div>
    <div class="register_input"><input value="<?php echo esc($_GET['uid'], 2); ?>" type="text" maxlength="10" name="uid" class="input register_inputfield"/></div>
  </div>
  <div class="register_row">
    <div class="register_text">Megerõsítõ kód:</div>
    <div class="register_input"><input value="<?php echo esc($_GET['verif'], 2); ?>" type="text" maxlength="32" name="verif" class="input register_inputfield"/></div>
  </div>
  <div class="register_row"><input type="submit" class="button register_button" value="Mehet"/></div>
</form>

<?php
  if($_GET['uid'] != '' || $_GET['verif'] != '') { echo '<br/><br/><b class="register_error">Érvénytelen uID / megerõsítõ kód.</b>'; }

  box(1);
}

foot();
?>