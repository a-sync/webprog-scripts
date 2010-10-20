<?php
// GM Log 1.02
// by Vector Akashi

// TODO: microstat not used, sql error handling, report from sql query's  (delete, ), better pager, anonym links, ip lookup and other info links, GROUP BY in index search to get proper number of rows for a page

//error_reporting(0); // first things first, 
error_reporting(E_ALL ^ E_NOTICE); // first things first, 

// settings
$pass = 'alma';
$limit = 500;

// users

$USERS[1] = 'Smith*';
$USERS[2] = 'John';
$USERS[1266506481] = 'Pink';

// avatars

$gravatar_type = 'monsterid'; // identicon / monsterid / wavatar 
$identicon_link = 'http://www.gravatar.com/avatar/'; // identicon link 
$identicon_end = '?s=30&r=any&default=' . $gravatar_type . '&forcedefault=1&.png'; // identicon 

// mysql

$defcon1 = @mysql_connect('localhost', 'redir', '13swork');// or die('01 - Failed to connect to the server!');
@mysql_select_db('redir_onethreestudio', $defcon1);// or die('02 - Failed to connect to the database!');
@mysql_query("SET NAMES utf8");

// functions

function sql($q)
{
  $q = @mysql_query($q);
  //if(!$q) die( 'SQL error ['.mysql_errno().'] :: '.mysql_error() );
  if(!$q) die('</body></html>');
  
  return $q;
}

function microstat($table) {
  $q = @mysql_query("SELECT * FROM `{$table}`");
  
  echo '<table width="100%" bgcolor="gray" border="0" bordercolor="gray" cellspacing="2" cellpadding="4">';
  $n = 0;
  
  while(false !== ($row = @mysql_fetch_assoc($q))) {
    $header = '<tr bgcolor="gray">';
    $inner = '<tr>';
    
    foreach($row as $i => $val) {
      if($n == 0) $header .= '<td>'.$i.'</td>';
      $inner .= '<td bgcolor="lavender">'.$val.'</td>';
    }
    if($n == 0) echo $header.'</tr>';
    $n++;
    
    echo $inner.'</tr>';
  }
  echo '</table>';
  echo '<span>Table: `'.$table.'` :: '.(($n < 1) ? 'No' : $n).' '.(($n > 1 || $n < 1) ? 'rows.' : 'row.').'</span>';
  
  //add pw stat :)
  //@mysql_query("INSERT INTO `redir_options` (`opid`, `type`, `text`, `num`) VALUES ('password1', '0', '', '0')");
  //@mysql_query("UPDATE `redir_options` SET `num` = `num` + 1 WHERE `opid` = 'password1' LIMIT 1");
}

// # program starts here

// login
if(isset($_POST['login']) && $_POST['pass'] == $pass)
{
  $_COOKIE['lo'] = md5($pass);
  setcookie('lo', md5($pass), 0);
}
elseif(isset($_GET['logout']))
{
  setcookie('lo', '', (-3600 * 24));
  $_COOKIE['lo'] = '';
  
  header("Location: ".md5('lol').".php");
  exit;
}

// auto refresh
$_COOKIE['ref'] = substr($_COOKIE['ref'], 0, 16);
if(ctype_digit($_POST['refresh']) && $_POST['refresh'] > 0)
{
  $_POST['refresh'] = substr($_POST['refresh'], 0, 16);
  setcookie('ref', $_POST['refresh'], 0);
  $_COOKIE['ref'] = $_POST['refresh'];
}
elseif($_POST['refresh'] == '0')
{
  setcookie('ref', '', (-3600 * 24));
  $_COOKIE['ref'] = '';
}

// output buffer starts here

// only needed if logged in
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">

<!--
html, body
{

text-align: center;
font-family: Arial, serif;
font-size: 11px;
color: #dadada;
background-color: #2a2a2a;
padding: 0;
margin: 0;
}

a:link, a:visited
{
color: #e0e0e0;
text-decoration: none;
}
a:hover, a:active
{
color: #30d020;
text-decoration: none;
}
a:focus
{

}

