/* �rhaj�s v.2.00
 * 
 * Tesztelve: - Internet Explorer 6
 *            - Internet Explorer 7
 *            - Opera 9
 *            - Firefox 2
 *            - Firefox 3
 * 
 */



/* Be�ll�t�sok �s v�ltoz�k deklar�l�sa
 * (t�mb�k l�trehoz�sa)
 * 
 */
// J�t�k t�bla alapbe�ll�t�sai
var tabla = [];
tabla['sorok_szama'] = 25; // oszlopok sz�ma
tabla['oszlopok_szama'] = 80; // sorok sz�ma
tabla['min_csillag_sorban'] = 1; // minimum h�ny csillag legyen egy sorban
tabla['max_csillag_sorban'] = Math.floor(tabla['oszlopok_szama'] / 20) * 3; // maximum h�ny csillag legyen egy sorban (oszlopok 15%-a)
tabla['kezdo_szoveg'] = '<br/><center>J�t�k v�rakozik. �ss entert az ind�t�shoz / meg�ll�t�shoz.</center>'; // j�t�kmez� kezd� sz�vege
tabla['vege_szoveg'] = '<br/><center>J�t�k v�ge. �ss entert az �jraind�t�shoz.</center>'; // j�t�k t�bl�ba v�ge sz�veg �r�sa

// J�t�k elemek kin�zete
var jatekmezo_elemek = [];
jatekmezo_elemek['urhajo'] = '<b class="sarga">W</b>'; // �rhaj�
jatekmezo_elemek[0] = '&nbsp;'; // �res mez�
jatekmezo_elemek[1] = '<b class="feher">*</b>'; // csillag (feh�r)
jatekmezo_elemek[2] = '<b class="piros">*</b>'; // csillag (piros)
jatekmezo_elemek[3] = '<b class="kek">*</b>'; // csillag (k�k)
jatekmezo_elemek[4] = '<b class="zold">*</b>'; // csillag (z�ld)
jatekmezo_elemek[5] = '<b class="feher">#</b>'; // fal (feh�r)

// Felhaszn�l�i fel�let azonos�t�i
var felulet = [];
felulet['jatekmezo'] = 'tabla'; // a j�t�kmez�; html elem (<pre>)
felulet['statusz'] = 'statusz'; // a st�tusz jelz�; input elem (text)
felulet['utkozesek'] = 'utkozesek'; // az �tk�z�s sz�ml�l�; input elem (text)
felulet['ciklus_ido'] = 'ciklus_ido'; // a ciklus id�; input elem (text)
felulet['ciklus_szam'] = 'ciklus_szam'; // a ciklus sz�ml�l�; input elem (text)
felulet['maximum_csillag'] = 'maximum_csillag'; // a maximum csillag; input elem (text)

// �rhaj� adatai
var urhajo = [];
urhajo['kezdo_x'] = false; // haj� kezd� poz�ci�ja az x tengelyen (false = oszlopok sz�m�nak k�zepe)
urhajo['kezdo_y'] = false; // haj� poz�ci�ja az y tengelyen (false = p�lya teteje)
urhajo['lephet_fel'] = 1; // az �rhaj� egy ciklusban h�nyat l�pett felfel� (false = v�gtelen)
urhajo['lephet_le'] = 1; // az �rhaj� egy ciklusban h�nyat l�pett lelfel� (false = v�gtelen)
urhajo['lephet_jobbra'] = false; // az �rhaj� egy ciklusban h�nyat l�pett jobbra (false = v�gtelen)
urhajo['lephet_balra'] = false; // az �rhaj� egy ciklusban h�nyat l�pett balra (false = v�gtelen)
urhajo['x'] = 0; // haj� poz�ci�ja az x tengelyen
urhajo['y'] = 0; // haj� poz�ci�ja az y tengelyen
urhajo['utkozesek'] = 0; // haj� �tk�z�seinek sz�ma

