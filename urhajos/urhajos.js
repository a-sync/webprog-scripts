/* Ûrhajós v.2.00
 * 
 * Tesztelve: - Internet Explorer 6
 *            - Internet Explorer 7
 *            - Opera 9
 *            - Firefox 2
 *            - Firefox 3
 * 
 */



/* Beállítások és változók deklarálása
 * (tömbök létrehozása)
 * 
 */
// Játék tábla alapbeállításai
var tabla = [];
tabla['sorok_szama'] = 25; // oszlopok száma
tabla['oszlopok_szama'] = 80; // sorok száma
tabla['min_csillag_sorban'] = 1; // minimum hány csillag legyen egy sorban
tabla['max_csillag_sorban'] = Math.floor(tabla['oszlopok_szama'] / 20) * 3; // maximum hány csillag legyen egy sorban (oszlopok 15%-a)
tabla['kezdo_szoveg'] = '<br/><center>Játék várakozik. Üss entert az indításhoz / megállításhoz.</center>'; // játékmezõ kezdõ szövege
tabla['vege_szoveg'] = '<br/><center>Játék vége. Üss entert az újraindításhoz.</center>'; // játék táblába vége szöveg írása

// Játék elemek kinézete
var jatekmezo_elemek = [];
jatekmezo_elemek['urhajo'] = '<b class="sarga">W</b>'; // ûrhajó
jatekmezo_elemek[0] = '&nbsp;'; // üres mezõ
jatekmezo_elemek[1] = '<b class="feher">*</b>'; // csillag (fehér)
jatekmezo_elemek[2] = '<b class="piros">*</b>'; // csillag (piros)
jatekmezo_elemek[3] = '<b class="kek">*</b>'; // csillag (kék)
jatekmezo_elemek[4] = '<b class="zold">*</b>'; // csillag (zöld)
jatekmezo_elemek[5] = '<b class="feher">#</b>'; // fal (fehér)

// Felhasználói felület azonosítói
var felulet = [];
felulet['jatekmezo'] = 'tabla'; // a játékmezõ; html elem (<pre>)
felulet['statusz'] = 'statusz'; // a státusz jelzõ; input elem (text)
felulet['utkozesek'] = 'utkozesek'; // az ütközés számláló; input elem (text)
felulet['ciklus_ido'] = 'ciklus_ido'; // a ciklus idõ; input elem (text)
felulet['ciklus_szam'] = 'ciklus_szam'; // a ciklus számláló; input elem (text)
felulet['maximum_csillag'] = 'maximum_csillag'; // a maximum csillag; input elem (text)

// Ûrhajó adatai
var urhajo = [];
urhajo['kezdo_x'] = false; // hajó kezdõ pozíciója az x tengelyen (false = oszlopok számának közepe)
urhajo['kezdo_y'] = false; // hajó pozíciója az y tengelyen (false = pálya teteje)
urhajo['lephet_fel'] = 1; // az ûrhajó egy ciklusban hányat lépett felfelé (false = végtelen)
urhajo['lephet_le'] = 1; // az ûrhajó egy ciklusban hányat lépett lelfelé (false = végtelen)
urhajo['lephet_jobbra'] = false; // az ûrhajó egy ciklusban hányat lépett jobbra (false = végtelen)
urhajo['lephet_balra'] = false; // az ûrhajó egy ciklusban hányat lépett balra (false = végtelen)
urhajo['x'] = 0; // hajó pozíciója az x tengelyen
urhajo['y'] = 0; // hajó pozíciója az y tengelyen
urhajo['utkozesek'] = 0; // hajó ütközéseinek száma

// Játék folyamat alapbeállításai
var jatek = [];
jatek['statusz'] = 'Áll'; // a játék státusza betöltésnél
jatek['ciklus_ido'] = 200; // mekkora legyen a ciklusok közt a késleltetés alapból
jatek['ciklus_azonosito'] = false; // a ciklus azonosítójának tárolója
jatek['ciklus_szam'] = 0; // a ciklus számának tárolója
jatek['max_ciklus'] = false; // maximum ciklusok száma a vége elõtt (false = végtelen)
jatek['lepett_fel'] = 0; // az ûrhajó egy ciklusban hányat tud lépni felfelé
jatek['lepett_le'] = 0; // az ûrhajó egy ciklusban hányat tud lépni lelfelé
jatek['lepett_jobbra'] = 0; // az ûrhajó egy ciklusban hányat tud lépni jobbra
jatek['lepett_balra'] = 0; // az ûrhajó egy ciklusban hányat tud lépni balra
jatek['ciklussal_lepes'] = false; // az ûrhajó egy ciklusban csak egyet léphet összesen (true|false)
jatek['lepett'] = false; // ûrhajó cikluson belüli lépésének számontartója

