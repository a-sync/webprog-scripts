<?php
if(file_exists('installed.txt')) { die('die!die!die! - töröld az `installed.txt` fájlt és újra próbálkozhatsz.'); }

require('config.php');

if(isset($_P['admin_reg'])) {

  if(!preg_match('/^[A-Za-z0-9]{3,32}$/', $_P['username'])) {
    $error .= ' Érvénytelen felhasználónév.';
  }
  if(!preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $_P['email'])) {
    $error .= ' Érvénytelen email cím.';
  }
  if(strlen($_P['password1']) > 32 || strlen($_P['password1']) < 3 || $_P['password1'] != $_P['password2']) {
    $error .= ' Érvénytelen jelszó.';
  }

  $check = @mysql_fetch_array(@mysql_query("SELECT * FROM `users` LIMIT 1"), MYSQL_ASSOC);

  if($check) {
    $_A = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `username` = '{$_P['username']}' OR `email` = '{$_P['email']}'"), MYSQL_ASSOC);
    if($_A != false) { $error .= ' A felhasználónév, vagy az email már foglalt.'; }
  }

  if($error == '') {
    if(!$check) {
      $res1 = mysql_query("CREATE TABLE `messages` (`meid` int(10) unsigned NOT NULL auto_increment, `usid` int(10) unsigned NOT NULL, `username` tinytext collate latin2_hungarian_ci NOT NULL, `message` mediumtext collate latin2_hungarian_ci NOT NULL, `sent` int(10) unsigned NOT NULL, `usid_rank` tinyint(1) unsigned NOT NULL, PRIMARY KEY (`meid`)) ENGINE=MyISAM  DEFAULT CHARSET=latin2 COLLATE=latin2_hungarian_ci AUTO_INCREMENT=1;");
      $res2 = mysql_query("CREATE TABLE `users` (`usid` int(10) unsigned NOT NULL auto_increment, `username` tinytext collate latin2_hungarian_ci NOT NULL, `passhash` tinytext collate latin2_hungarian_ci NOT NULL, `email` tinytext collate latin2_hungarian_ci NOT NULL, `rank` tinyint(1) unsigned NOT NULL, `confirm` tinytext collate latin2_hungarian_ci NOT NULL, `registered` int(10) unsigned NOT NULL, PRIMARY KEY (`usid`)) ENGINE=MyISAM  DEFAULT CHARSET=latin2 COLLATE=latin2_hungarian_ci AUTO_INCREMENT=1;");
    }

    $passhash = ident($_P['username'], $_P['password1']);
    $time = time();
    $res3 = mysql_query("INSERT INTO `users` (`usid`, `username`, `passhash`, `email`, `rank`, `confirm`, `registered`) VALUES (NULL, '{$_P['username']}', '$passhash', '{$_P['email']}', 6, '', '$time');");

    if(!$check && !$res1 && !$res2) { $error .= ' A felhasználók és üzenetek táblák létrehozása sikertelen volt! Lehet, hogy az sql kérésben hiba van, vagy a felhasználók tábla már létezik, csak üres (ez esetben töröld a `users` táblát a megfelelő adatbázisban)!'; }
    elseif(!$check) { $error .= ' A felhasználók és üzenetek táblák sikeresen létrehozva.'; }
    if($res3) { $error .= '<br/>Az admin sikeresen hozzá lett adva az adatbázishoz.'; }
    else { $error .= '</br>Az admint nem sikerült hozzáadni az adatbázishoz! Lehet, hogy hiba van az sql kérésben, vagy a megadott adatokban!'; }
    if(!touch('installed.txt')) { $error .= '</br>Az `installed.txt` nevű fájl nem hozható létre, ezért hozz létre magad egy `installed.txt` nevű fájlt, vagy az `install.php` fájlt feltétlen töröld le, és csak akkor másold fel újra, ha újabb admint akarsz a rendszerhez adni!'; }
  }

}
?>

<html>
<head>
</head>
<body>
<?php if($error != '') { echo '<font color="red">'.$error.'</font>'; } ?>

<?php if(!file_exists('installed.txt')) { ?>
<form action="" method="post">
<h3>Admin regisztrálása, sql táblák létrehozása</h3>
Felhasználónév: <input type="text" name="username"/> (min. 3, max. 32 karakter, a-z, A-Z, 0-9)
<br/>
Jelszó: <input type="text" name="password1"/> (min. 3, max. 32 karakter)
<br/>
Jelszó újra: <input type="text" name="password2"/>
<br/>
Email: <input type="text" name="email"/>
<br/>
<input type="submit" name="admin_reg" value="Adminisztrátor regisztrálása!">
</form>
<?php } else { echo '<br/><br/>Ha újabb admint akarsz a rendszerhez adni, előbb töröld az `installed.txt` nevű fájlt!'; } ?>

</body>
</html>