// J�t�k folyamat alapbe�ll�t�sai
var jatek = [];
jatek['statusz'] = '�ll'; // a j�t�k st�tusza bet�lt�sn�l
jatek['ciklus_ido'] = 200; // mekkora legyen a ciklusok k�zt a k�sleltet�s alapb�l
jatek['ciklus_azonosito'] = false; // a ciklus azonos�t�j�nak t�rol�ja
jatek['ciklus_szam'] = 0; // a ciklus sz�m�nak t�rol�ja
jatek['max_ciklus'] = false; // maximum ciklusok sz�ma a v�ge el�tt (false = v�gtelen)
jatek['lepett_fel'] = 0; // az �rhaj� egy ciklusban h�nyat tud l�pni felfel�
jatek['lepett_le'] = 0; // az �rhaj� egy ciklusban h�nyat tud l�pni lelfel�
jatek['lepett_jobbra'] = 0; // az �rhaj� egy ciklusban h�nyat tud l�pni jobbra
jatek['lepett_balra'] = 0; // az �rhaj� egy ciklusban h�nyat tud l�pni balra
jatek['ciklussal_lepes'] = false; // az �rhaj� egy ciklusban csak egyet l�phet �sszesen (true|false)
jatek['lepett'] = false; // �rhaj� cikluson bel�li l�p�s�nek sz�montart�ja

// J�t�kmez�
var palya = []; // k�sz p�lya haszn�lata
var jatekmezo = [];



/* Billenty� le�t�s esem�ny befog�sa
 * 
 * billenty� le�t�s eset�n k�ldje az
 * esem�ny adatait a gombnyomas() funkci�nak
 * 
 */
document.onkeydown = gombnyomas;


/* Gombnyom�s esem�ny elemz�se
 * 
 * billenty� gomb nyom�s eset�n b�ng�sz�
 * f�ggetlen�l meg�llap�tja a lenyomott
 * gomb k�dj�t, �s el�nd�tja a gombhoz
 * megfelel� funkci�t (esetleg tov�bbk�ldve
 * a gomb k�dj�t)
 * 
 * 13 - enter
 * 37 - bal ny�l
 * 38 - fel ny�l
 * 39 - jobb ny�l
 * 40 - le ny�l
 * 107 / 43 - numpad plussz
 * 109 / 45 - numpad m�nusz
 * 
 */
function gombnyomas(e)
{
  var g = (window.event) ? event.keyCode : e.keyCode; // gomb k�d felv�tele

  if(g == 13 || g == 13)
  {
    statusz_valtas(); // enter eset�n st�tusz v�lt�s
  }
  else if(g == 37 || g == 38 || g == 39 || g == 40)
  {
    urhajo_mozgatas(g); // ny�lbillenty� eset�n �rhaj� mozgat�sa
  }
  else // egy�b esetben val�sz�n�leg vlaamilyen be�ll�t�s
  {
    jatek_beallitas(g);
  }
}


/* St�tusz v�lt�s
 * 
 * a j�t�k st�tusz�nak megv�ltoztat�sa
 * az aktu�lis v�ltoz�kt�l �s �llapott�l
 * f�gg�en
 * 
 */
