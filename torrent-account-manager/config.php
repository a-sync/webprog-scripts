<?php
$tam_domain = '';
//if $tam_doman != $_SERVER['blabla'] (domain) { die(); }//logdie

$tam_pass_hash = '';

$tam_db_host = 'vector.extra.sql';
$tam_db_user = 'vector';
$tam_db_pass = 'passw';
$tam_db_name = 'vector';

$tam_url_redir = '';//oldal és egyéb kimenõ linkeket ezzel a domain-nel irányítsa át (pl.: anonym.to/)
$din_accdata = 'no';//nem(no), igen(yes) -- dinamikus account adatszerzés a lehetséges helyeken
$tam_invite = 2;//felh(0), mod(1), admin(2) -- küldhet meghívót/hozzáadhat felhasználót (nem lehet nagyobb user_full-nál)

$accowner_lock = 'yes';//ha van az adott oldalon saját accountja akkor csak szabad azonosítókulcsokra pályázhat
$maxaccountonsite = 1;//maximum felvehetõ szabad accountok száma egy oldalon
$maxpasskeyonsite = 3;//maximum felvehetõ szabad azonosítókulcsok száma egy oldalon

$mixedusers_lock = 'yes';//nem(no), igen(yes) -- szabad account állapotból használtba vált szabad azonosítókulcs helyett
$accountuser_lock = 1;//ha szabad accountként legalább ennyi felhasználója lesz, magasabb szintû állapotba vált
$passkeyuser_lock = 3;//ha szabad azonosítókulcsként legalább ennyi felhasználója lesz, magasabb szintû állapotba vált

//konkrét adatbázis adathoz (accountok|userek) csak admin fér hozzá módosításnál
//beállításoknál belõni, hogy az adott mod/add ne lehessen magasabb (rang) mint a full

$acc_full = 2;//felh(0), mod(1), admin(2) -- hozzáfér az emailhez, passkeyhez és a jelszavakhoz (módosításnál és acc adatlapnál), (magának is kioszthat accot), törölhet accot; védett acc adatait módosíthatja, és kiosztását is
$acc_mod = 2;//felh(0), mod(1), admin(2) -- módosíthat acc kiosztást és adatokat (mail,jelszavak, passkey, Védett babrálása és inaktív acc státuszváltása csak ha acc_full); láthatja az accountok adatlapját (ill. oldal adatlapon és user adatlapon az acclistát) inaktívra állíthat nem védett accot (megy log (és request ha nem acc_full))
$acc_add = 2;//felh(0), mod(1), admin(2) -- hozzáadhat accot (inaktív alapból ha nem acc_full)
$acc_user_users = 'uid';//nem(no), használók száma(number), használók uid-je linkel üzenet íráshoz(uid) (csak metszetben lévõ felhasználók írhatnak egymásnak) -- használt acc más használóiról infó
$acc_user_free = 'number';//nem(no), igen(yes), használók száma(number) -- a szabad állapotú accok adatlapját láthatják a felhasználók, látják a szabad accok listáját (+infó a használók számáról)

$site_full = 2;//felh(0), mod(1), admin(2) -- hozzáfér a megkötés kalkulátorhoz, törölhet oldalt
$site_mod = 2;//felh(0), mod(1), admin(2) -- módosíthatja az oldal adatait (megkötés kalkulátort csak ha site_full)
$site_add = 2;//felh(0), mod(1), admin(2) -- hozzáadhat oldalt (wildcardokkal)

$user_full = 2;//felh(0), mod(1), admin(2) -- hozzáfér jelszóhoz, emailhez, törölhet felhasználót,hozzáadhat felhasználót
$user_mod = 2;//felh(0), mod(1), admin(2) -- módosíthatja a felhasználó adatait, láthatja az adatlapot (user_full nélkül csak a maradékot)(ha user add, láthatja és módosíthatja a verif-et is)
$user_add = $user_mod;//felh(0), user_mod($user_mod), user_full($user_full) -- aktiválhat felhasználót, láthatja és módosíthatja a verif-et (ha nulla, akkor a felhasználók magukat is aktiválhatják)
//add usernél ha full akkor hozzáadhat felhasználót minden adattal és elküldheti meghívóként az adatokat ha akarja (ha nincs név/jelszó akkor aktivációs kulcs kell feltétlen)
//add usernél ha tam_invite vagy nagyobb és mod és nem full, küldhet meghívót (fullon kívül mindent megadhat és verif-et is ha user_add)
//add usernél ha tam_invite vagy nagyobb és nem mod és nem full, küldhet meghívót (csak emailt adhat meg)


?>