// Játékmezõ
var palya = []; // kész pálya használata
var jatekmezo = [];



/* Billentyû leütés esemény befogása
 * 
 * billentyû leütés esetén küldje az
 * esemény adatait a gombnyomas() funkciónak
 * 
 */
document.onkeydown = gombnyomas;


/* Gombnyomás esemény elemzése
 * 
 * billentyû gomb nyomás esetén böngészõ
 * függetlenül megállapítja a lenyomott
 * gomb kódját, és elíndítja a gombhoz
 * megfelelõ funkciót (esetleg továbbküldve
 * a gomb kódját)
 * 
 * 13 - enter
 * 37 - bal nyíl
 * 38 - fel nyíl
 * 39 - jobb nyíl
 * 40 - le nyíl
 * 107 / 43 - numpad plussz
 * 109 / 45 - numpad mínusz
 * 
 */
function gombnyomas(e)
{
  var g = (window.event) ? event.keyCode : e.keyCode; // gomb kód felvétele

  if(g == 13 || g == 13)
  {
    statusz_valtas(); // enter esetén státusz váltás
  }
  else if(g == 37 || g == 38 || g == 39 || g == 40)
  {
    urhajo_mozgatas(g); // nyílbillentyû esetén ûrhajó mozgatása
  }
  else // egyéb esetben valószínûleg vlaamilyen beállítás
  {
    jatek_beallitas(g);
  }
}


/* Státusz váltás
 * 
 * a játék státuszának megváltoztatása
 * az aktuális változóktól és állapottól
 * függõen
 * 
 */
function statusz_valtas()
{
  if(jatek['statusz'] == 'Áll') // ha a játék áll
  {
    document.getElementById(felulet['jatekmezo']).innerHTML = tabla['kezdo_szoveg']; // játék mezõbe kezdõ szöveg írása

    // kezdõ állapotok beállítása
    if(urhajo['kezdo_x'] === false) // ha nincs meghatározott kezdõ oszlop
    {
      urhajo['x'] = Math.floor(tabla['oszlopok_szama'] / 2);
    }
    else
    {
      urhajo['x'] = urhajo['kezdo_x'];
    }

    if(urhajo['kezdo_y'] === false) // ha nincs meghatározott kezdõ sor
    {
      urhajo['y'] = 0;
    }
    else{
      urhajo['y'] = urhajo['kezdo_y'];
    }

    urhajo['utkozesek'] = 0;
    jatek['lepett_fel'] = 0;
    jatek['lepett_le'] = 0;
    jatek['lepett_jobbra'] = 0;
    jatek['lepett_balra'] = 0;
    jatek['lepett'] = false;

    jatek['ciklus_szam'] = 0;

    if(palya.length > 0) // kész pálya betöltése a játékmezõbe
    {
      for(k in palya)
      {
        jatekmezo[k] = palya[k].split(',');
      }
    }
    else // játékmezõ létrehozása, feltöltése üres mezõkkel
    {
      for(var i = 0; tabla['sorok_szama'] > i; i++)
      {
        jatekmezo[i] = [];

        for(var j = 0; tabla['oszlopok_szama'] > j; j++)
        {
          jatekmezo[i][j] = 0;
        }
      }
    }

    //jatek['ciklus_azonosito'] = setInterval('jatekciklus();', jatek['ciklus_ido']); // játékciklus indítása
    //jatek['statusz'] = 'Fut'; // játék státusza mostantól: fut
    jatek['statusz'] = 'Várakozik'; // játék státusza mostantól: várakozik
    statusz_panel(); // frissítsük a státuszpanel adatait
  }
  else if(jatek['statusz'] == 'Fut') // ha a játék fut
  {
    clearInterval(jatek['ciklus_azonosito']); // aktuális játékciklus megszakítása
    jatek['statusz'] = 'Várakozik'; // játék státusza mostantól: várakozik
  }
  else if(jatek['statusz'] == 'Várakozik') // ha a játék várakozik
  {
    jatek['ciklus_azonosito'] = setInterval('jatekciklus();', jatek['ciklus_ido']); // játékciklus indítása
    jatek['statusz'] = 'Fut'; // játék státusza mostantól: fut
  }
  else if(jatek['statusz'] == 'Leáll') // ha a játék leáll
  {
    clearInterval(jatek['ciklus_azonosito']); // játékciklus megszakítása
    jatek['statusz'] = 'Áll'; // játék státusza mostantól: áll
  }

  statusz_panel(); // státusz panel frissítése
}


