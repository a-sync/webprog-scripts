<?php
header("Content-Type: text/html; charset=iso-utf-8"); // hogy tutira egyezzen a kimenet karakterkódolása az oldaléval
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>szöveg cserélő cucc</title>
</head>

<body>

<?php
if(file_exists($_POST['szoveg']) && file_exists($_POST['szavak']) && $_POST['elvalaszto'] != '') { //formtól post methoddal kapott adatok ellenőrzése
  // ha mindkét fájl létezik a megadott helyen, és megvan adva az elválasztó karakter

  $szoveg = file_get_contents($_POST['szoveg']); // szöveg fájl tartalmának kinyerése

/*
  $szavak_fajl = fopen($_POST['szavak'], 'r'); // szavak fájl megnyitása olvasásra
  
  if ($szavak_fajl) { // ha sikerült megnyitni a fájlt
    while (!feof($szavak_fajl)) { // amíg az olvasás mutató nem áll a fájl végén
      $sor = str_replace("\r\n", '', fgets($szavak_fajl)); // akutális sor kiolvasása, sortörés karakter eltávolítása, és a fájl mutató tovább tolása

      if($sor != '' && strstr($sor, $_POST['elvalaszto']) !== false) { // ha a sor nem üres, és a sorban megtalálható az elválasztó
        $sor_szavai = explode($_POST['elvalaszto'], $sor, 2); // törje szét a sort az elválasztó mentén maximum két részre egy tömbbe
      
        // a széttört sor bal eleme a szó amit ki akarunk cserélni, a jobb eleme a szó amire ki akarjuk cserélni
        $szavak_bal_tomb[] = $_POST['bal_jel'].$sor_szavai[0].$_POST['jobb_jel']; // bal szó + jelölők felvétele a bal szavakat tartalmazó tömbbe
        $szavak_jobb_tomb[] = $_POST['bal_jel'].$sor_szavai[1].$_POST['jobb_jel']; // jobb szó + jelölők felvétele a jobb szavakat tartalmazó tömbbe
      }

    }

    fclose($szavak_fajl); // fájl bezárása
  }
*/
  $szavak = file_get_contents($_POST['szavak']); // szavak fájl tartalmának kinyerése
  $sorok = explode("\r\n", $szavak); // a tartalom széttörése soronként egy tömbbe az új sor karakterek mentén

  foreach($sorok as $sor) {
    if($sor != '' && strstr($sor, $_POST['elvalaszto']) !== false) { // ha a sor nem üres, és a sorban megtalálható az elválasztó
      $sor_szavai = explode($_POST['elvalaszto'], $sor, 2); // törje szét a sort az elválasztó mentén maximum két részre egy tömbbe

      // a széttört sor bal eleme a szó amit ki akarunk cserélni, a jobb eleme a szó amire ki akarjuk cserélni
      $szavak_bal_tomb[] = $_POST['bal_jel'].$sor_szavai[0].$_POST['jobb_jel']; // bal szó + jelölők felvétele a bal szavakat tartalmazó tömbbe
      $szavak_jobb_tomb[] = $_POST['bal_jel'].$sor_szavai[1].$_POST['jobb_jel']; // jobb szó + jelölők felvétele a jobb szavakat tartalmazó tömbbe
    }

  }

  $cserelt_szoveg = str_replace($szavak_bal_tomb, $szavak_jobb_tomb, $szoveg); // cserélje le a bal oszlop szavait, a jobb oszlop szavaira a szövegben (mit, mire, miben)

  echo '<pre>'.htmlentities($cserelt_szoveg).'</pre>'; // a kicserélt szavakat tartalmazó szöveg kiírása a különleges karakterek html-ben kiírható formára alakításával, <pre> tagek közt
}
else { // ha nincs átalakítás akkor a küldő form kiírása
?>
  <form action="" method="post">
    Teljes szöveget tartalmazó fájl elérése: <input type="text" name="szoveg" value="szoveg.xml"/>
    <br/>
    Kicserélendő szavak jelölése balról: <input type="text" name="bal_jel" value="<"/>
    <br/>
    Kicserélendő szavak jelölése jobbról: <input type="text" name="jobb_jel" value=">"/>
    <br/>
    <br/>
    Angol / Magyar szavakat tartalmazó fájl: <input type="text" name="szavak" value="szavak.txt"/>
    (A szópárok új sorokban legyenek, ne legyen üres sor)
    <br/>
    Szavakat elválasztó karakter(ek): <input type="text" name="elvalaszto" value="@"/>
    <br/>
    <input type="submit" name="mehet" value="Mehet!"/>
    <br/>
    (minden adat legyen megadva)
  </form>
<?php } ?>
</body>
</html>
