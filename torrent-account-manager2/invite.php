<?php
/* INVITE */
global $tam_rights;

include('functions.php');

$USER = checkLogin();

head();

if($USER['invites'] != 0) {
  echo '<form action="invite_handler.php" method="post">';
  box(0, 'Meghívás');
?>

<div class="invite_row"><?php echo $USER['invites']; ?>db meghívód van.</div>
<br/>
  <div class="invite_row">
    <div class="invite_text"><?php if(rights($USER, 1) > 4) { echo '<b class="invite_error invite_star">*</b>'; } ?>Meghívandó e-mail cím:</div>
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
    <div class="invite_text"><b class="invite_important invite_star">*</b>Felhasználónév:</div>
    <div class="invite_input"><input type="text" maxlength="32" name="username" class="input invite_inputfield"/></div>
  </div>
  <div class="invite_row">
    <div class="invite_text"><b class="invite_important invite_star">*</b>Jelszó:</div>
    <div class="invite_input"><input type="text" maxlength="32" name="password" class="input invite_inputfield"/></div>
  </div>
  <div class="invite_row">
    <div class="invite_text">E-Mail cím:</div>
    <div class="invite_input"><input type="text" maxlength="200" name="email" class="input invite_inputfield"/></div>
  </div>
  <div class="invite_row">
    <div class="invite_text">Értesítés rendszer üzenet érkezésérõl:</div>
    <div class="invite_input"><input value="1" type="checkbox" name="notif0" class="input invite_checkbox"/></div>
  </div>
  <div class="invite_row">
    <div class="invite_text">Értesítés használt accountokkal kapcsolatos üzenet érkezésérõl:</div>
    <div class="invite_input"><input value="1" type="checkbox" name="notif1" class="input invite_checkbox"/></div>
  </div>
  <div class="invite_row">
    <div class="invite_text">Értesítés privát üzenet érkezésérõl:</div>
    <div class="invite_input"><input value="1" type="checkbox" name="notif2" class="input invite_checkbox"/></div>
  </div>
  <div class="invite_row">
    <div class="invite_text">Meghívók:</div>
    <div class="invite_input"><input type="text" maxlength="9" name="invites" class="input invite_inputfield_9char" value="0"/></div>
  </div>

  <?php if($full_admin) {
    $status[0] = 'Kitiltott';
    $status[1] = 'Engedélyezett';
    $class[0] = 'Felhasználó';
    $class[1] = 'Moderátor';
    $class[2] = 'Adminisztrátor';
    $user_rights_sel[rights($tam_rights, 1, false)] = ' selected';
    $log_rights_sel[rights($tam_rights, 2, false)] = ' selected';
  ?>
  <div class="invite_row">
    <div class="invite_text">Státusz:</div>
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
    <div class="invite_text">Felhasználói jogok:</div>
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
    <div class="invite_text">Saját accountok:</div>
    <div class="invite_input">
      <input value="1" type="checkbox" name="IDlist" class="input invite_checkbox"/>
      <input value="2" type="checkbox" name="IDlist" class="input invite_checkbox"/>
      <input value="3" type="checkbox" name="IDlist" class="input invite_checkbox"/>
    </div>
  </div>
*/ ?>
  <div class="invite_row">
    <div class="invite_text"><b class="invite_error invite_star">*</b>Megerõsítõ kód:</div>
    <div class="invite_input"><input type="text" maxlength="32" name="verif" class="input invite_inputfield" value="<?php echo rand_str(); ?>"/></div>
  </div>
  <div class="invite_row">
    <div class="invite_text">Admin komment:</div>
    <div class="invite_input"><textarea name="admin_comm" class="input invite_textarea"></textarea></div>
  </div>

<?php
  }
?>

  <div class="invite_row"><input type="submit" class="button invite_button" value="Meghívás"/></div>

<?php
  if($_GET['error'] == 1) { echo '<br/><br/><b class="invite_error">Valós meghívandó e-mail címet adj meg!</b>'; }
  //elseif($_GET['error'] == 2) { echo '<br/><br/><b class="invite_error">Ez az e-mail cím foglalt!</b>'; }
  elseif($_GET['error'] == 3) { echo '<br/><br/><b class="invite_error">A felhasználónév minimum 3, maximum 32 karakter hosszú legyen, csak kisbetû, nagybetû és szám szerepelhet benne az angol ábécé-bõl!</b>'; }
  elseif($_GET['error'] == 4) { echo '<br/><br/><b class="invite_error">Ez a felhasználónév foglalt!</b>'; }
  //elseif($_GET['error'] == 5) { echo '<br/><br/><b class="settings_error">Az új jelszó minimum 6, maximum 32 karakter hosszú legyen, szerepeljen benne kisbetû, nagybetû, és szám, és a jelszó ne tartalmazza a felhasználónevet, vagy a felhasználónév a jelszót!</b>'; }
  elseif($_GET['error'] == 5) { echo '<br/><br/><b class="settings_error">Az új jelszó minimum 6, maximum 32 karakter hosszú legyen, szerepeljen benne betû és szám, és a jelszó ne tartalmazza a felhasználónevet, vagy a felhasználónév a jelszót!</b>'; }
  elseif($_GET['error'] == 6) { echo '<br/><br/><b class="invite_error">Valós e-mail címet adj meg!</b>'; }
  elseif($_GET['error'] == 7) { echo '<br/><br/><b class="invite_error">A megerõsítõ kód minimum 8, maximum 32 karakter hosszú legyen, és csak alsóvonás (<b class="invite_important">_</b>), számok, és az angol ábécé betûi szerepelhetnek benne!</b>'; }
  elseif($_GET['error'] == 8) { echo '<br/><br/><b class="invite_error">Legalább az <b class="invite_error register_star">*</b>e-mail meghívó / <b class="invite_error register_star">*</b>megerõsítõ kód, vagy a <b class="invite_important register_star">*</b>felhasználónév / <b class="invite_important register_star">*</b>jelszó párost add meg!</b>'; }

  box(1);
  echo '</form>';
}
elseif(rights($USER, 1) < 3) {
  box(0, 'Hiba');
  echo '<b class="invite_error">Nincsen meghívód.</b>';
  box(1);
}

$INVITED = getInvited($USER['uid']);
if(rights($USER, 1) > 2 && $INVITED !== false) {
  box(0, 'Meghívottak');

  echo '<div class="invited_head">'
  .'<div class="invited_col1">Meghívás / Regisztrálás</div>'
  .'<div class="invited_col2">uID</div>'
  .'<div class="invited_col3">Felhasználónév</div>'
  .'<div class="invited_col4">Megerõsítõ kód</div>'
  .'</div>';

  for($i = 0; $i < count($INVITED); $i++) {
    $alt_class = ($i % 2) == 0 ? '' : ' invited_altrow';
    $invited_user = $INVITED[$i];

    $the_verif = ($invited_user['last_login'] == 0 || rights($USER, 1) > 3) ? $invited_user['verif'] : '(nem láthatod)';
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
  echo '<b class="invite_error">Nincsenek meghívottaid.</b>';
  box(1);
}

foot();
?>