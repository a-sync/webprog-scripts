<?php
/* USERS */

include('functions.php');

$USER = checkLogin();

head();

if(rights($USER, 1) > 2) {
  $full_details = (rights($USER, 1) > 3) ? true : false;
  $status[0] = 'Kitiltott';
  $status[1] = 'Engedélyezett';
  $class[0] = 'Felhasználó';
  $class[1] = 'Moderátor';
  $class[2] = 'Adminisztrátor';

  //értékek ellenõrzése és felvétele
  $w_u = (strlen(esc($_GET['u'], 1)) > 2) ? esc($_GET['u'], 1) : false;
  $w_s = (preg_match('/^[0-1]{1}$/', $_GET['s'])) ? esc($_GET['s'], 1) : '-';
  $w_c = (preg_match('/^[0-2]{1}$/', $_GET['c'])) ? esc($_GET['c'], 1) : '-';
  if($full_details) {
    $w_e = (strlen(esc($_GET['e'], 1)) > 2) ? esc($_GET['e'], 1) : false;
    $w_ur = (preg_match('/^[0-6]{1}$/', $_GET['ur'])) ? esc($_GET['ur'], 1) : '-';
    $w_i = (preg_match('/^[0-1]{1}$/', $_GET['i'])) ? esc($_GET['i'], 1) : '-';
    $w_ip = (strlen(esc($_GET['ip'], 1)) > 2) ? esc($_GET['ip'], 1) : false;
    $w_v = (preg_match('/^[0-1]{1}$/', $_GET['v'])) ? esc($_GET['v'], 1) : '-';
  }
  else {
    $w_e = false;
    $w_ur = '-';
    $w_i = '-';
    $w_ip = false;
    $w_v = 0;
  }
  $o_d = (preg_match('/^[0-1]{1}$/', $_GET['d'])) ? esc($_GET['d'], 1) : 0;
  $o_o = (preg_match('/^[0-2]{1}$/', $_GET['o'])) ? esc($_GET['o'], 1) : 0;

  $p_p = (preg_match('/^[0-9]$/', $_GET['p'])) ? esc($_GET['p'], 1) : 0;

  //hiányzó értékek alapbeállításai
  if(!isset($_GET['s'])) { $w_s = 1; }
  if(!isset($_GET['v'])) { $w_v = 0; }

  //szûrõ függvény elõállítása
  $where = " WHERE `uid` != ''";//alapérték
  if($w_u !== false) { $where .= " AND `username` LIKE '%".wc($w_u)."%'"; }
  if($w_s !== '-') { $where .= " AND `status` = '$w_s'"; }
  if($w_c !== '-') { $where .= " AND `rights` LIKE '$w_c|%'"; }

  if($w_e !== false) { $where .= " AND `email` LIKE '%".wc($w_e)."%'"; }
  if($w_ur !== '-') { $where .= " AND `rights` LIKE '_|$w_ur|%'"; }
  if($w_i !== '-') {
    if($w_i == 0) { $where .= " AND `invites` = '0'"; }
    else { $where .= " AND `invites` != '0'"; }
  }
  if($w_ip !== false) { $where .= " AND `last_ips` LIKE '%".wc($w_ip)."%'"; }
  if($w_v !== '-') {
    if($w_v == 0) { $where .= " AND `verif` = ''"; }
    else { $where .= " AND `verif` != ''"; }
  }

  //rendezõ függvény elõállítása
  $order = " ORDER BY";
  if($o_d == 0) { $o_dir = "ASC"; }
  else { $o_dir = "DESC"; }
  if($o_o == 1) { $order .= " `last_login` ".$o_dir.","; }
  elseif($o_o == 2) { $order .= " `reg_time` ".$o_dir.","; }
  $order .= " `username` ".$o_dir;

  //lapozó függvény, limit érték elõállítása
  $users_perpage = 50;//users per page
  $p_from = ($p_p * $users_perpage);
  $limit = " LIMIT $p_from, $users_perpage";

  //kérés összeillesztése, végrehajtás
  $query0 = "SELECT `uid`, `username`, `email`, `status`, `verif`, `rights`, `invites`, `inviter`, `reg_time`, `last_login`, `last_ips` FROM `tam_users`".$where.$order.$limit;
//echo($query0.'<br/>');//debug
  $result0 = sql_query($query0);
  $resultnum0 = mysql_num_rows($result0);

  box(0, 'Felhasználó keresése');

  $s_sel[$w_s] = ' selected';
  $c_sel[$w_c] = ' selected';
  if($full_details) {
    $ur_sel[$w_ur] = ' selected';
    $i_sel[$w_i] = ' selected';
    $v_sel[$w_v] = ' selected';
  }
?>

  <form action="users.php" method="get">
    <div class="users_search_row">
      <div class="users_search_col1">
        Felhasználónév:
        <input type="text" maxlength="32" name="u" class="input users_search_inputfield" value="<?php echo esc($_GET['u'], 2); ?>"/>
      </div>
      <div class="users_search_col2">
        Státusz:
        <select name="s" class="input users_search_select">
          <option value=""<?php echo $s_sel['-']; ?>>*</option>
          <option value="0"<?php echo $s_sel[0]; ?>><?php echo $status[0]; ?></option>
          <option value="1"<?php echo $s_sel[1]; ?>><?php echo $status[1]; ?></option>
        </select>
      </div>
      <div class="users_search_col3">
        Rang:
        <select name="c" class="input users_search_select">
          <option value=""<?php echo $c_sel['-']; ?>>*</option>
          <option value="0"<?php echo $c_sel[0]; ?>><?php echo $class[0]; ?></option>
          <option value="1"<?php echo $c_sel[1]; ?>><?php echo $class[1]; ?></option>
          <option value="2"<?php echo $c_sel[2]; ?>><?php echo $class[2]; ?></option>
        </select>
      </div>
    </div>
<?php if($full_details) { ?>
    <div class="users_search_row">
      <div class="users_search_col1">
        E-Mail cím:
        <input type="text" maxlength="200" name="e" class="input users_search_inputfield" value="<?php echo esc($_GET['e'], 2); ?>"/>
      </div>
      <div class="users_search_col2">
        Felhasználói jogok:
        <select name="ur" class="input users_search_select">
          <option value=""<?php echo $ur_sel['-']; ?>>*</option>
          <option value="0"<?php echo $ur_sel[0]; ?>>0</option>
          <option value="1"<?php echo $ur_sel[1]; ?>>1</option>
          <option value="2"<?php echo $ur_sel[2]; ?>>2</option>
          <option value="3"<?php echo $ur_sel[3]; ?>>3</option>
          <option value="4"<?php echo $ur_sel[4]; ?>>4</option>
          <option value="5"<?php echo $ur_sel[5]; ?>>5</option>
          <option value="6"<?php echo $ur_sel[6]; ?>>6</option>
        </select>
      </div>
      <div class="users_search_col3">
        Meghívók:
        <select name="i" class="input users_search_select">
          <option value=""<?php echo $i_sel['-']; ?>>*</option>
          <option value="0"<?php echo $i_sel[0]; ?>>Nincs</option>
          <option value="1"<?php echo $i_sel[1]; ?>>Van</option>
        </select>
      </div>
    </div>
    <div class="users_search_row">
      <div class="users_search_col1">
        Utolsó IP címek:
        <input type="text" maxlength="250" name="ip" class="input users_search_inputfield" value="<?php echo esc($_GET['ip'], 2); ?>"/>
      </div>
      <div class="users_search_col2">
        Megerõsített:
        <select name="v" class="input users_search_select">
          <option value=""<?php echo $v_sel['-']; ?>>*</option>
          <option value="0"<?php echo $v_sel[0]; ?>>Igen</option>
          <option value="1"<?php echo $v_sel[1]; ?>>Nem</option>
        </select>
      </div>
    </div>
<?php } ?>
    <div class="users_search_row">
      <div class="users_search_important">
        (a szövegmezõkben legalább 3 karakter szerepeljen) (* = akármennyi, akármilyen karakter; ? = 1db akármilyen karakter)
      </div>
    </div>
    <div class="users_search_row">
      <input type="submit" class="button users_search_button" value="Keresés"/>
    </div>
  </form>

<?php
  box(1);

  $o_d_inv = ($o_d == 0) ? 1 : 0;
  $users_page = '&p='.$p_p;
  $search_vars = '&u='.rawurlencode($w_u).'&s='.$w_s.'&c='.$w_c;
  if($full_details) { $search_vars .= '&e='.rawurlencode($w_e).'&ur='.$w_ur.'&i='.$w_i.'&ip='.rawurlencode($w_ip).'&v='.$w_v; }

  $query1 = "SELECT count(`uid`) FROM `tam_users`".$where;
//echo($query1.'<br/>');//debug
  $result1 = sql_query($query1);
  $users_num = mysql_fetch_array($result1, MYSQL_ASSOC);
  $users_num = $users_num['count(`uid`)'];

  $pages_num = number_format(($users_num / $users_perpage), 0, '', '');
  if(($pages_num * $users_perpage) < $users_num) { $pages_num++; }

  $pager = '';
  for($j = 0; $pages_num > $j; $j++) {
    $list_start = (($j * $users_perpage) + 1);
    $list_end = (($j + 1) * $users_perpage);
    if($list_end > $users_num) { $list_end = $users_num; }

    if($p_p == $j) { $pager .= $list_start.' - '.$list_end.' '; }
    else { $pager .= '<a class="pager" href="users.php?o='.$o_o.'&p='.$j.'&d='.$o_d.$search_vars.'">'.$list_start.' - '.$list_end.'</a> '; }
  }

  box(0, 'Felhasználók');
  echo $pager;
  box(1);
  box(0);

  if($full_details) {
    $h_email = ' title="(e-mail cím)"';
    $h_verif = ' title="(megerõsítõ kód)"';
    $h_rights = ' title="(jogok)"';
    $h_last_ip = ' title="(utolsó IP)"';
    $h_inviter = ' title="(meghívó küldõ uID-je)"';
  }
  else {
    $h_email = '';
    $h_verif = '';
    $h_rights = '';
    $h_last_ip = '';
    $h_inviter = '';
  }
  echo '<div class="users_head">'
  .'<div class="users_col1"'.$h_email.'>Felhasználónév <a href="users.php?o=0'.$users_page.'&d='.$o_d_inv.$search_vars.'">/\ \/</a></div>'
  .'<div class="users_col2"'.$h_verif.'>Státusz</div>'
  .'<div class="users_col3"'.$h_rights.'>Rang</div>'
  .'<div class="users_col4"'.$h_last_ip.'>Utolsó bejelentkezés <a href="users.php?o=1'.$users_page.'&d='.$o_d_inv.$search_vars.'">/\ \/</a></div>'
  .'<div class="users_col5"'.$h_inviter.'>Regisztráció <a href="users.php?o=2'.$users_page.'&d='.$o_d_inv.$search_vars.'">/\ \/</a></div>'
  .'</div>';

  for($i = 0; $i < $resultnum0; $i++) {
    $alt_class = ($i % 2) == 0 ? '' : ' users_altrow';
    $USERDATA = mysql_fetch_assoc($result0);

    $username = ($USERDATA['username'] == '') ? '(uID: '.$USERDATA['uid'].')' : $USERDATA['username'];
    if($full_details) {
      $email = ' title="('.$USERDATA['email'].')"';
      $verif = ' title="('.$USERDATA['verif'].')"';
      $rights = ' title="('.$USERDATA['rights'].')"';
      $last_ips = explode('|', $USERDATA['last_ips']);
      $last_ip = ' title="('.$last_ips[0].')"';
      $inviter = ' title="('.$USERDATA['inviter'].')"';
    }
    else {
      $email = '';
      $verif = '';
      $rights = '';
      $last_ip = '';
      $inviter = '';
    }
    $last_login = ($USERDATA['last_login'] == 0) ? '(nincs)' : _date($USERDATA['last_login']);

    echo '<div class="users_row'.$alt_class.'">'
    .'<div class="users_col1"'.$email.'><a href="user.php?uid='.$USERDATA['uid'].'">'.$username.'</a></div>'
    .'<div class="users_col2"'.$verif.'>'.$status[$USERDATA['status']].'</div>'
    .'<div class="users_col3"'.$rights.'>'.$class[rights($USERDATA, 0)].'</div>'
    .'<div class="users_col4"'.$last_ip.'>'.$last_login.'</div>'
    .'<div class="users_col5"'.$inviter.'>'._date($USERDATA['reg_time']).'</div>'
    .'</div>';
  }

  box(1);
  box(0);
  echo $pager;
  box(1);
}
else {
  box(0, 'Hiba');
  echo '<b class="users_error">Nincs jogod megtekinteni a felhasználólistát.</b>';
  box(1);
}

foot();
?>