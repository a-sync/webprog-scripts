<?php
$tam_domain = '';
//if $tam_doman != $_SERVER['blabla'] (domain) { die(); }//logdie

$tam_pass_hash = '';

$tam_db_host = 'vector.extra.sql';
$tam_db_user = 'vector';
$tam_db_pass = 'passw';
$tam_db_name = 'vector';

$tam_url_redir = '';//oldal �s egy�b kimen� linkeket ezzel a domain-nel ir�ny�tsa �t (pl.: anonym.to/)
$din_accdata = 'no';//nem(no), igen(yes) -- dinamikus account adatszerz�s a lehets�ges helyeken
$tam_invite = 2;//felh(0), mod(1), admin(2) -- k�ldhet megh�v�t/hozz�adhat felhaszn�l�t (nem lehet nagyobb user_full-n�l)

$accowner_lock = 'yes';//ha van az adott oldalon saj�t accountja akkor csak szabad azonos�t�kulcsokra p�ly�zhat
$maxaccountonsite = 1;//maximum felvehet� szabad accountok sz�ma egy oldalon
$maxpasskeyonsite = 3;//maximum felvehet� szabad azonos�t�kulcsok sz�ma egy oldalon

$mixedusers_lock = 'yes';//nem(no), igen(yes) -- szabad account �llapotb�l haszn�ltba v�lt szabad azonos�t�kulcs helyett
$accountuser_lock = 1;//ha szabad accountk�nt legal�bb ennyi felhaszn�l�ja lesz, magasabb szint� �llapotba v�lt
$passkeyuser_lock = 3;//ha szabad azonos�t�kulcsk�nt legal�bb ennyi felhaszn�l�ja lesz, magasabb szint� �llapotba v�lt

//konkr�t adatb�zis adathoz (accountok|userek) csak admin f�r hozz� m�dos�t�sn�l
//be�ll�t�sokn�l bel�ni, hogy az adott mod/add ne lehessen magasabb (rang) mint a full

$acc_full = 2;//felh(0), mod(1), admin(2) -- hozz�f�r az emailhez, passkeyhez �s a jelszavakhoz (m�dos�t�sn�l �s acc adatlapn�l), (mag�nak is kioszthat accot), t�r�lhet accot; v�dett acc adatait m�dos�thatja, �s kioszt�s�t is
$acc_mod = 2;//felh(0), mod(1), admin(2) -- m�dos�that acc kioszt�st �s adatokat (mail,jelszavak, passkey, V�dett babr�l�sa �s inakt�v acc st�tuszv�lt�sa csak ha acc_full); l�thatja az accountok adatlapj�t (ill. oldal adatlapon �s user adatlapon az acclist�t) inakt�vra �ll�that nem v�dett accot (megy log (�s request ha nem acc_full))
$acc_add = 2;//felh(0), mod(1), admin(2) -- hozz�adhat accot (inakt�v alapb�l ha nem acc_full)
$acc_user_users = 'uid';//nem(no), haszn�l�k sz�ma(number), haszn�l�k uid-je linkel �zenet �r�shoz(uid) (csak metszetben l�v� felhaszn�l�k �rhatnak egym�snak) -- haszn�lt acc m�s haszn�l�ir�l inf�
$acc_user_free = 'number';//nem(no), igen(yes), haszn�l�k sz�ma(number) -- a szabad �llapot� accok adatlapj�t l�thatj�k a felhaszn�l�k, l�tj�k a szabad accok list�j�t (+inf� a haszn�l�k sz�m�r�l)

$site_full = 2;//felh(0), mod(1), admin(2) -- hozz�f�r a megk�t�s kalkul�torhoz, t�r�lhet oldalt
$site_mod = 2;//felh(0), mod(1), admin(2) -- m�dos�thatja az oldal adatait (megk�t�s kalkul�tort csak ha site_full)
$site_add = 2;//felh(0), mod(1), admin(2) -- hozz�adhat oldalt (wildcardokkal)

$user_full = 2;//felh(0), mod(1), admin(2) -- hozz�f�r jelsz�hoz, emailhez, t�r�lhet felhaszn�l�t,hozz�adhat felhaszn�l�t
$user_mod = 2;//felh(0), mod(1), admin(2) -- m�dos�thatja a felhaszn�l� adatait, l�thatja az adatlapot (user_full n�lk�l csak a marad�kot)(ha user add, l�thatja �s m�dos�thatja a verif-et is)
$user_add = $user_mod;//felh(0), user_mod($user_mod), user_full($user_full) -- aktiv�lhat felhaszn�l�t, l�thatja �s m�dos�thatja a verif-et (ha nulla, akkor a felhaszn�l�k magukat is aktiv�lhatj�k)
//add usern�l ha full akkor hozz�adhat felhaszn�l�t minden adattal �s elk�ldheti megh�v�k�nt az adatokat ha akarja (ha nincs n�v/jelsz� akkor aktiv�ci�s kulcs kell felt�tlen)
//add usern�l ha tam_invite vagy nagyobb �s mod �s nem full, k�ldhet megh�v�t (fullon k�v�l mindent megadhat �s verif-et is ha user_add)
//add usern�l ha tam_invite vagy nagyobb �s nem mod �s nem full, k�ldhet megh�v�t (csak emailt adhat meg)


?>