/* Játékciklus
 * 
 * a játékciklus folyamatosan fut és módosítja
 * a játékmezõ tömböt (törli a legfelsõ sort,
 * beállítás alapján feltölti véletlen számú
 * csillaggal és összekeveri az új legalsó
 * sort)
 * 
 */
function jatekciklus()
{
  if(jatek['statusz'] == 'Fut')
  {
    jatekmezo.shift(); // a játékmezõ legfelsõ sorának eldobása

    if(palya.length < 1) // ha nincsen kész pálya betöltve
    {
      // véletlen szerû szám min. csillagok és max. csillagok / sor közt
      var csillagok_szama = rand(tabla['min_csillag_sorban'], tabla['max_csillag_sorban']);

      var sor_elemek = '';
      for(var i = 0; tabla['oszlopok_szama'] > i; i++) // ahány oszlop annyi elemû tömb
      {
        if(csillagok_szama >= i) // ahány csillag ki lett számolva erre a sorra, annyi felvétele a stringbe
        {
          sor_elemek += rand(1, 4); // a négyféle csillagból véletlenszerûen valamelyiket
        }
        else // a fennmaradó hely üres mezõkkel feltöltendõ
        {
          sor_elemek += '0';
        }
      }

      var kesz_sor = '';
      for(var k = 1; sor_elemek.length > 0; k++) // amilyen hosszú a sor, annyiszor
      {
        //var karakter_index = Math.floor(Math.random() * sor_elemek.length);
        var karakter_index = rand(0, sor_elemek.length - 1);//teszt
        kesz_sor += sor_elemek.substr(karakter_index, 1) + '-';
        sor_elemek = sor_elemek.substr(0, karakter_index) + sor_elemek.substr(karakter_index + 1);
      }
      kesz_sor = kesz_sor.split('-');

      jatekmezo[jatekmezo.length++] = kesz_sor; // új, utolsó sor beillesztése
    }

    jatek['ciklus_szam']++; // ciklusok számát növeljük egyel
    jatekmezo_kiirasa(); // írassuk ki a játékmezõt

    // nullázzuk a hajó cikluson belül megtett lépéseit
    jatek['lepett_fel'] = 0;
    jatek['lepett_le'] = 0;
    jatek['lepett_jobbra'] = 0;
    jatek['lepett_balra'] = 0;
    jatek['lepett'] = false;

    return true;
  }
  else
  {
    return false;
  }
}


/* Játékmezõ kiírása a képernyõre
 * 
 * meghívja az urhajo_allapot() és a
 * statusz_panel() funkciókat, hogy
 * frissítse a játékmezõt, majd
 * átalakítja a jatekmezo[] tömböt a
 * mezõknek megfelelõ kódokká és beilleszti
 * a táblafelületbe
 * (minden ciklussal lefut)
 * 
 */
function jatekmezo_kiirasa()
{
  if(jatek['statusz'] == 'Fut') // csak akkor számoljunk ha fut a program
  {
    urhajo_allapot(); // hajó állapotának elemzése
    statusz_panel(); // státuszjelzõ panel frissítése

    var jatekmezo_forras = '';
    for(var i = 0; (tabla['sorok_szama'] - 1) >= i; i++)//for(var i in jatekmezo)
    {
      if(typeof(jatekmezo[i]) == 'undefined' || jatekmezo[i] === null) // ha nincs ilyen sor
      {
        jatekmezo[i] = []; // sor létrehozása üresen
      }

      for(var j = 0; (tabla['oszlopok_szama'] - 1) >= j; j++)//for(var j in jatekmezo[i])
      {
        if(urhajo['x'] == j && urhajo['y'] == i) // ezen a mezõn van az ûrhajó
        {
          jatekmezo_forras += jatekmezo_elemek['urhajo'];
        }
        else
        {
          if(typeof(jatekmezo[i][j]) == 'undefined' || jatekmezo[i][j] === null) // ha nincs az adott mezõ beállítva
          {
            jatekmezo[i][j] = 0; // feltöltés üres mezõvel
          }

          if(typeof(jatekmezo_elemek[jatekmezo[i][j]]) != 'undefined' && jatekmezo_elemek[jatekmezo[i][j]] !== null) // ha van ennek a mezõnek megfeleló elemkód
          {
            //( mixed_var instanceof Array ) // egyszerûbb elemkódokhoz és tömbben tárolt színezésükhöz a színek külön tömbben tárolása
            jatekmezo_forras += jatekmezo_elemek[jatekmezo[i][j]];
          }
          else // ha ismeretlen a mezõ
          {
            jatekmezo_forras += jatekmezo[i][j];
          }
        }
      }
      jatekmezo_forras += '<br/>'; // sortörés minden sor végére
    }

    if(jatek['statusz'] == 'Fut') // ha számolás alatt változik a státusz, akkor még itt átcsúszna
    {
      document.getElementById(felulet['jatekmezo']).innerHTML = jatekmezo_forras; // a játékmezõbõl kreált kódot, képi elemekké alakítva kiírjuk a játékmezõ táblába
    }

    return true;
  }
  else
  {
    return false;
  }
}


