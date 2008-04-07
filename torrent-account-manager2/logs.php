<?php
/* LOGS */

include('functions.php');

/*$m_z = mt_rand(100, 200);//debug
for($z = 0; $z < 132; $z++) {//debug
  $m_y = mt_rand(3, 8);
  for($y = 0; $y < $m_y; $y++) { $m = rand_str(mt_rand(2, 8), mt_rand(1, 3)).' '.$m; }
  logger(mt_rand(0, mt_rand(1, mt_rand(1, mt_rand(1, 2)))), mt_rand(0, 4), $m, mt_rand(0, mt_rand(1, mt_rand(1, mt_rand(1, mt_rand(1, 2))))));
}
die($m_z.'db random logbejegyzés hozzáadva!');//debug*/

$USER = checkLogin();

head();

if(rights($USER, 2) > 0) {
  $full_logs = (rights($USER, 2) > 1) ? true : false;

  $status[0] = 'Rendben';
  $status[1] = 'Ellenõrizetlen';
  $status[2] = 'Kiemelt';
  $type[0] = 'SQL';
  $type[1] = 'User';
  $type[2] = 'Mail';

  //értékek ellenõrzése és felvétele
  $w_t = (preg_match('/^[0-2]{1}$/', $_GET['t'])) ? esc($_GET['t'], 1) : '-';
  $w_c = (preg_match('/^[0-4]{1}$/', $_GET['c'])) ? esc($_GET['c'], 1) : '-';
  $w_s = (preg_match('/^[0-2]{1}$/', $_GET['s'])) ? esc($_GET['s'], 1) : '-';

  $o_d = (preg_match('/^[0-1]{1}$/', $_GET['d'])) ? esc($_GET['d'], 1) : 0;
  $o_o = (preg_match('/^[0-3]{1}$/', $_GET['o'])) ? esc($_GET['o'], 1) : 0;
  $p_p = (preg_match('/^[0-9]$/', $_GET['p'])) ? esc($_GET['p'], 1) : 0;

  //szûrõ függvény elõállítása
  $where = " WHERE `lid` != ''";
  if(!$full_logs) { $where .= " AND `class` < 3"; }
  if($w_t !== '-') { $where .= " AND `type` = '$w_t'"; }
  if($w_c !== '-') { $where .= " AND `class` = '$w_c'"; }
  if($w_s !== '-') { $where .= " AND `status` = '$w_s'"; }
  
  //rendezõ függvény elõállítása
  $order = " ORDER BY";
  if($o_d == 0) { $o_dir = "DESC"; }
  else { $o_dir = "ASC"; }

  //$order .= " `status` DESC,";//default //debug
  if($o_o == 1) { $order .= " `type` ".$o_dir.","; }
  elseif($o_o == 2) { $order .= " `class` ".$o_dir.","; }
  elseif($o_o == 3) { $order .= " `status` ".$o_dir.","; }
  $order .= " `log_time` ".$o_dir;


  //lapozó függvény, limit érték elõállítása
  $logs_perpage = 100;//users per page
  $p_from = ($p_p * $logs_perpage);
  $limit = " LIMIT $p_from, $logs_perpage";

  //kérés összeillesztése, végrehajtás
  $query0 = "SELECT `lid`, `type`, `class`, `message`, `status`, `log_time` FROM `tam_logs`".$where.$order.$limit;
//echo($query0.'<br/>');//debug
  $result0 = sql_query($query0);
  $resultnum0 = mysql_num_rows($result0);

  $o_d_inv = ($o_d == 0) ? 1 : 0;
  $logs_page = '&p='.$p_p;
  $search_vars = '&t='.$w_t.'&c='.$w_c.'&s='.$w_s;

  $query1 = "SELECT count(`lid`) FROM `tam_logs`".$where;
//echo($query1.'<br/>');//debug
  $result1 = sql_query($query1);
  $logs_num = mysql_fetch_array($result1, MYSQL_ASSOC);
  $logs_num = $logs_num['count(`lid`)'];

  $pages_num = number_format(($logs_num / $logs_perpage), 0, '', '');
  if(($pages_num * $logs_perpage) < $logs_num) { $pages_num++; }

  $pager = '';
  for($j = 0; $pages_num > $j; $j++) {
    $list_start = (($j * $logs_perpage) + 1);
    $list_end = (($j + 1) * $logs_perpage);
    if($list_end > $logs_num) { $list_end = $logs_num; }

    if($p_p == $j) { $pager .= $list_start.' - '.$list_end.' '; }
    else { $pager .= '<a class="pager" href="logs.php?o='.$o_o.'&p='.$j.'&d='.$o_d.$search_vars.'">'.$list_start.' - '.$list_end.'</a> '; }
  }

  box(0, 'Logok');
  echo $pager;
  box(1);
  box(0);

  $filter = ' &nbsp; - &nbsp; Típus: (<a href="logs.php?o='.$o_o.'&t=0&c='.$w_c.'&s='.$w_s.'">0</a> <a href="logs.php?o='.$o_o.'&t=1&c='.$w_c.'&s='.$w_s.'">1</a> <a href="logs.php?o='.$o_o.'&t=2&c='.$w_c.'&s='.$w_s.'">2</a>)';

  $filter .= ' / Rang: (<a href="logs.php?o='.$o_o.'&t='.$w_t.'&c=0&s='.$w_s.'">0</a> <a href="logs.php?o='.$o_o.'&t='.$w_t.'&c=1&s='.$w_s.'">1</a> <a href="logs.php?o='.$o_o.'&t='.$w_t.'&c=2&s='.$w_s.'">2</a>';
  if($full_logs) { $filter .= ' <a href="logs.php?o='.$o_o.'&t='.$w_t.'&c=3&s='.$w_s.'">3</a> <a href="logs.php?o='.$o_o.'&t='.$w_t.'&c=4&s='.$w_s.'">4</a>'; }
  $filter .= ')';

  $filter .= ' / Státusz: (<a href="logs.php?o='.$o_o.'&t='.$w_t.'&c='.$w_c.'&s=0">0</a> <a href="logs.php?o='.$o_o.'&t='.$w_t.'&c='.$w_c.'&s=1">1</a> <a href="logs.php?o='.$o_o.'&t='.$w_t.'&c='.$w_c.'&s=2">2</a>)';

  echo '<div class="logs_head">'
  .'<div class="logs_col1" title="(lID)"><a href="logs.php?o=0">Logolás ideje</a> <a href="logs.php?o=0'.$logs_page.'&d='.$o_d_inv.$search_vars.'">/\ \/</a></div>'
  .'<div class="logs_col2" title="(Rang)"><a href="logs.php?o=1">Típus</a> <a href="logs.php?o=1'.$logs_page.'&d='.$o_d_inv.$search_vars.'">/\ \/</a></div>'
  .'<div class="logs_col3">Szöveg '.$filter.'</div>'
  .'<div class="logs_col4"><a href="logs.php?o=2">Státusz</a> <a href="logs.php?o=2'.$logs_page.'&d='.$o_d_inv.$search_vars.'">/\ \/</a></div>'
  .'</div>';

  for($i = 0; $i < $resultnum0; $i++) {
    $alt_class = ($i % 2) == 0 ? '' : ' logs_altrow';
    $LOGDATA = mysql_fetch_assoc($result0);

    echo '<div class="logs_row'.$alt_class.'">'
    .'<div class="logs_col1" title="(lID: '.$LOGDATA['lid'].')"><a href="log.php?lid='.$LOGDATA['lid'].'">'._date($LOGDATA['log_time']).'</a></div>'
    .'<div class="logs_col2" title="(Rang: '.$LOGDATA['class'].')">'.$type[$LOGDATA['type']].'</div>'
    //.'<div class="logs_col3">'.$LOGDATA['message'].'</div>'
    .'<div class="logs_col3">'.cutString($LOGDATA['message'], 70).'</div>'
    .'<div class="logs_col4 logs_status'.$LOGDATA['status'].'">'.$status[$LOGDATA['status']].' <span class="logs_class'.$LOGDATA['class'].'" title="(Rang: '.$LOGDATA['class'].')">X</span></div>'
    .'</div>';
  }

  box(1);
  box(0);
  echo $pager;
  box(1);
}
else {
  box(0, 'Hiba');
  echo '<b class="logs_error">Nincs jogod megtekinteni a logokat.</b>';
  box(1);
}

foot();
?>