#container
{
width: 90%;
padding: 10px;
margin: 10px auto;
text-align: left;
 -moz-border-radius: 7px;
 -webkit-border-radius: 7px;
 border-radius: 7px;
background-color: #323232;
}

#rows
{
width: 100%;
padding: 0;
margin: 0;
}

#rows tr.head
{
padding: 0;
margin: 0;
background-color: #1e1e1e;
}

#rows tr.head th
{
text-align: center;
font-weight: bold;
font-size: 16px;
padding: 5px 0 5px 0;
margin: 0;
}

#rows tr.row
{
padding: 0;
margin: 0;
background-color: #282828;
font-size: 11px;
}

#rows tr.row td
{
border-top: 2px solid #323232;
border-bottom: 2px solid #323232;
padding: 4px;
}

#rows tr.row td.id
{
padding: 4px 0px 4px 2px;
text-align: center;
}

#rows tr.row td.id img
{
opacity: 0.9;
}

#rows tr.row td.time
{
font-size: 10px;
text-align: center;
color: #bababa;
padding: 4px 2px 4px 0px;
}

#rows tr.row td.time span
{
color: #eaeaef;
font-weight: bold;
font-size: 14px;
}

#rows tr.row td.time div
{
color: #b0b0b0;
display: none;
}

#rows tr.row td.link
{

}

#rows tr.row td.link span.domain
{
color: #4a8ad0;
font-size: 10px;
font-family: Tahoma, Verdana, serif;
}

#rows tr.row td.link h2
{
font-size: 13px;
font-weight: bold;
padding: 0;
margin: 1px 0 1px 0;
}

#rows tr.row td.link h2 a
{
display: inline;
}

#rows tr.row td.link h2 a:visited
{
color: #e0d0a0;
}
#rows tr.row td.link h2 a:hover
{
color: #30d020;
}

#rows tr.row td.link a
{
font-size: 11px;
display: none;
}

#rows tr.row td.link a.last_link
{
padding-left: 16px;
}

#rows tr.row td.link span.domain a
{
display: inline;
}

#rows tr.row td.link span.domain a:visited
{
color: #e0d0a0;
}
#rows tr.row td.link span.domain a:hover
{
color: #30d020;
}

#rows tr.row td.link div.session
{
display: none;
}

#rows tr.row td.user
{
text-align: right;
}

#rows tr.row td.user h3
{
color: #b0b0b0;
font-size: 12px;
font-weight: bold;
padding: 0;
margin: 0 0 1px 0;
}

#rows tr.row td.user span
{
font-size: 10px;
padding: 0 0 0 2px;
display: none;
}

#rows tr.row:hover
{
background-color: #222222;
}

#rows tr.row:hover td.id img
{
width: auto;
height: auto;
}

#rows tr.row:hover td.time div
{
display: block;
}

#rows tr.row:hover td.link a
{
display: inline;
}

#rows tr.row:hover td.link div.session
{
display: block;
}

#rows tr.row:hover td.user span
{
display: inline;
}

#auto_refresh
{
text-align: left;
margin: 0;
}

#auto_refresh .input_text, #auto_refresh .input_submit
{
color: #b0b0b0;
border: 1px solid #404040;
background-color: #202020;
text-align: center;
}

#auto_refresh .input_submit
{
cursor: pointer;
}

.pager
{
text-align: center;
font-weight: bold;
font-size: 13px;
padding: 3px 2px;
}

#head
{
font-family: Tahoma, Verdana, serif;
font-size: 22px;
font-weight: bold;
margin: 0 0 10px 0;
}

#rows .del:link, #rows .del:visited
{
font-size: 11px;
font-weight: normal;
color: darkred;
}
#rows .del:hover
{
color: #30d020;
}

div.session
{
padding: 1px;
}

div.session div.session_row del
{

}

div.session div.session_row span
{
color: #60a0d0;
}

