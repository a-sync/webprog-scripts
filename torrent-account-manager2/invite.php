<?php
/* INVITE */
global $tam_rights;

include('functions.php');

$USER = checkLogin();

head();

if($USER['invites'] != 0) {
  echo '<form action="invite_handler.php" method="post">';
  box(0, 'Megh�v�s');
?>

<div class="invite_row"><?php echo $USER['invites']; ?>db megh�v�d van.</div>
<br/>
  <div class="invite_row">
    <div class="invite_text"><?php if(rights($USER, 1) > 4) { echo '<b class="invite_error invite_star">*</b>'; } ?>Megh�vand� e-mail c�m:</div>
    <div class="invite_input"><input type="text" maxlength="32" name="invite_email" class="input invite_inputfield"/></div>
  </div>

<?php
  if(rights($USER, 1) > 4) {
    box(1);
    $full_admin = (rights($USER, 1) > 5) ? true : false;
    $full_log = (rights($USER, 2) > 3) ? true : false;
    box(0);
?>

<br/>
  <div class="invite_row">
    <div class="invite_text"><b class="invite_important invite_star">*</b>Felhaszn�l�n�v:</div>
    <div class="invite_input"><input type="text" maxlength="32" name="username" class="input invite_inputfield"/></div>
  </div>
  <div class="invite_row">
    <div class="invite_text"><b class="invite_important invite_star">*</b>Jelsz�:</div>
    <div class="invite_input"><input type="text" maxlength="32" name="password" class="input invite_inputfield"/></div>
  </div>
  <div class="invite_row">
    <div class="invite_text">E-Mail c�m:</div>
    <div class="invite_input"><input type="text" maxlength="200" name="email" class="input invite_inputfield"/></div>
  </div>
  <div class="invite_row">
    <div class="invite_text">�rtes�t�s rendszer �zenet �rkez�s�r�l:</div>
    <div class="invite_input"><input value="1" type="checkbox" name="notif0" class="input invite_checkbox"/></div>
  </div>
  <div class="invite_row">
    <div class="invite_text">�rtes�t�s haszn�lt accountokkal kapcsolatos �zenet �rkez�s�r�l:</div>
    <div class="invite_input"><input value="1" type="checkbox" name="notif1" class="input invite_checkbox"/></div>
  </div>
  <div class="invite_row">
    <div class="invite_text">�rtes�t�s priv�t �zenet �rkez�s�r�l:</div>
    <div class="invite_input"><input value="1" type="checkbox" name="notif2" class="input invite_checkbox"/></div>
  </div>
  <div class="invite_row">
    <div class="invite_text">Megh�v�k:</div>
    <div class="invite_input"><input type="text" maxlength="9" name="invites" class="input invite_inputfield_9char" value="0"/></div>
  </div>

  <?php if($full_admin) {
    $status[0] = 'Kitiltott';
    $status[1] = 'Enged�lyezett';
    $class[0] = 'Felhaszn�l�';
    $class[1] = 'Moder�tor';
    $class[2] = 'Adminisztr�tor';
    $user_rights_sel[rights($tam_rights, 1, false)] = ' selected';
    $log_rights_sel[rights($tam_rights, 2, false)] = ' selected';
  ?>
  <div class="invite_row">
    <div class="invite_text">St�tusz:</div>
    <div class="invite_input"><select name="status" class="input invite_select">
      <option value="0"><?php echo $status[0]; ?></option>
      <option value="1" selected><?php echo $status[1]; ?></option>
    </select></div>
  </div>
  <div class="invite_row">
    <div class="invite_text">Rang:</div>
    <div class="invite_input"><select name="user_class" class="input invite_select">
      <option value="0" selected><?php echo $class[0]; ?></option>
      <?php if(rights($USER, 0) > 0) { ?><option value="1"><?php echo $class[1]; ?></option><?php } ?>
      <?php if(rights($USER, 0) > 1) { ?><option value="2"><?php echo $class[2]; ?></option><?php } ?>
    </select></div>
  </div>
  <div class="invite_row">
    <div class="invite_text">Felhaszn�l�i jogok:</div>
    <div class="invite_input"><select name="user_rights" class="input invite_select">
      <option value="0"<?php echo $user_rights_sel[0]; ?>>0</option>
      <option value="1"<?php echo $user_rights_sel[1]; ?>>1</option>
      <option value="2"<?php echo $user_rights_sel[2]; ?>>2</option>
      <option value="3"<?php echo $user_rights_sel[3]; ?>>3</option>
      <option value="4"<?php echo $user_rights_sel[4]; ?>>4</option>
      <option value="5"<?php echo $user_rights_sel[5]; ?>>5</option>
      <option value="6"<?php echo $user_rights_sel[6]; ?>>6</option>
    </select></div>
  </div>
  <?php if($full_log) { ?>
  <div class="invite_row">
    <div class="invite_text">Log jogok:</div>
    <div class="invite_input"><select name="log_rights" class="input invite_select">
      <option value="0"<?php echo $log_rights_sel[0]; ?>>0</option>
      <option value="1"<?php echo $log_rights_sel[1]; ?>>1</option>
      <option value="2"<?php echo $log_rights_sel[2]; ?>>2</option>
      <option value="3"<?php echo $log_rights_sel[3]; ?>>3</option>
      <option value="4"<?php echo $log_rights_sel[4]; ?>>4</option>
    </select></div>
  </div>
  <?php } ?>
  <?php } ?>
<?php /*
  <div class="invite_row">
    <div class="invite_text">Saj�t accountok:</div>
    <div class="invite_input">
      <input value="1" type="checkbox" name="IDlist" class="input invite_checkbox"/>
      <input value="2" type="checkbox" name="IDlist" class="input invite_checkbox"/>
      <input value="3" type="checkbox" name="IDlist" class="input invite_checkbox"/>
    </div>
  </div>
*/ ?>
  <div class="invite_row">
    <div class="invite_text"><b class="invite_error invite_star">*</b>Meger�s�t� k�d:</div>
    <div class="invite_input"><input type="text" maxlength="32" name="verif" class="input invite_inputfield" value="<?php echo rand_str(); ?>"/></div>
  </div>
  <div class="invite_row">
    <div class="invite_text">Admin komment:</div>
    <div class="invite_input"><textarea name="admin_comm" class="input invite_textarea"></textarea></div>
  </div>

<?php
  }
?>

  <div class="invite_row"><input type="submit" class="button invite_button" value="Megh�v�s"/></div>

<?php
  if($_GET['error'] == 1) { echo '<br/><br/><b class="invite_error">Val�s megh�vand� e-mail c�met adj meg!</b>'; }
  //elseif($_GET['error'] == 2) { echo '<br/><br/><b class="invite_error">Ez az e-mail c�m foglalt!</b>'; }
  elseif($_GET['error'] == 3) { echo '<br/><br/><b class="invite_error">A felhaszn�l�n�v minimum 3, maximum 32 karakter hossz� legyen, csak kisbet�, nagybet� �s sz�m szerepelhet benne az angol �b�c�-b�l!</b>'; }
  elseif($_GET['error'] == 4) { echo '<br/><br/><b class="invite_error">Ez a felhaszn�l�n�v foglalt!</b>'; }
  //elseif($_GET['error'] == 5) { echo '<br/><br/><b class="settings_error">Az �j jelsz� minimum 6, maximum 32 karakter hossz� legyen, szerepeljen benne kisbet�, nagybet�, �s sz�m, �s a jelsz� ne tartalmazza a felhaszn�l�nevet, vagy a felhaszn�l�n�v a jelsz�t!</b>'; }
  elseif($_GET['error'] == 5) { echo '<br/><br/><b class="settings_error">Az �j jelsz� minimum 6, maximum 32 karakter hossz� legyen, szerepeljen benne bet� �s sz�m, �s a jelsz� ne tartalmazza a felhaszn�l�nevet, vagy a felhaszn�l�n�v a jelsz�t!</b>'; }
  elseif($_GET['error'] == 6) { echo '<br/><br/><b class="invite_error">Val�s e-mail c�met adj meg!</b>'; }
  elseif($_GET['error'] == 7) { echo '<br/><br/><b class="invite_error">A meger�s�t� k�d minimum 8, maximum 32 karakter hossz� legyen, �s csak als�von�s (<b class="invite_important">_</b>), sz�mok, �s az angol �b�c� bet�i szerepelhetnek benne!</b>'; }
  elseif($_GET['error'] == 8) { echo '<br/><br/><b class="invite_error">Legal�bb az <b class="invite_error register_star">*</b>e-mail megh�v� / <b class="invite_error register_star">*</b>meger�s�t� k�d, vagy a <b class="invite_important register_star">*</b>felhaszn�l�n�v / <b class="invite_important register_star">*</b>jelsz� p�rost add meg!</b>'; }

  box(1);
  echo '</form>';
}
elseif(rights($USER, 1) < 3) {
  box(0, 'Hiba');
  echo '<b class="invite_error">Nincsen megh�v�d.</b>';
  box(1);
}

