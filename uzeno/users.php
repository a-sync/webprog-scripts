<?php if(!$_USER || $_USER < 4) { die('die!die!die!-1'); } ?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php echo $head; ?>

<style type="text/css">
body {
  background-color: white;
}

input, td {
  font-size: 12px;
}

.osszes {
  margin: 0;
  padding: 0;
}
.valto {
  background-color: lavender;
}

.send {
  background-image: url("nyil.png");
  width: 20px;
  height: 20px;
  font-size: normal;
  line-height: 0px;
  overflow: hidden;
  margin: 0;
  padding: 0 0 0 20px;
  border: 1px solid darkgray;
  cursor: pointer;
}

#container {
width: 100%;
height: 100%;
overflow: auto;
}

</style>

<script type="text/javascript">
<!--
  function transFrom(e, name, maxlength, size) {

    if(e.innerHTML.indexOf('<input') == -1)
    {
      var input = '<input type="text" name="'+name+'"';
      input += ' value="'+e.innerHTML+'"';
      if(maxlength) input += ' maxlength="'+maxlength+'"';
      if(size) input += ' size="'+size+'"';
      input += ' />';
      
      e.innerHTML = input;
    }
  }

  function toggle(name, type, n)
  {
    if (!type) type = 0;
    if (type == 1) var e = document.getElementsByTagName(name)[n];
    else var e = document.getElementById(name);
    e.style.display = e.style.display == "none" ? "" : "none";
  }
//-->
</script>

</head>
<body>
<div id="container">

<a href="board.php">Üzenetek</a>
<br/>
<a href="javascript:toggle('uhelp');">Segítség!</a>
<br/>
<p id="uhelp" style="display: none; margin-top: 5px;">
  <?php if($_USER['rank'] > 5) { echo 'Módosítható: email, rang, megerősítőkód, jelszó. Kattints a módosítandó mezőbe az átíráshoz.<br/>A sorok végén lévő nyíllal lehet érvényesíteni a módosítást, egyszerre egy felhasználón.<br/><br/>'; } ?>
  Rangok: 0-1 sima felhasználó, 2-3 törölhet hozzászólásokat, 4-5 látja a felhasználókat és adataikat, 6 (tulaj) módosíthatja a felhasználók adatait
  <br/>
  <br/>
  A megerősítőkód az a kód amit emailben kap meg regisztrációkor a felhasználó, és annak segítségével tudja visszaigazolni regisztrációját.
  <br/>
  Amíg van, addig megerősítetlen a felhasználó, így könnyen kilehet valakit tiltani úgy hogy valami véletlenszerűt írunk bele.
</p>
<?php
  if($error) { echo '<font color="red">'.$error.'</font><br/>'; }

  if($_USER['rank'] > 5) { echo '<form action="board.php?order='.$_G['order'].'&users='.$_G['users'].'" method="post">'; }

  $order = ($_G['order'] == 'asc') ? 'order=desc&' : 'order=asc&';
?>

<table width="100%">
<tr bgcolor="darkorange" height="20">
<td>ID</td>
<td><a href="?<?php echo $order; ?>users=username">felhasználónév</a></td>
<td width="200"><a href="?<?php echo $order; ?>users=email">email</a></td>
<td width="30"><a href="?<?php echo $order; ?>users=rank">rang</a></td>
<td width="200"><a href="?<?php echo $order; ?>users=confirm">megerősítőkód</a></td>
<?php if($_USER['rank'] > 5) { echo '<td width="130">új jelszó</td>'; } ?>
<td width="160"><a href="?<?php echo $order; ?>users=registered">regisztrált</a></td>
</tr>

<?php
while($_U = mysql_fetch_array($users, MYSQL_ASSOC)) {
$class = ($class == 'osszes valto') ? 'osszes' : 'osszes valto';
//$mod = ($_USER['rank'] > 5) ? '<td><input maxlength="200" type="text" size="35" name="email-'.$_U['usid'].'" value="'.$_U['email'].'"/>'.'</td><td><input maxlength="1" type="text" size="1" name="rank-'.$_U['usid'].'" value="'.$_U['rank'].'"/></td><td><input size="35" maxlength="200" type="text" name="confirm-'.$_U['usid'].'" value="'.$_U['confirm'].'"/></td><td><input maxlength="32" type="text" name="pass-'.$_U['usid'].'" value=""/></td>' : '<td>'.$_U['email'].'</td><td>'.$_U['rank'].'</td><td>'.$_U['confirm'].'</td>';
$mod = ($_USER['rank'] > 5) ? '<td onclick="transFrom(this, \'email-'.$_U['usid'].'\', 200, 35);">'.$_U['email'].'</td><td onclick="transFrom(this, \'rank-'.$_U['usid'].'\', 1, 1);">'.$_U['rank'].'</td><td onclick="transFrom(this, \'confirm-'.$_U['usid'].'\', 200, 35);">'.$_U['confirm'].'</td><td><input maxlength="32" type="text" name="pass-'.$_U['usid'].'" value=""/></td>' : '<td>'.$_U['email'].'</td><td>'.$_U['rank'].'</td><td>'.$_U['confirm'].'</td>';

$send = ($_USER['rank'] > 5) ? '<td><input class="send" type="submit" name="user" value="'.$_U['usid'].'"/></td>': '';

echo '<tr class="'.$class.'">';
echo '<td>'.$_U['usid'].'</td><td>'.$_U['username'].'</td>'.$mod.'<td>'.date('Y.m.d - H:i:s', $_U['registered']).'</td>';
echo $send.'</tr>';



}
?>

</table>


<?php if($_USER['rank'] > 5) { echo '</form>';} ?>

</div>
</body>
</html>