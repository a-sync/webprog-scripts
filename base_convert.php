<html><head></head><body>
<form method="post" action="">
  num: <input type="text" name="num" /><br/>
  base from: <input type="text" name="bf" value="10"/><br/>
  base to: <input type="text" name="bt" value="16"/><br/>
  <input type="submit" value="go" name="send"/>
</form>
<?php
if($_POST['send'] == 'go' && $_POST['num'] != '' && is_numeric($_POST['bf']) && is_numeric($_POST['bt'])) {
  echo '<pre align="left">'.base_convert(strip_tags($_POST['num']), $_POST['bf'], $_POST['bt']).'</pre>';
}
?>
</body></html>