$INVITED = getInvited($USER['uid']);
if(rights($USER, 1) > 2 && $INVITED !== false) {
  box(0, 'Megh�vottak');

  echo '<div class="invited_head">'
  .'<div class="invited_col1">Megh�v�s / Regisztr�l�s</div>'
  .'<div class="invited_col2">uID</div>'
  .'<div class="invited_col3">Felhaszn�l�n�v</div>'
  .'<div class="invited_col4">Meger�s�t� k�d</div>'
  .'</div>';

  for($i = 0; $i < count($INVITED); $i++) {
    $alt_class = ($i % 2) == 0 ? '' : ' invited_altrow';
    $invited_user = $INVITED[$i];

    $the_verif = ($invited_user['last_login'] == 0 || rights($USER, 1) > 3) ? $invited_user['verif'] : '(nem l�thatod)';
    $the_username = ($invited_user['username'] == '') ? '(nincs)' : $invited_user['username'];

    echo '<div class="invited_row'.$alt_class.'">'
    .'<div class="invited_col1">'._date($invited_user['reg_time']).'</div>'
    .'<div class="invited_col2">'.$invited_user['uid'].'</div>'
    .'<div class="invited_col3"><a href="user.php?uid='.$invited_user['uid'].'">'.$the_username.'</a></div>'
    .'<div class="invited_col4">'.$the_verif.'</div>'
    .'</div>';
  }

  box(1);
}
elseif(rights($USER, 1) > 2) {
  box(0, 'Hiba');
  echo '<b class="invite_error">Nincsenek megh�vottaid.</b>';
  box(1);
}

foot();
?>