div.session div.session_row a
{

}

div.session div.session_row div.session_link
{
padding-left: 16px;
/*display: none;*/
}

div.session div.session_row:hover div.session_link
{
display: block;
}

#counter
{
font-family: Tahoma, Verdana, serif;
font-weight: bold;
padding-left: 10px;
}

-->

</style>
<?php 
// auto refresh
if(ctype_digit($_COOKIE['ref']) && $_COOKIE['ref'] > 0)
{ 
  echo '<meta http-equiv="refresh" content="'.$_COOKIE['ref'].'" />';
?>
<script type="text/javascript">
<!--
if(window.attachEvent) window.attachEvent('onload', counter);
else window.addEventListener('load', counter, false);

var span = false;
var i = false;
function counter(e)
{
  span = document.getElementById('counter');
  var n = parseInt(span.innerHTML);
  
  if(!span) return false;
  else
  {
    if(isNaN(n))
    {
      span.innerHTML = <?php echo $_COOKIE['ref']; ?>;
      i = setInterval('counter();', 1000);
    }
    else if(n > 0)
    {
      span.innerHTML = (n - 1);
    }
    else
    {
      clearInterval(i);
    }
  }
}

-->
</script>
<?php
}

// head ends here
?>
</head>
<body>
<div id="container">
<?php
// login passed
if($_COOKIE['lo'] == md5($pass))
{
?>
  <div id="head">
    <a href="?">GMlog</a>
  </div>
  <form id="auto_refresh" action="" method="post">
    <a href="?" accesskey="h">Home</a> &nbsp; | &nbsp; <a href="myscript.user.js.php?myscript.user.js">UserScript</a> &nbsp; | &nbsp; <a href="about:blank" accesskey="b">about:blank</a> &nbsp; | &nbsp; <a href="?logout" accesskey="l">Logout</a>
    <br/>
    <br/>
    Auto-refresh: <input title="Seconds..." class="input_text" size="4" type="text" name="refresh" value="<?php echo (ctype_digit($_COOKIE['ref'])) ? htmlspecialchars($_COOKIE['ref']) : 0; ?>" maxlength="6" /> <input class="input_submit" type="submit" value="Set" /> <span id="counter"> </span>
  </form>
<?php
  // delete entry
  if(ctype_digit($_GET['del']))
  {
    sql("DELETE FROM `gmlog` WHERE `time` = '".mysql_real_escape_string($_GET['del'])."' LIMIT 1;");
  }

  // index listing starts here
  $q = "SELECT * FROM `gmlog` ";
  
  $w = "WHERE 1 = 1 ";
  if(ctype_digit($_GET['id']))
  {
    $w .= "AND `id` = '".mysql_real_escape_string($_GET['id'])."' ";
  }
    
  $o = "ORDER BY `time` DESC, `domain` ASC ";
  
  $_GET['p'] = substr($_GET['p'], 0, 16);//no mysql fuckups
  $p = (ctype_digit($_GET['p']) && $_GET['p'] >= 0) ? $_GET['p'] : 0;
  $l = "LIMIT ".($p * $limit).", {$limit} ;";
  
  $rows = sql($q.$w.$o.$l);
  //microstat('gmlog');
  $_GET['id'] = substr($_GET['id'], 0, 16);
  $pager = '<div class="pager"><a href="?p='.($p - 1).((ctype_digit($_GET['id'])) ? '&amp;id='.htmlspecialchars($_GET['id']) : '').'">&lt;&lt;</a> &nbsp; '.($p * $limit).' - '.(($p + 1) * $limit).' &nbsp; <a href="?p='.($p + 1).((ctype_digit($_GET['id'])) ? '&amp;id='.htmlspecialchars($_GET['id']) : '').'">&gt;&gt;</a></div>';
  
  echo $pager;
  
  echo '<table id="rows" cellspacing="0">';
  
  echo '<tr class="head">';
    echo '<th width="40">id</th>';
    echo '<th width="70">time</th>';
    echo '<th>title / link</th>';
    echo '<th width="300">user</th>';
  echo '</tr>';
  
  $filtered = array();
  $prev = false;
  $sprev = false;
  while(false !== ($row = mysql_fetch_assoc($rows)))
  {
    if($prev != false && $row['id'] == $prev['id'] && $row['ip'] == $prev['ip'] && $row['domain'] == $prev['domain'] && $row['browser'] == $prev['browser'])
    {
      if(($sprev == false || $row['link'] != $sprev['link']) && $row['link'] != $prev['link'])
      {
        $filtered[$prev['time']]['session'][$row['time']] = array('time' => $row['time'], 'ip' => $row['ip'], 'title' => $row['title'], 'link' => $row['link']);
      }
      $sprev = $row;
    }
    else
    {
      $filtered[$row['time']] = $row;
      $prev = $row;
      $sprev = false;
    }
  }
  unset($rows);
  unset($prev);
  unset($sprev);
  
  foreach($filtered as $row)
  {
    $s = count($row['session']);
    echo '
      <tr class="row">
        <td class="id" valign="top">
          <img src="'.$identicon_link.md5((isset($USERS[$row['id']])) ? $USERS[$row['id']] : $row['id']).$identicon_end.'" alt="'.$row['host'].'" title="'.$row['host'].'" />
        </td>
        <td class="time" valign="top">
          <span class="name">'.date('H:i:s', $row['time']).'</span>
          <br/>
          '.date('Y-m-d', $row['time']).'
          <div>
            <a href="?id='.$row['id'].'&amp;p='.((ctype_digit($_GET['p']) && $_GET['p'] >= 0) ? htmlspecialchars($_GET['p']) : '0').'">
            '.((isset($USERS[$row['id']])) ? $USERS[$row['id']] : $row['id']).'
            </a>
          </div>
        </td>
        <td class="link" valign="top">
          <span class="domain">
            <a target="_blank" href="http://'.$row['domain'].'">'.$row['domain'].'</a>
            '.(($s > 0) ? '&nbsp;('.($s + 1).')' : '').'
          </span>';

    if($s > 0)
    {
      array_unshift($row['session'], $row);
      
      echo '<div class="session">';
      foreach($row['session'] as $s)
      {
        echo 
        '<div class="session_row">
          <a class="del" href="?del='.$s['time'].'&amp;p='.((ctype_digit($_GET['p']) && $_GET['p'] >= 0) ? htmlspecialchars($_GET['p']) : '0').'">[x]</a> 
          <span title="'.date('Y-m-d', $s['time']).'">'.date('H:i:s', $s['time']).'</span> 
          <a title="'.$s['link'].'" href="'.$s['link'].'" target="_blank">'.(($s['title'] == '') ? $s['link'] : $s['title']).'</a>
          
        </div>';
        //<div class="session_link"><a href="'.$s['link'].'" target="_blank">'.$s['link'].'</a></div>
      }
      echo '</div>';
    }
    else
    {
      echo '<h2>
              <a class="del" href="?del='.$row['time'].'&amp;p='.((ctype_digit($_GET['p']) && $_GET['p'] >= 0) ? htmlspecialchars($_GET['p']) : '0').'">[x]</a> 
              <a href="'.$row['link'].'" target="_blank">'.(($row['title'] == '') ? $row['link'] : $row['title']).'</a>
            </h2>
            <a class="last_link" title="'.$row['title'].'" href="'.$row['link'].'" target="_blank">'.$row['link'].'</a>';
    }

    echo '</td>
        <td class="user" valign="top">
          <h3 title="'.$row['host'].'">'.$row['ip'].'</h3>
          <span>'.$row['browser'].'</span>
        </td>
      </tr>
    ';
  }

  echo '</table>';
  
  echo $pager;
  
}
// login page
else
{
  echo '<form action="" method="post"><input type="password" autocomplete="off" value="" name="pass" /> <input type="submit" name="login" /></form>';
}
?>
</div>
</body>
</html>
