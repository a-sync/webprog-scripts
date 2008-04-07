<?php
/* CONFIG */

$tam_dbhost = 'localhost';
$tam_dbuser = 'root';
$tam_dbpass = '';
$tam_dbname = 'vector';

$tam_rights = '0|2|0|';//alap jogok

  //CLASS
  //0 - 0 Felhasználó
  //0 - 1 Moderátor
  //0 - 2 Adminisztrátor

  //USER
  //1 - 0 Semmit sem állíthat
  //1 - 1 jelszót és értesítéseket állíthatja
  //1 - 2 e-mail címet is állíthatja
  //1 - 3 látja a felhasználólistát, az alap adatlapokat, a saját meghívottjai listáját
  //1 - 4 látja a teljes adatlapokat, és a meghívottakat
  //1 - 5 módosíthatja az adatokat a jogok, státusz, class, verif, secret kivételével - meghivonal allithat verif-et - (kivéve magasabb classon)
  //1 - 6 módosíthatja a USER jogokat 0-6-ig, a státuszt, classokat saját classig, a verif-et (kivéve magasabb classon)

  //LOG
  //2 - 0 Nem láthatja a logokat
  //2 - 1 Láthatja a 0-1-2 classal rendelkezõ logokat
  //2 - 2 Láthatja az összes logot
  //2 - 3 Módosíthatja a 0-1-2 classal rendelkezõ logokat, látja és módosíthatja az admin kommentet és a státuszát a logoknak
  //2 - 4 Láthatja, módosíthatja az összes logot, az admin kommentet és a státuszát a logoknak; (USER 6) esetén állíthatja, (kivéve magasabb classon)
?>