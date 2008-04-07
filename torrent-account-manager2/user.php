<?php
/* USER */

include('functions.php');

$USER = checkLogin();
$USERDATA = getUserData($_GET['uid']);

head();

if(rights($USER, 1) > 2) {
  $full_details = (rights($USER, 1) > 3) ? true : false;
  $status[0] = 'Kitiltott';
  $status[1] = 'Enged�lyezett';
  $class[0] = 'Felhaszn�l�';
  $class[1] = 'Moder�tor';
  $class[2] = 'Adminisztr�tor';

  if($USERDATA === false || ($full_details === false && ($USERDATA['status'] < 1 || $USERDATA['verif'] != ''))) {
    box(0, 'Hiba');
    echo '<b class="user_error">Nincs akt�v felhaszn�l� ezzel az uID-vel.</b>';
    box(1);
  }
  else {
    $username = ($USERDATA['username'] == '') ? '(uID: '.$USERDATA['uid'].')' : $USERDATA['username'];

    box(0, $username.' adatai');
?>

  <div class="user_row">
    <div class="user_col1">Regisztr�ci�:</div>
    <div class="user_col2"><?php echo _date($USERDATA['reg_time']); ?></div>
  </div>
  <div class="user_row">
    <div class="user_col1">Utols� bejelentkez�s:</div>
    <div class="user_col2"><?php echo _date($USERDATA['last_login']); ?></div>
  </div>
  <?php if($full_details) { ?>
  <div class="user_row">
    <div class="user_col1">E-Mail c�m:</div>
    <div class="user_col2"><?php echo esc($USERDATA['email'], 2); ?></div>
  </div>
  <?php } ?>
  <div class="user_row">
    <div class="user_col1">�rtes�t�s rendszer �zenet �rkez�s�r�l:</div>
    <div class="user_col2"><?php echo (rights($USERDATA, 0, 'notif') == 0) ? 'Nem' : 'Igen'; ?></div>
  </div>
  <div class="user_row">
    <div class="user_col1">�rtes�t�s haszn�lt accountokkal kapcsolatos �zenet �rkez�s�r�l:</div>
    <div class="user_col2"><?php echo (rights($USERDATA, 1, 'notif') == 0) ? 'Nem' : 'Igen'; ?></div>
  </div>
  <div class="user_row">
    <div class="user_col1">�rtes�t�s priv�t �zenet �rkez�s�r�l:</div>
    <div class="user_col2"><?php echo (rights($USERDATA, 2, 'notif') == 0) ? 'Nem' : 'Igen'; ?></div>
  </div>
  <?php if($full_details) { ?>
  <div class="user_row">
    <div class="user_col1">Saj�t megh�v�k sz�ma:</div>
    <div class="user_col2"><?php echo $USERDATA['invites'].'db'; ?></div>
  </div>
  <?php } ?>
  <div class="user_row">
    <div class="user_col1">Rang:</div>
    <div class="user_col2"><?php echo $class[rights($USERDATA, 0)]; ?></div>
  </div>
  <?php if($full_details) { ?>
  <div class="user_row">
    <div class="user_col1">St�tusz:</div>
    <div class="user_col2"><?php echo $status[$USERDATA['status']]; ?></div>
  </div>
  <div class="user_row">
    <div class="user_col1">Felhaszn�l�i jogok:</div>
    <div class="user_col2"><?php echo rights($USERDATA, 1); ?></div>
  </div>
  <div class="user_row">
    <div class="user_col1">Log jogok:</div>
    <div class="user_col2"><?php echo rights($USERDATA, 2); ?></div>
  </div>
  <div class="user_row">
    <div class="user_col1">Meger�s�t� k�d:</div>
    <div class="user_col2"><?php echo $USERDATA['verif']; ?></div>
  </div>
  <div class="user_row">
    <div class="user_col1">Adatcsere k�r�s:</div>
    <div class="user_col2">
    <?php
      $secret = explode('|', $USERDATA['secret']);

      if(checkSecret($USERDATA['uid'], $secret[2]) !== false) {
        if($secret[0] == 'passw') {
          echo 'Jelsz� csere: '._date($secret[1])
          .'<br/>uID: '.$USERDATA['uid']
          .'<br/>Meger�s�t� k�d: '.$secret[2];
        }
        //elseif($secret[0] == 'email') {}
      }
    ?></div>
  </div>
<?php /*
  <div class="user_row">
    <div class="user_col1">Saj�t accountok:</div>
    <div class="user_col2"><?php echo $USERDATA['own_acc']; ?></div>
  </div>
*/ ?>
  <div class="user_row">
    <div class="user_col1">Admin komment:</div>
    <div class="user_col2"><div class="user_admin_comm"><?php echo nl2br(esc($USERDATA['admin_comm'], 2)); ?></div></div>
  </div>
  <div class="user_row">
    <div class="user_col1">Megh�v�ja:</div>
    <div class="user_col2">
    <?php
      $INVITER = getUserData($USERDATA['inviter']);
      echo '<a href="user.php?uid='.$INVITER['uid'].'">'.$INVITER['username'].' (uID: '.$USERDATA['inviter'].')</a>'; 
    ?></div>
  </div>
  <div class="user_row">
    <div class="user_col1">Utols� IP c�mek:</div>
    <div class="user_col2"><?php echo str_replace('|', ', ', $USERDATA['last_ips']); ?></div>
  </div>
  <?php } ?>

<?php
    box(1);

    if(rights($USER, 1) > 4 && rights($USER, 0) >= rights($USERDATA, 0)) {
      $USERDATA = getUserData($_GET['uid']);
      $full_admin = (rights($USER, 1) > 5) ? true : false;
      $full_log = (rights($USER, 2) > 3) ? true : false;

      box(0, 'Adminisztr�ci�');

      $class_sel[rights($USERDATA, 0)] = ' selected';
      $status_sel[$USERDATA['status']] = ' selected';
      $user_rights_sel[rights($USERDATA, 1)] = ' selected';
      $log_rights_sel[rights($USERDATA, 2)] = ' selected';

      $secret = explode('|', $USERDATA['secret']);
?>

  <form action="user_handler.php" method="post">
    <div class="user_row">
      <div class="user_col1">Felhaszn�l�n�v:</div>
      <div class="user_col2"><input value="<?php echo esc($USERDATA['username'], 2); ?>" type="text" maxlength="32" name="username" class="input user_inputfield<?php if($USERDATA['username'] != '') { echo ' input_disabled'; } ?>"<?php if($USERDATA['username'] != '') { echo ' disabled'; } ?>/></div>
    </div>
    <div class="user_row">
      <div class="user_col1">�j jelsz�:</div>
      <div class="user_col2"><input type="text" maxlength="32" name="password" class="input user_inputfield"/></div>
    </div>
    <div class="user_row">
      <div class="user_col1">E-Mail c�m:</div>
      <div class="user_col2"><input value="<?php echo esc($USERDATA['email'], 2); ?>" type="text" name="email" maxlength="32" class="input user_inputfield"/></div>
    </div>
    <div class="user_row">
      <div class="user_col1">�rtes�t�s rendszer �zenet �rkez�s�r�l:</div>
      <div class="user_col2"><input value="1" type="checkbox" name="notif0" class="input user_checkbox"<?php echo (rights($USERDATA, 0, 'notif') == 0) ? '' : ' checked'; ?>/></div>
    </div>
    <div class="user_row">
      <div class="user_col1">�rtes�t�s haszn�lt accountokkal kapcsolatos �zenet �rkez�s�r�l:</div>
      <div class="user_col2"><input value="1" type="checkbox" name="notif1" class="input user_checkbox"<?php echo (rights($USERDATA, 1, 'notif') == 0) ? '' : ' checked'; ?>/></div>
    </div>
    <div class="user_row">
      <div class="user_col1">�rtes�t�s priv�t �zenet �rkez�s�r�l:</div>
      <div class="user_col2"><input value="1" type="checkbox" name="notif2" class="input user_checkbox"<?php echo (rights($USERDATA, 2, 'notif') == 0) ? '' : ' checked'; ?>/></div>
    </div>
    <div class="user_row">
      <div class="user_col1">Saj�t megh�v�k sz�ma:</div>
      <div class="user_col2"><input value="<?php echo esc($USERDATA['invites'], 2); ?>" type="text" name="invites" maxlength="9" class="input user_inputfield_9char"/>db</div>
    </div>
    <?php if($full_admin) { ?>
    <div class="user_row">
      <div class="user_col1">Rang:</div>
      <div class="user_col2">
        <select name="user_class" class="input user_select">
          <option value="0"<?php echo $class_sel[0]; ?>><?php echo $class[0]; ?></option>
          <?php if(rights($USER, 0) > 0) { ?><option value="1"<?php echo $class_sel[1]; ?>><?php echo $class[1]; ?></option><?php } ?>
          <?php if(rights($USER, 0) > 1) { ?><option value="2"<?php echo $class_sel[2]; ?>><?php echo $class[2]; ?></option><?php } ?>
        </select>
      </div>
    </div>
    <div class="user_row">
      <div class="user_col1">St�tusz:</div>
      <div class="user_col2">
        <select name="status" class="input user_select">
          <option value="0"<?php echo $status_sel[0]; ?>><?php echo $status[0]; ?></option>
          <option value="1"<?php echo $status_sel[1]; ?>><?php echo $status[1]; ?></option>
        </select>
      </div>
    </div>
    <div class="user_row">
      <div class="user_col1">Felhaszn�l�i jogok:</div>
      <div class="user_col2">
        <select name="user_rights" class="input user_select">
          <option value="0"<?php echo $user_rights_sel[0]; ?>>0</option>
          <option value="1"<?php echo $user_rights_sel[1]; ?>>1</option>
          <option value="2"<?php echo $user_rights_sel[2]; ?>>2</option>
          <option value="3"<?php echo $user_rights_sel[3]; ?>>3</option>
          <option value="4"<?php echo $user_rights_sel[4]; ?>>4</option>
          <option value="5"<?php echo $user_rights_sel[5]; ?>>5</option>
          <option value="6"<?php echo $user_rights_sel[6]; ?>>6</option>
        </select>
      </div>
    </div>
    <?php if($full_log) { ?>
    <div class="user_row">
      <div class="user_col1">Log jogok:</div>
      <div class="user_col2">
        <select name="log_rights" class="input user_select">
          <option value="0"<?php echo $log_rights_sel[0]; ?>>0</option>
          <option value="1"<?php echo $log_rights_sel[1]; ?>>1</option>
          <option value="2"<?php echo $log_rights_sel[2]; ?>>2</option>
          <option value="3"<?php echo $log_rights_sel[3]; ?>>3</option>
          <option value="4"<?php echo $log_rights_sel[4]; ?>>4</option>
        </select>
      </div>
    </div>
    <?php } ?>
    <div class="user_row">
      <div class="user_col1">Meger�s�t� k�d:</div>
      <div class="user_col2"><input value="<?php echo esc($USERDATA['verif'], 2); ?>" type="text" name="verif" maxlength="32" class="input user_inputfield"/></div>
    </div>
    <div class="user_row">
      <div class="user_col1">Adatcsere k�r�s:</div>
      <div class="user_col2">
        <select name="secret0" class="input user_select">
          <option value="false"<?php echo ($secret[0] == '') ? ' selected' : ''; ?>>Nincs</option>
          <option value="passw"<?php echo ($secret[0] == 'passw') ? ' selected' : ''; ?>>Jelsz� csere</option>
          <?php /*<option value="email"<?php echo ($secret[0] == 'email') ? ' selected' : ''; ?>>E-Mail csere</option>*/ ?>
        </select>
        <input value="<?php echo _date($secret[1], 'Y'); ?>" type="text" name="secret1_year" maxlength="4" class="input user_inputfield_4char"/>.<input value="<?php echo _date($secret[1], 'm'); ?>" type="text" name="secret1_month" maxlength="2" class="input user_inputfield_2char"/>.<input value="<?php echo _date($secret[1], 'd'); ?>" type="text" name="secret1_day" maxlength="2" class="input user_inputfield_2char"/> - <input value="<?php echo _date($secret[1], 'H'); ?>" type="text" name="secret1_hour" maxlength="2" class="input user_inputfield_2char"/>:<input value="<?php echo _date($secret[1], 'i'); ?>" type="text" name="secret1_minute" maxlength="2" class="input user_inputfield_2char"/>:<input value="<?php echo _date($secret[1], 's'); ?>" type="text" name="secret1_second" maxlength="2" class="input user_inputfield_2char"/>
        <br/>uID: <?php echo $USERDATA['uid']; ?>
        <br/>Meger�s�t� k�d: <input value="<?php echo esc($secret[2], 2); ?>" type="text" name="secret2" maxlength="32" class="input user_secret_verif"/>
      </div>
    </div>
    <?php } ?>
<?php /*
    <div class="user_row">
      <div class="user_col1">Saj�t accountok:</div>
      <div class="user_col2"></div>
    </div>
*/ ?>
    <div class="user_row">
      <div class="user_col1">Admin komment:</div>
      <div class="user_col2"><textarea name="admin_comm" class="input user_textarea"><?php echo esc($USERDATA['admin_comm'], 2); ?></textarea></div>
    </div>
    <div class="user_row">
      <input type="hidden" value="<?php echo $USERDATA['uid']; ?>" name="uid"/>
      <input type="submit" class="button user_button" value="Mehet"/>
    </div>
  </form>

<?php
  if($_GET['error'] == 1) { echo '<br/><br/><b class="user_error">A felhaszn�l�n�v minimum 3, maximum 32 karakter hossz� legyen, csak kisbet�, nagybet� �s sz�m szerepelhet benne az angol �b�c�-b�l!</b>'; }
  elseif($_GET['error'] == 2) { echo '<br/><br/><b class="user_error">Ez a felhaszn�l�n�v foglalt!</b>'; }
  elseif($_GET['error'] == 3) { echo '<br/><br/><b class="user_error">Az �j jelsz� minimum 6, maximum 32 karakter hossz� legyen, szerepeljen benne bet� �s sz�m, �s a jelsz� ne tartalmazza a felhaszn�l�nevet, vagy a felhaszn�l�n�v a jelsz�t!</b>'; }
  //elseif($_GET['error'] == 3) { echo '<br/><br/><b class="user_error">Az �j jelsz� minimum 6, maximum 32 karakter hossz� legyen, szerepeljen benne kisbet�, nagybet�, �s sz�m, �s a jelsz� ne tartalmazza a felhaszn�l�nevet, vagy a felhaszn�l�n�v a jelsz�t!</b>'; }
  //elseif($_GET['error'] == 4) { echo '<br/><br/><b class="user_error">Ez az e-mail c�m foglalt!</b>'; }
  elseif($_GET['error'] == 5) { echo '<br/><br/><b class="user_error">Val�s e-mail c�met adj meg!</b>'; }
  elseif($_GET['error'] == 6) { echo '<br/><br/><b class="user_error">A meger�s�t� k�d minimum 8, maximum 32 karakter hossz� legyen, �s csak als�von�s (<b class="user_important">_</b>), sz�mok, �s az angol �b�c� bet�i szerepelhetnek benne!</b>'; }
  elseif($_GET['error'] == 7) { echo '<br/><br/><b class="user_error">Az adatcsere k�r�s id�pontja �rv�nytelen �rt�ket tartalmaz.</b>'; }
  elseif($_GET['error'] == 8) { echo '<br/><br/><b class="user_error">Az adatcsere meger�s�t� k�d minimum 8, maximum 32 karakter hossz� legyen, �s csak als�von�s (<b class="user_important">_</b>), sz�mok, �s az angol �b�c� bet�i szerepelhetnek benne!</b>'; }
  elseif($_GET['error'] == 9) { echo '<br/><br/><b class="user_error">Legal�bb a meger�s�t� k�dot, vagy a felhaszn�l�n�v / jelsz� p�rost add meg!</b>'; }

      box(1);
    }

    $INVITED = getInvited($USERDATA['uid']);
    if(rights($USER, 1) > 3 && $INVITED !== false) {
      box(0, 'Megh�vottai');

      echo '<div class="invited_head">'
      .'<div class="invited_col1">Megh�v�s / Regisztr�l�s</div>'
      .'<div class="invited_col2">uID</div>'
      .'<div class="invited_col3">Felhaszn�l�n�v</div>'
      .'<div class="invited_col4">Meger�s�t� k�d</div>'
      .'</div>';

      for($i = 0; $i < count($INVITED); $i++) {
        $alt_class = ($i % 2) == 0 ? '' : ' invited_altrow';
        $invited_user = $INVITED[$i];

        $the_verif = ($invited_user['last_login'] == 0 || rights($USER, 1) > 3) ? $invited_user['verif'] : '';
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
/*
    elseif(rights($USER, 1) > 2) {
      box(0, 'Hiba');
      echo '<b class="invite_error">Nincsenek megh�vottai.</b>';
      box(1);
    }
*/
  }
}
else {
  box(0, 'Hiba');
  echo '<b class="user_error">Nincs jogod megtekinteni a felhaszn�l�i adatlapot.</b>';
  box(1);
}

foot();
?>