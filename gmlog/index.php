<?php
// GM Log 1.02
// by Vector Akashi

// TODO: header for the gif against caching, add cookie saving (c=)

// use cockblock to put the service on inactive
// -- cockblock start -- 
header("Content-Type: image/gif");
die(base64_decode("R0lGODlhAQABAIAAAP///wAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw=="));
// -- cockblock end --

error_reporting(0); // first things first, 
//error_reporting(E_ALL ^ E_NOTICE); // first things first, 
die(' die( ); ');

// mysql
$defcon1 = @mysql_connect('localhost', 'redir', '13swork');// or die('01 - Failed to connect to the server!');
@mysql_select_db('redir_onethreestudio', $defcon1);// or die('02 - Failed to connect to the database!');
@mysql_query("SET NAMES utf8");

/*
--
CREATE TABLE IF NOT EXISTS `gmlog` (
  `time` int(10) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL default '0',
  `title` text collate utf8_unicode_ci NOT NULL,
  `link` text collate utf8_unicode_ci NOT NULL,
  `domain` tinytext collate utf8_unicode_ci NOT NULL,
  `referer` text collate utf8_unicode_ci NOT NULL,
  `browser` text collate utf8_unicode_ci NOT NULL,
  `ip` tinytext collate utf8_unicode_ci NOT NULL,
  `host` tinytext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
*/

$BAN = array('redir.at');


$LOG = false;
$BAN = array_flip($BAN);
if(ctype_digit($_GET['i']) && $_GET['i'] > 0) // dbg
{
  $LOG['time'] = time();

  $LOG['id'] = $_GET['i'];
  $LOG['title'] = mysql_real_escape_string(urldecode(utf8_encode($_GET['t'])));
  $LOG['link'] = parse_url(urldecode(utf8_encode($_GET['l'])));
  
  $LOG['domain'] = mysql_real_escape_string(htmlspecialchars(($LOG['link']['host'] != '') ? $LOG['link']['host'] : ''));
  if(!isset($BAN[$LOG['domain']]) && $LOG['domain'] != '')
  {
    $LOG['referer'] = ($_SERVER['HTTP_REFERER'] == '') ? '' : mysql_real_escape_string(serialize(parse_url($_SERVER['HTTP_REFERER'])));
    $LOG['browser'] = mysql_real_escape_string(htmlspecialchars($_SERVER['HTTP_USER_AGENT']));
    
    $LOG['ip'] = (!filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) ? '0.0.0.0' : $_SERVER['REMOTE_ADDR'];
    $LOG['host'] = gethostbyaddr($LOG['ip']);
    
    //$LOG['link'] = mysql_real_escape_string(serialize($LOG['link']));
    $LOG['sql_link'] = $LOG['link']['scheme'].'://';
    $LOG['sql_link'] .= $LOG['link']['host'];
    if($LOG['link']['port'] != '') $LOG['sql_link'] .= ':'.$LOG['link']['port'];
    if($LOG['link']['path'] != '') $LOG['sql_link'] .= $LOG['link']['path'];
    if($LOG['link']['query'] != '') $LOG['sql_link'] .= '?'.$LOG['link']['query'];
    $LOG['sql_link'] = mysql_real_escape_string(htmlspecialchars($LOG['sql_link']));
    
    /*
    header('Content-type: text/plain');
    echo file_put_contents('dbg.log', print_r($LOG, true)."\r\n", FILE_APPEND);
    //print_r($LOG);
    exit;
    */
    
    //sql
    @mysql_query("INSERT INTO `gmlog` (`time`, `id`, `title`, `link`, `domain`, `referer`, `browser`, `ip`, `host`) VALUES ('{$LOG['time']}', '{$LOG['id']}', '{$LOG['title']}', '{$LOG['sql_link']}', '{$LOG['domain']}', '{$LOG['referer']}', '{$LOG['browser']}', '{$LOG['ip']}', '{$LOG['host']}')");
    //exit;
  }
}

// one and only output 1x1 transparent gif (mostly preloaded by javascript)
header("Content-type: image/gif");
die(base64_decode("R0lGODlhAQABAIAAAP///wAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw=="));
?>