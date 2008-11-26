
<form method="post" action="" id="login" style="">
<a href="javascript:toggle('login'); toggle('reg');">Regisztrácio</a> /
<a href="javascript:toggle('login'); toggle('newpass');">Jelszóemlékeztető</a>
<h3>Bejelentkezés</h3>

Felhasználónév: <input class="username" type="text" name="username" maxlength="32"/>
<br/>
Jelszó: <input class="pass" type="password" name="password1" maxlength="32"/>
<br/>
<input class="submit" type="submit" name="login" value="Bejelentkezés"/> 
<br/>
</form>

<form method="post" action="" id="reg" style="display: none;">
<a href="javascript:toggle('reg'); toggle('login');">Bejelentkezés</a> /
<a href="javascript:toggle('reg'); toggle('newpass');">Jelszóemlékeztető</a>
<h3>Regisztrácio</h3>

Felhasználónév: <input class="username" type="text" name="username" maxlength="32"/> (min. 3 max. 32 karakter a-z, A-Z, 0-9)
<br/>
Jelszó: <input class="pass" type="password" name="password1" maxlength="32"/> (min. 3 max. 32 karakter)
<br/>
Jelszó újra: <input class="re_pass" type="password" name="password2" maxlength="32"/>
<br/>
Email: <input class="email" type="text" name="email" maxlength="200"/>
<br/>
<input class="submit" type="submit" name="register" value="Regisztrálás"/>
<br/>
(megerősítő emailt küldünk)
</form>

<form method="post" action="" id="newpass" style="display: none;">
<a href="javascript:toggle('newpass'); toggle('login');">Bejelentkezés</a> /
<a href="javascript:toggle('newpass'); toggle('reg');">Regisztráció</a>
<h3>Jelszóemlékeztető</h3>

Felhasználónév: <input class="username" type="text" name="username" maxlength="32"/>
<br/>
Email: <input class="email" type="text" name="email" maxlength="200"/>
<br/>
<input class="submit" type="submit" name="newpass" value="Küldés"/>
<br/>
(az új jelszavadat emailben kapod meg)
</form>
