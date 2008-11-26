<p id="uzenetek">
<?php
$rows = mysql_num_rows($messages);
while($_M = mysql_fetch_array($messages, MYSQL_ASSOC)) {
  $rows--;
  //$_M['message'] = str_replace(array('&#369;', '&#337;', '&#368;','&#336;'), array('ű','ő','Ű','Ő'), $_M['message']);//QFix
  $_M['message'] = str_replace('  ', ' &nbsp;', nl2br(htmlentities($_M['message'], ENT_QUOTES, 'UTF-8')));

  if($_M['usid_rank'] > 5) { $color = ' style="color: orangered;"'; }
  elseif($_M['usid_rank'] > 3) { $color = ' style="color: royalblue;"'; }
  elseif($_M['usid_rank'] > 1) { $color = ' style="color: forestgreen;"'; }
  else { $color = ''; }


  $del = ($_USER['rank'] > 1) ? ' <span class="del">[<a href="?del='.$_M['meid'].'">x</a>]</span>' : '';
  $class = ($rows % 2) ? 'mind masik' : 'mind';


  echo '<p class="'.$class.'">'
  .'<span class="name" title="#'.$_M['meid'].'"'.$color.'>'.$_M['username'].'</span> <span class="time">('.date('Y.m.d - H:i:s', $_M['sent']).')</span>'.$del.'<br/>'
  .$_M['message'].'</p>';

}
?>
</p>