/* Hajó állapotának elemzése
 * 
 * az ûrhajó helyzetének és a játék státuszának
 * megfelelõen módosítani a játékmezõt
 * és/vagy a játékmenetet
 * (minden kiírás elõtt lefut)
 * 
 */
function urhajo_allapot()
{
  var m = jatekmezo[urhajo['y']][urhajo['x']]; // milyen mezõn van a hajó
  var x = urhajo['x'];
  var y = urhajo['y'];
  var a, b, c; // segéd változók szükség esetére

  if(m == 1 || m == 2 || m == 3 || m == 4) // ha valamelyik csillag mezõn van a hajó
  {
    urhajo['utkozesek']++; // ütközések számát növeljük egyel

    // kiszámoljuk, hogy hol kell elkezdeni adott mezõ átírását, hogy kiférjen a szöveg
    a = (tabla['oszlopok_szama'] - 1) - x; // mennyi szabad mezõ van jobbra a hajótól
    b = (a < 4) ? x - (4 - a) : x; // ha nincs elég szabad mezõ, akkor kellõ számmal toljuk el az elsõ betû helyét

    jatekmezo[y][b + 0] = '<b class="szurke">B</b>';//0
    jatekmezo[y][b + 1] = '<b class="szurke">U</b>';//1
    jatekmezo[y][b + 2] = '<b class="szurke">M</b>';//2
    jatekmezo[y][b + 3] = '<b class="szurke">M</b>';//3
    jatekmezo[y][b + 4] = '<b class="szurke">!</b>';//4
  }
  else if(m == 5) // ha fal mezõn van a hajó
  {
    jatek['statusz'] = 'Leáll'; // játék státusza mostantól: leáll
    statusz_valtas(); // státusz váltása

    // játékmezõ felülírása valami vége szöveggel
    document.getElementById(felulet['jatekmezo']).innerHTML = '<br/><center>Falnak mentél. Üss entert az újraindításhoz.</center>';
  }

  // ha az utolsó sorban vagyunk és a játék fut, vagy elérjük a maximum ciklusszámot
  if((y >= (jatekmezo.length - 1) && jatek['statusz'] == 'Fut') || (jatek['max_ciklus']) !== false && jatek['ciklus_szam'] >= jatek['max_ciklus'])
  {
    jatek['statusz'] = 'Leáll'; // játék státusza mostantól: leáll
    statusz_valtas(); // státusz váltása

    document.getElementById(felulet['jatekmezo']).innerHTML = tabla['vege_szoveg']; // játék mezõbe vége szöveg írása
  }
}


/* Státuszjelzõ panel frissítése
 * 
 * frissíti a státuszt mutató elemek
 * tartalmát
 * (minden kiírás elõtt lefut)
 * 
 */
function statusz_panel()
{
  document.getElementById(felulet['statusz']).value = 'Státusz: ' + jatek['statusz']; // aktuális státusz
  document.getElementById(felulet['utkozesek']).value = 'Ütközések: ' + urhajo['utkozesek']; // ütközések száma
  document.getElementById(felulet['ciklus_ido']).value = 'Ciklus idõ: ' + jatek['ciklus_ido'] + ' ms'; // ciklus idõ
  document.getElementById(felulet['ciklus_szam']).value = 'Ciklus: ' + jatek['ciklus_szam']; // ciklus szám
  document.getElementById(felulet['maximum_csillag']).value = 'Max. csillag: ' + tabla['max_csillag_sorban']; // maximum csillag egy sorban
}


/* Ûrhajó mozgatása
 * 
 * ûrhajó pozícióját változtató funkció ami
 * ellenõrzi a kapott billentyûkódot, és
 * annak megfelelõen a feltételektõl függõen
 * módosítja a hajó x vagy y koordinátáját
 * és növeli az adott iránynak megfelelõ
 * lépések számát a ciklusban
 * 
 * 37 - balra
 * 38 - fel
 * 39 - jobbra
 * 40 - le
 */