function statusz_valtas()
{
  if(jatek['statusz'] == '�ll') // ha a j�t�k �ll
  {
    document.getElementById(felulet['jatekmezo']).innerHTML = tabla['kezdo_szoveg']; // j�t�k mez�be kezd� sz�veg �r�sa

    // kezd� �llapotok be�ll�t�sa
    if(urhajo['kezdo_x'] === false) // ha nincs meghat�rozott kezd� oszlop
    {
      urhajo['x'] = Math.floor(tabla['oszlopok_szama'] / 2);
    }
    else
    {
      urhajo['x'] = urhajo['kezdo_x'];
    }

    if(urhajo['kezdo_y'] === false) // ha nincs meghat�rozott kezd� sor
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

    if(palya.length > 0) // k�sz p�lya bet�lt�se a j�t�kmez�be
    {
      for(k in palya)
      {
        jatekmezo[k] = palya[k].split(',');
      }
    }
    else // j�t�kmez� l�trehoz�sa, felt�lt�se �res mez�kkel
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

    //jatek['ciklus_azonosito'] = setInterval('jatekciklus();', jatek['ciklus_ido']); // j�t�kciklus ind�t�sa
    //jatek['statusz'] = 'Fut'; // j�t�k st�tusza mostant�l: fut
    jatek['statusz'] = 'V�rakozik'; // j�t�k st�tusza mostant�l: v�rakozik
    statusz_panel(); // friss�ts�k a st�tuszpanel adatait
  }
  else if(jatek['statusz'] == 'Fut') // ha a j�t�k fut
  {
    clearInterval(jatek['ciklus_azonosito']); // aktu�lis j�t�kciklus megszak�t�sa
    jatek['statusz'] = 'V�rakozik'; // j�t�k st�tusza mostant�l: v�rakozik
  }
  else if(jatek['statusz'] == 'V�rakozik') // ha a j�t�k v�rakozik
  {
    jatek['ciklus_azonosito'] = setInterval('jatekciklus();', jatek['ciklus_ido']); // j�t�kciklus ind�t�sa
    jatek['statusz'] = 'Fut'; // j�t�k st�tusza mostant�l: fut
  }
  else if(jatek['statusz'] == 'Le�ll') // ha a j�t�k le�ll
  {
    clearInterval(jatek['ciklus_azonosito']); // j�t�kciklus megszak�t�sa
    jatek['statusz'] = '�ll'; // j�t�k st�tusza mostant�l: �ll
  }

  statusz_panel(); // st�tusz panel friss�t�se
}


/* J�t�kciklus
 * 
 * a j�t�kciklus folyamatosan fut �s m�dos�tja
 * a j�t�kmez� t�mb�t (t�rli a legfels� sort,
 * be�ll�t�s alapj�n felt�lti v�letlen sz�m�
 * csillaggal �s �sszekeveri az �j legals�
 * sort)
 * 
 */
