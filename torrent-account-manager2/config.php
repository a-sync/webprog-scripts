<?php
/* CONFIG */

$tam_dbhost = 'localhost';
$tam_dbuser = 'root';
$tam_dbpass = '';
$tam_dbname = 'vector';

$tam_rights = '0|2|0|';//alap jogok

  //CLASS
  //0 - 0 Felhaszn�l�
  //0 - 1 Moder�tor
  //0 - 2 Adminisztr�tor

  //USER
  //1 - 0 Semmit sem �ll�that
  //1 - 1 jelsz�t �s �rtes�t�seket �ll�thatja
  //1 - 2 e-mail c�met is �ll�thatja
  //1 - 3 l�tja a felhaszn�l�list�t, az alap adatlapokat, a saj�t megh�vottjai list�j�t
  //1 - 4 l�tja a teljes adatlapokat, �s a megh�vottakat
  //1 - 5 m�dos�thatja az adatokat a jogok, st�tusz, class, verif, secret kiv�tel�vel - meghivonal allithat verif-et - (kiv�ve magasabb classon)
  //1 - 6 m�dos�thatja a USER jogokat 0-6-ig, a st�tuszt, classokat saj�t classig, a verif-et (kiv�ve magasabb classon)

  //LOG
  //2 - 0 Nem l�thatja a logokat
  //2 - 1 L�thatja a 0-1-2 classal rendelkez� logokat
  //2 - 2 L�thatja az �sszes logot
  //2 - 3 M�dos�thatja a 0-1-2 classal rendelkez� logokat, l�tja �s m�dos�thatja az admin kommentet �s a st�tusz�t a logoknak
  //2 - 4 L�thatja, m�dos�thatja az �sszes logot, az admin kommentet �s a st�tusz�t a logoknak; (USER 6) eset�n �ll�thatja, (kiv�ve magasabb classon)
?>