function urhajo_mozgatas(g)
{
  if(jatek['statusz'] == 'Fut' && jatek['lepett'] === false) // a játék fut, és az ûrhajó még léphet ebben a ciklusban
  {
    // lefelé lép, nem az utolsó sorban vagyunk és még léphet lefelé
    if(g == 40 && urhajo['y'] < (jatekmezo.length - 1) && (urhajo['lephet_le'] > jatek['lepett_le'] || urhajo['lephet_le'] === false))
    {
      urhajo['y']++;
      jatek['lepett_le']++;
    }
    // jobbra lép és még léphet jobbra
    else if(g == 39 && (urhajo['lephet_jobbra'] > jatek['lepett_jobbra'] || urhajo['lephet_jobbra'] === false))
    {
      if(urhajo['x'] == (tabla['oszlopok_szama'] - 1)) // ha a jobb szélen vagyunk
      {
        urhajo['x'] = 0;
      }
      else
      {
        urhajo['x']++;
      }
      jatek['lepett_jobbra']++;
    }
    // balra lép és még léphet balra
    else if(g == 37 && (urhajo['lephet_balra'] > jatek['lepett_balra'] || urhajo['lephet_balra'] === false))
    {
      if(urhajo['x'] == 0) // ha a bal szélen vagyunk
      {
        urhajo['x'] = (tabla['oszlopok_szama'] - 1);
      }
      else
      {
        urhajo['x']--;
      }
      jatek['lepett_balra']++;
    }
    // fel lép, nem a legfelsõ sorban vagyunk és még léphet felfelé
    else if(g == 38 && urhajo['y'] > 0 && (urhajo['lephet_fel'] > jatek['lepett_fel'] || urhajo['lephet_fel'] === false))
    {
      urhajo['y']--;
      jatek['lepett_fel']++;
    }
    else // ha nincsen érvényes mozgás
    {
      return false; // ugorjunk ki a függvénybõl
    }

    jatek['lepett'] = jatek['ciklussal_lepes']; // ha csak a ciklussal léphet, jelezzük, hogy megvolt a lépés
    if(jatek['lepett'] === false) // ha nem csak a ciklussal mozoghat
    {
      jatekmezo_kiirasa(); // generáljuk újra a játéktáblát, hogy lássuk a hajó új helyzetét
    }
    else
    {
      urhajo_allapot(); // csak ciklussal mozoghat, ellenõrizzük az állapotot mielõtt kiíródik a játékmezõ
    }
  }
}


/* Játék beállítások kezelése
 * 
 * a játék menet közbeni beállításait ellenörzõ
 * funkció ami ellenõrzi a kapott gombnyomások
 * kódját, és megváltoztatja az adott
 * beállítást
 * 
 * 109 - numpad mínusz
 * 107 - numpad plussz
 */
function jatek_beallitas(g)
{
  if(g == 109 || g == 107 || g == 45 || g == 43) // ha a ciklus idõ változik (numpad + / -)
  {
    if(g == 109 || g == 45) // ha kisebbre
    {
      if(jatek['ciklus_ido'] > 50) // amíg nem kisebb mint 50
      {
        jatek['ciklus_ido'] -= 50; // legyen 50-el kevesebb
      }
    }
    else if(g == 107 || g == 43) // ha nagyobbra
    {
      jatek['ciklus_ido'] += 50; // legyen 50-el több
    }

    clearInterval(jatek['ciklus_azonosito']); // aktuális ciklus megszakítása
    jatek['ciklus_azonosito'] = setInterval('jatekciklus();', jatek['ciklus_ido']); // új ciklus indítása a meglévõ állapotokkal és új ciklus idõvel
  }
  else if (48 < g && g < 58) // ha a maximum csillagok száma változik
  {
    var a = g - 48; // a = amelyik gomb lett lenyomva
    tabla['max_csillag_sorban'] = Math.floor(tabla['oszlopok_szama'] / 20) * a; // az oszlopk számának 5%-a szorozva a nyomott gombbal számú max csillag (5%-45%)
  }

  statusz_panel();
}


/* Rand véletlenszám függvény
 * 
 * véletlenszerû integert elõállító függvény
 * min és max szám közt
 * (egyszerûsítéshez)
 * 
 */
function rand(min, max)
{
  return Math.floor(Math.random() * (max - min + 1) + min);
}

/* <EOF> */