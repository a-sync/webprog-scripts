<?php
require('config.php');

$_USER = checkLogin();
if($_USER) {

  //adminbol users.php basztatása
  if(isset($_P['user']) && is_numeric($_P['user']) && $_USER['rank'] > 5) {
    $_U = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `usid` = '{$_P['user']}'"), MYSQL_ASSOC) or $error .= ' Nincs ilyen id az adatbázisban: '.$_P['user'];

    if($error == '') {
      //megerősítőkód módosítás
      $new_confirm = ($_P['confirm-'.$_P['user']]) ? ($_P['confirm-'.$_P['user']]) : $_U['confirm'];
      if($new_confirm != '' && !preg_match('/^[A-Za-z0-9]{1,200}$/', $new_confirm)) {
        $error .= 'Az új megerősítőkód maximum 200 karakter legyen (a-z, A-Z, 0-9). ';
      }

      //rang módosítás
      $new_rank = ($_P['rank-'.$_P['user']]) ? ($_P['rank-'.$_P['user']]) : $_U['rank'];
      if($_P['user'] == $_USER['usid'] || $new_rank == $_U['rank']) { $new_rank = $_U['rank']; }
      elseif(!is_numeric($new_rank) || $new_rank > 5 || $new_rank < 0) {
        $error .= 'A rang szám kell, hogy legyen 0-5 között, és a saját rangodat nem állíthatod át. ';
      }

      //jelszó módosítás
      $new_pass = ($_P['pass-'.$_P['user']]) ? ($_P['pass-'.$_P['user']]) : '';
      if($new_pass != '' && (strlen($new_pass) > 32 || strlen($new_pass) < 3)) {
        $error .= 'A jelszó min. 3, max. 32 karakteres legyen. ';
      }

      $new_passhash = ($new_pass == '') ? $_U['passhash'] : ident($_U['username'], $new_pass);

      //email módosítás
      $new_email = ($_P['email-'.$_P['user']]) ? ($_P['email-'.$_P['user']]) : $_U['email'];
      if(!preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $new_email)) {
        $error .= ' Érvénytelen email címet adtál meg.';
      }

      if($error == '') { mysql_query("UPDATE `users` SET `confirm` = '$new_confirm', `rank` = '$new_rank', `passhash` = '$new_passhash', `email` = '$new_email' WHERE `usid` = '{$_P['user']}'"); }
    }
  }

  //adminból userek sorrendezésének basztatása
  if(isset($_G['users']) && $_USER['rank'] > 3) {
    switch($_G['users']) {
      case 'username': $sort = 'username';
        break;
      case 'email': $sort = 'email';
        break;
      case 'rank': $sort = 'rank';
        break;
      case 'confirm': $sort = 'confirm';
        break;
      default: $sort = 'registered';
    }
    $order = ($_G['order'] == 'asc') ? 'ASC' : 'DESC';

    $query3 = "SELECT * FROM `users` ORDER BY `$sort` $order LIMIT 0, 100";
    $users = mysql_query($query3);
    $resultnum = mysql_num_rows($users);
    
    include 'users.php';
  }
  
  //üzenet törlése
  elseif($_USER['rank'] > 1 && isset($_G['del']) && is_numeric($_G['del'])) {
    $query2 = "DELETE FROM `messages` WHERE `meid` = '{$_G['del']}'";
    mysql_query($query2);
    header("Location: board.php");
  }
  
  //üzenet hozzáadása
  elseif(isset($_P['send_message']) && $_P['text'] != '') {
    $time = time();

    //űőŰŐ --> &#369;&#337;&#368;&#336;
    //$msg_text = str_replace(array('ű','ő','Ű','Ő'),array('&#369;','&#337;','&#368;','&#336;'),$_P['text']);//QFix
    //$msg_text = str_replace(array('ű','ő','Ű','Ő'), array('&#369;','&#337;','&#368;','&#336;'), utf8_decode($_P['text']));
    //if($_USER['usid'] == 1) { die('<pre>'.print_r(array($_P, $_POST, $msg_text), true).'</pre>'); }//dbg
    //$msg_text = utf8_decode($_P['text']);
    //$msg_text = htmlentities($_P['text'], ENT_QUOTES, 'UTF-8');
    //$msg_text = htmlentities($_POST['text'], ENT_QUOTES);
    //$msg_text = utf8_decode(htmlentities($_POST['text'], ENT_QUOTES));
    $msg_text = $_P['text'];

    $query1 = "INSERT INTO `messages` (`meid`, `usid`, `username`, `message`, `sent`, `usid_rank`) VALUES (NULL, '{$_USER['usid']}', '{$_USER['username']}', '$msg_text', '$time', '{$_USER['rank']}')";
    mysql_query($query1);
    header("Location: board.php");//frissitésre ne küldözgesse újra a szarokat
  }
  
  //jelszó csere
  elseif(isset($_P['newpass1']) || isset($_P['newpass2'])) {
    
    if($_P['newpass1'] == $_P['newpass2']) {
      if(strlen($_P['newpass1']) > 32 || strlen($_P['newpass1']) < 3) {
        $error .= ' Érvénytelen jelszó.';
      }
    }
    else {
      $error .= ' Az új jelszó megerősítése helytelen.';
    }
    
    if($error == '') {
      $_USER['passhash'] = ident($_USER['username'], $_P['newpass1']);
      mysql_query("UPDATE `users` SET `passhash` = '{$_USER['passhash']}' WHERE `usid` = '{$_USER['usid']}'");
      
      addCookie($_USER['usid'], $_USER['passhash']);
    }
    
    include 'constructor.php';
  }
  else {
    include 'constructor.php';
  }
}
else {
  //bejelentkezés
  if(isset($_P['login'])) {
    $passhash = ident($_P['username'], $_P['password1']);
    $query = "SELECT * FROM `users` WHERE `username` = '{$_P['username']}' AND `passhash` = '$passhash' AND `confirm` = ''";
    $result = mysql_query($query);
    $resultnum = @mysql_num_rows($result);

    if($resultnum < 1 || $_P['username'] == '' || $_P['password1'] == '') {
      $error = 'Hibás felhasználónév / jelszó vagy nincs megerősítve a felhasználó.';
      include 'constructor.php';
    }
    else {
      $_USER = mysql_fetch_array($result, MYSQL_ASSOC);
      addCookie($_USER['usid'], $_USER['passhash']);
      header("Location: board.php");
    }
  }
  
  //regisztrálás
  elseif(isset($_P['register'])) {
  
    if(!preg_match('/^[A-Za-z0-9]{3,32}$/', $_P['username'])) {
      $error .= ' Érvénytelen felhasználónév.';
    }
    if(!preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $_P['email'])) {
      $error .= ' Érvénytelen email cím.';
    }
    if(strlen($_P['password1']) > 32 || strlen($_P['password1']) < 3 || $_P['password1'] != $_P['password2']) {
      $error .= ' Érvénytelen jelszó.';
    }

    if($error == '') {
      $query1 = "SELECT * FROM `users` WHERE `username` = '{$_P['username']}' OR `email` = '{$_P['email']}'";
      $result = mysql_query($query1);
      $resultnum = @mysql_num_rows($result);
      
      if($resultnum > 0) {
        $error .= 'A felhasználónév, vagy az email foglalt.';
      }
      else {
        $passhash = ident($_P['username'], $_P['password1']);
        $time = time();
        $confirm = md5(microtime() * rand());
        
        $headers ='From: Presidance <reisztracio@presidance.com>'."\r\n"
          .'Reply-To: noreplay@presidance.com'."\r\n"
          .'Return-Path: noreplay@presidance.com'."\r\n"
          .'X-Mailer: PHP/'.phpversion()."\r\n";
        $message = 'Kedves '.$_P['username'].','."\n"
          .'kérlek másold az alábbi linket be a böngészősávba, hogy megerősítsd'."\n"
          .'a regisztrációd a www.presidance.com oldalon.'."\n"
          .'Ha nem te regisztráltál az alábbi email címmel / ip-vel akkor kérlek'."\n"
          .'hagyd figyelmen kívül ezt a levelet.'."\n\r"
          .'Email: '.$_P['email'].' / Ip: '.$_SERVER['REMOTE_ADDR']."\n\r"
          .'http://presidance.com/uzeno/board.php?username='.$_P['username'].'&confirm='.$confirm."\n\r"
          .'Erre az üzenetre légyszíves ne válaszolj.'."\n"
          .'Üdv.'."\n\n";

        mail($_P['email'], 'Regisztrálás megerősítése!', $message, $headers) or $error .= ' A megerősítő email-t nem sikerült elküldeni, kérlek lépj kapcsolatba az oldal egyik adminisztrátorával.';

        $query2 = "INSERT INTO `users` (`usid`, `username`, `passhash`, `email`, `rank`, `confirm`, `registered`) VALUES (NULL, '{$_P['username']}', '$passhash', '{$_P['email']}', '0', '$confirm', '$time')";
        mysql_query($query2);
        
        if($error == '') { $error .= 'Sikeres regisztráció. Ellenőrizd az email fiókodat. (a spam mappádat is)'; }
      }
    }
    
    include 'constructor.php';
  }
  
  //jelszóemlékeztető
  elseif(isset($_P['newpass'])) {
    
    $query1 = "SELECT * FROM `users` WHERE `confirm` = '' AND `username` = '{$_P['username']}' AND `email` = '{$_P['email']}'";
    $result = mysql_query($query1);
    $resultnum = @mysql_num_rows($result);
    
    if($resultnum > 0) {
      $_USER = mysql_fetch_array($result, MYSQL_ASSOC);
      
      $new_pass = substr(md5(microtime() * rand()), mt_rand(0, 26), 6);
      $new_passhash = ident($_USER['username'], $new_pass);
      
      $headers ='From: Presidance <jelszoemlekezteto@presidance.com>'."\r\n"
        .'Reply-To: noreplay@presidance.com'."\r\n"
        .'Return-Path: noreplay@presidance.com'."\r\n"
        .'X-Mailer: PHP/'.phpversion()."\r\n";
      $message = 'Kedves '.$_USER['username'].','."\n"
        .'te vagy valaki a te nevedben és az email címeddel'."\n"
        .'jelszócserét kezdeményezett a www.presidance.com oldalon.'."\n\r"
        .'Új jelszavad: '.$new_pass."\n"
        .'(Belépés után azonnal meg tudod változtatni.)'."\n\r"
        .'A kérés erről az IP-ről történt: '.$_SERVER['REMOTE_ADDR']."\n\r"
        .'Erre az üzenetre légyszíves ne válaszolj.'."\n"
        .'Üdv.'."\n\n";

      mail($_P['email'], 'Jelszó csere!', $message, $headers) or $error .= ' A jelszavadat tartalmazó email-t nem sikerült elküldeni, kérlek lépj kapcsolatba az oldal egyik adminisztrátorával.';
      
      mysql_query("UPDATE `users` SET `passhash` = '$new_passhash' WHERE `usid` = '{$_USER['usid']}'");
      
      if($error == '') { $error = 'Az új jelszavadat elküldtük az email címedre.'; }
      
      $_USER = false;
    }
    else {
      $error = 'Nincs ilyen aktivált felhasználónév / email páros.';
    }
    
    include 'constructor.php';
    
  }
  
  //regisztráció megerősítése
  elseif(isset($_G['username']) && isset($_G['confirm'])) {
    $query1 = "SELECT * FROM `users` WHERE `username` = '{$_G['username']}' AND `confirm` = '{$_G['confirm']}'";
    $result = mysql_query($query1);
    $resultnum = @mysql_num_rows($result);
    
    if($resultnum > 0) {
      $_USER = mysql_fetch_array($result, MYSQL_ASSOC);
      mysql_query("UPDATE `users` SET `confirm` = '' WHERE `usid` = '{$_USER['usid']}'");
      
      $error = 'A megerősítés sikeres, máris átirányítunk a kezdőoldalra.';
      
      
    }
    else {
      $error = 'A megerősítés sikertelen, nincs ilyen felhasználónév / megerősítőkód.';
    }
    
    $_USER = false;
    $just_confirmed = true;
    
    include 'constructor.php';
  }
  else {
    include 'constructor.php';
  }
}



?>