<?php if(!$_USER) { die('die!die!die!-1'); } ?>

<p id="options">
<a href="logout.php">Kijelentkezés</a>
<br/>
<?php
if ($_USER['rank'] > 3) {
echo '<a href="?users=registered">Felhasználók</a><br/>';
}
?>
<a href="javascript:toggle('modpass');">Jelszó csere</a>
<br/>
</p>

<form method="post" action="" id="modpass" style="display: none;">
Új jelszó: <input class="pass" type="password" name="newpass1"/> (min. 3 max. 32 karakter)
<br/>
Újra: <input class="re_pass" type="password" name="newpass2"/>
<br/>
<input class="submit" type="submit" value="Mehet"/>
</form>

<form method="post" action="" id="msg">
<?php echo $_USER['username'].':<br/>'; ?>
  <textarea name="text"></textarea>
  <br/>
  <input type="submit" name="send_message" value="Üzenet küldése!" />

</form>