function jatekciklus()
{
  if(jatek['statusz'] == 'Fut')
  {
    jatekmezo.shift(); // a j�t�kmez� legfels� sor�nak eldob�sa

    if(palya.length < 1) // ha nincsen k�sz p�lya bet�ltve
    {
      // v�letlen szer� sz�m min. csillagok �s max. csillagok / sor k�zt
      var csillagok_szama = rand(tabla['min_csillag_sorban'], tabla['max_csillag_sorban']);

      var sor_elemek = '';
      for(var i = 0; tabla['oszlopok_szama'] > i; i++) // ah�ny oszlop annyi elem� t�mb
      {
        if(csillagok_szama >= i) // ah�ny csillag ki lett sz�molva erre a sorra, annyi felv�tele a stringbe
        {
          sor_elemek += rand(1, 4); // a n�gyf�le csillagb�l v�letlenszer�en valamelyiket
        }
        else // a fennmarad� hely �res mez�kkel felt�ltend�
        {
          sor_elemek += '0';
        }
      }

      var kesz_sor = '';
      for(var k = 1; sor_elemek.length > 0; k++) // amilyen hossz� a sor, annyiszor
      {
        //var karakter_index = Math.floor(Math.random() * sor_elemek.length);
        var karakter_index = rand(0, sor_elemek.length - 1);//teszt
        kesz_sor += sor_elemek.substr(karakter_index, 1) + '-';
        sor_elemek = sor_elemek.substr(0, karakter_index) + sor_elemek.substr(karakter_index + 1);
      }
      kesz_sor = kesz_sor.split('-');

      jatekmezo[jatekmezo.length++] = kesz_sor; // �j, utols� sor beilleszt�se
    }

    jatek['ciklus_szam']++; // ciklusok sz�m�t n�velj�k egyel
    jatekmezo_kiirasa(); // �rassuk ki a j�t�kmez�t

    // null�zzuk a haj� cikluson bel�l megtett l�p�seit
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


/* J�t�kmez� ki�r�sa a k�perny�re
 * 
 * megh�vja az urhajo_allapot() �s a
 * statusz_panel() funkci�kat, hogy
 * friss�tse a j�t�kmez�t, majd
 * �talak�tja a jatekmezo[] t�mb�t a
 * mez�knek megfelel� k�dokk� �s beilleszti
 * a t�blafel�letbe
 * (minden ciklussal lefut)
 * 
 */
function jatekmezo_kiirasa()
{
  if(jatek['statusz'] == 'Fut') // csak akkor sz�moljunk ha fut a program
  {
    urhajo_allapot(); // haj� �llapot�nak elemz�se
    statusz_panel(); // st�tuszjelz� panel friss�t�se

    var jatekmezo_forras = '';
    for(var i = 0; (tabla['sorok_szama'] - 1) >= i; i++)//for(var i in jatekmezo)
    {
      if(typeof(jatekmezo[i]) == 'undefined' || jatekmezo[i] === null) // ha nincs ilyen sor
      {
        jatekmezo[i] = []; // sor l�trehoz�sa �resen
      }

      for(var j = 0; (tabla['oszlopok_szama'] - 1) >= j; j++)//for(var j in jatekmezo[i])
      {
        if(urhajo['x'] == j && urhajo['y'] == i) // ezen a mez�n van az �rhaj�
        {
          jatekmezo_forras += jatekmezo_elemek['urhajo'];
        }
        else
        {
          if(typeof(jatekmezo[i][j]) == 'undefined' || jatekmezo[i][j] === null) // ha nincs az adott mez� be�ll�tva
          {
            jatekmezo[i][j] = 0; // felt�lt�s �res mez�vel
          }

          if(typeof(jatekmezo_elemek[jatekmezo[i][j]]) != 'undefined' && jatekmezo_elemek[jatekmezo[i][j]] !== null) // ha van ennek a mez�nek megfelel� elemk�d
          {
            //( mixed_var instanceof Array ) // egyszer�bb elemk�dokhoz �s t�mbben t�rolt sz�nez�s�kh�z a sz�nek k�l�n t�mbben t�rol�sa
            jatekmezo_forras += jatekmezo_elemek[jatekmezo[i][j]];
          }
          else // ha ismeretlen a mez�
          {
            jatekmezo_forras += jatekmezo[i][j];
          }
        }
      }
      jatekmezo_forras += '<br/>'; // sort�r�s minden sor v�g�re
    }

    if(jatek['statusz'] == 'Fut') // ha sz�mol�s alatt v�ltozik a st�tusz, akkor m�g itt �tcs�szna
    {
      document.getElementById(felulet['jatekmezo']).innerHTML = jatekmezo_forras; // a j�t�kmez�b�l kre�lt k�dot, k�pi elemekk� alak�tva ki�rjuk a j�t�kmez� t�bl�ba
    }

    return true;
  }
  else
  {
    return false;
  }
}


/* Haj� �llapot�nak elemz�se
 * 
 * az �rhaj� helyzet�nek �s a j�t�k st�tusz�nak
 * megfelel�en m�dos�tani a j�t�kmez�t
 * �s/vagy a j�t�kmenetet
 * (minden ki�r�s el�tt lefut)
 * 
 */
function urhajo_allapot()
{
  var m = jatekmezo[urhajo['y']][urhajo['x']]; // milyen mez�n van a haj�
  var x = urhajo['x'];
  var y = urhajo['y'];
  var a, b, c; // seg�d v�ltoz�k sz�ks�g eset�re

  if(m == 1 || m == 2 || m == 3 || m == 4) // ha valamelyik csillag mez�n van a haj�
  {
    urhajo['utkozesek']++; // �tk�z�sek sz�m�t n�velj�k egyel

    // kisz�moljuk, hogy hol kell elkezdeni adott mez� �t�r�s�t, hogy kif�rjen a sz�veg
    a = (tabla['oszlopok_szama'] - 1) - x; // mennyi szabad mez� van jobbra a haj�t�l
    b = (a < 4) ? x - (4 - a) : x; // ha nincs el�g szabad mez�, akkor kell� sz�mmal toljuk el az els� bet� hely�t

    jatekmezo[y][b + 0] = '<b class="szurke">B</b>';//0
    jatekmezo[y][b + 1] = '<b class="szurke">U</b>';//1
    jatekmezo[y][b + 2] = '<b class="szurke">M</b>';//2
    jatekmezo[y][b + 3] = '<b class="szurke">M</b>';//3
    jatekmezo[y][b + 4] = '<b class="szurke">!</b>';//4
  }
  else if(m == 5) // ha fal mez�n van a haj�
  {
    jatek['statusz'] = 'Le�ll'; // j�t�k st�tusza mostant�l: le�ll
    statusz_valtas(); // st�tusz v�lt�sa

    // j�t�kmez� fel�l�r�sa valami v�ge sz�veggel
    document.getElementById(felulet['jatekmezo']).innerHTML = '<br/><center>Falnak ment�l. �ss entert az �jraind�t�shoz.</center>';
  }

  // ha az utols� sorban vagyunk �s a j�t�k fut, vagy el�rj�k a maximum ciklussz�mot
  if((y >= (jatekmezo.length - 1) && jatek['statusz'] == 'Fut') || (jatek['max_ciklus']) !== false && jatek['ciklus_szam'] >= jatek['max_ciklus'])
  {
    jatek['statusz'] = 'Le�ll'; // j�t�k st�tusza mostant�l: le�ll
    statusz_valtas(); // st�tusz v�lt�sa

    document.getElementById(felulet['jatekmezo']).innerHTML = tabla['vege_szoveg']; // j�t�k mez�be v�ge sz�veg �r�sa
  }
}


/* St�tuszjelz� panel friss�t�se
 * 
 * friss�ti a st�tuszt mutat� elemek
 * tartalm�t
 * (minden ki�r�s el�tt lefut)
 * 
 */
function statusz_panel()
{
  document.getElementById(felulet['statusz']).value = 'St�tusz: ' + jatek['statusz']; // aktu�lis st�tusz
  document.getElementById(felulet['utkozesek']).value = '�tk�z�sek: ' + urhajo['utkozesek']; // �tk�z�sek sz�ma
  document.getElementById(felulet['ciklus_ido']).value = 'Ciklus id�: ' + jatek['ciklus_ido'] + ' ms'; // ciklus id�
  document.getElementById(felulet['ciklus_szam']).value = 'Ciklus: ' + jatek['ciklus_szam']; // ciklus sz�m
  document.getElementById(felulet['maximum_csillag']).value = 'Max. csillag: ' + tabla['max_csillag_sorban']; // maximum csillag egy sorban
}


/* �rhaj� mozgat�sa
 * 
 * �rhaj� poz�ci�j�t v�ltoztat� funkci� ami
 * ellen�rzi a kapott billenty�k�dot, �s
 * annak megfelel�en a felt�telekt�l f�gg�en
 * m�dos�tja a haj� x vagy y koordin�t�j�t
 * �s n�veli az adott ir�nynak megfelel�
 * l�p�sek sz�m�t a ciklusban
 * 
 * 37 - balra
 * 38 - fel
 * 39 - jobbra
 * 40 - le
 */
function urhajo_mozgatas(g)
{
  if(jatek['statusz'] == 'Fut' && jatek['lepett'] === false) // a j�t�k fut, �s az �rhaj� m�g l�phet ebben a ciklusban
  {
    // lefel� l�p, nem az utols� sorban vagyunk �s m�g l�phet lefel�
    if(g == 40 && urhajo['y'] < (jatekmezo.length - 1) && (urhajo['lephet_le'] > jatek['lepett_le'] || urhajo['lephet_le'] === false))
    {
      urhajo['y']++;
      jatek['lepett_le']++;
    }
    // jobbra l�p �s m�g l�phet jobbra
    else if(g == 39 && (urhajo['lephet_jobbra'] > jatek['lepett_jobbra'] || urhajo['lephet_jobbra'] === false))
    {
      if(urhajo['x'] == (tabla['oszlopok_szama'] - 1)) // ha a jobb sz�len vagyunk
      {
        urhajo['x'] = 0;
      }
      else
      {
        urhajo['x']++;
      }
      jatek['lepett_jobbra']++;
    }
    // balra l�p �s m�g l�phet balra
    else if(g == 37 && (urhajo['lephet_balra'] > jatek['lepett_balra'] || urhajo['lephet_balra'] === false))
    {
      if(urhajo['x'] == 0) // ha a bal sz�len vagyunk
      {
        urhajo['x'] = (tabla['oszlopok_szama'] - 1);
      }
      else
      {
        urhajo['x']--;
      }
      jatek['lepett_balra']++;
    }
    // fel l�p, nem a legfels� sorban vagyunk �s m�g l�phet felfel�
    else if(g == 38 && urhajo['y'] > 0 && (urhajo['lephet_fel'] > jatek['lepett_fel'] || urhajo['lephet_fel'] === false))
    {
      urhajo['y']--;
      jatek['lepett_fel']++;
    }
    else // ha nincsen �rv�nyes mozg�s
    {
      return false; // ugorjunk ki a f�ggv�nyb�l
    }

    jatek['lepett'] = jatek['ciklussal_lepes']; // ha csak a ciklussal l�phet, jelezz�k, hogy megvolt a l�p�s
    if(jatek['lepett'] === false) // ha nem csak a ciklussal mozoghat
    {
      jatekmezo_kiirasa(); // gener�ljuk �jra a j�t�kt�bl�t, hogy l�ssuk a haj� �j helyzet�t
    }
    else
    {
      urhajo_allapot(); // csak ciklussal mozoghat, ellen�rizz�k az �llapotot miel�tt ki�r�dik a j�t�kmez�
    }
  }
}


/* J�t�k be�ll�t�sok kezel�se
 * 
 * a j�t�k menet k�zbeni be�ll�t�sait ellen�rz�
 * funkci� ami ellen�rzi a kapott gombnyom�sok
 * k�dj�t, �s megv�ltoztatja az adott
 * be�ll�t�st
 * 
 * 109 - numpad m�nusz
 * 107 - numpad plussz
 */
function jatek_beallitas(g)
{
  if(g == 109 || g == 107 || g == 45 || g == 43) // ha a ciklus id� v�ltozik (numpad + / -)
  {
    if(g == 109 || g == 45) // ha kisebbre
    {
      if(jatek['ciklus_ido'] > 50) // am�g nem kisebb mint 50
      {
        jatek['ciklus_ido'] -= 50; // legyen 50-el kevesebb
      }
    }
    else if(g == 107 || g == 43) // ha nagyobbra
    {
      jatek['ciklus_ido'] += 50; // legyen 50-el t�bb
    }

    clearInterval(jatek['ciklus_azonosito']); // aktu�lis ciklus megszak�t�sa
    jatek['ciklus_azonosito'] = setInterval('jatekciklus();', jatek['ciklus_ido']); // �j ciklus ind�t�sa a megl�v� �llapotokkal �s �j ciklus id�vel
  }
  else if (48 < g && g < 58) // ha a maximum csillagok sz�ma v�ltozik
  {
    var a = g - 48; // a = amelyik gomb lett lenyomva
    tabla['max_csillag_sorban'] = Math.floor(tabla['oszlopok_szama'] / 20) * a; // az oszlopk sz�m�nak 5%-a szorozva a nyomott gombbal sz�m� max csillag (5%-45%)
  }

  statusz_panel();
}


/* Rand v�letlensz�m f�ggv�ny
 * 
 * v�letlenszer� integert el��ll�t� f�ggv�ny
 * min �s max sz�m k�zt
 * (egyszer�s�t�shez)
 * 
 */
function rand(min, max)
{
  return Math.floor(Math.random() * (max - min + 1) + min);
}

/* <EOF> */