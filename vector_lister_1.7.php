<?php error_reporting(E_ALL);
#########################
### Vector Lister 1.7 ###
### vector@devs.space ###
#########################
// config ###########################################################
$pwd = '';              // password protection
$name='Vector Lister';  // title of the site
$root='';               // directory to list (same as the script file by default)
$ignoredf1='';          // 1. ignored file (eg.: 'index1.html' or 'files/me.jpg' etc.)
$ignoredf2='';          // 2. -"-
$ignoredf3='';          // 3. -"-
$ignoredt1='';          // 1. ignored file type (eg.: 'php' etc.)
$ignoredt2='';          // 2. -"-
$ignoredt3='';          // 3. -"-
$ignoredd1='';          // 1. ignored directory (eg.: 'files' or 'files/secret' etc.)
$ignoredd2='';          // 2. -"-
$ignoredd3='';          // 3. -"-
$date='Y.m.d. - H:i';   // date format (PHP date())
$target='';             // target of file links (eg.:'_blank' for new window or leave it blank for default)
$incl=0;                // if you insert the script to another file, set it to 1
$bottom='&copy; '.$name;// stuff under the lister (html)
// config end #######################################################
if($pwd){$time=time()+60*10;//10 min cookies
    if(!$_COOKIE['t'])$_COOKIE['t']=0;
    if($_COOKIE['p']!=$pwd){if(!$_POST['send']&&$_COOKIE['t']<3)die('<!DOCTYPE html><html><body><form action="'.$_SERVER['PHP_SELF'].'" method=post>pass:<input type=password name=p size=8><input type=submit name=send></form></body></html>');
    elseif($_POST['p']==$pwd)setcookie('p',$_POST['p'],$time,'/',$_SERVER['HTTP_HOST']);
    else{setcookie('t',++$_COOKIE['t'],$time,'/',$_SERVER['HTTP_HOST']);die("<!DOCTYPE html><html><body><b>Error:</b> Invalid pass.<br><a href=javascript:window.history.back()><b>Back</b></a></body></html>");}}
}
$burl=$_SERVER['SCRIPT_NAME'];
$css=<<<EOT
body,td{font-family:tahoma,verdana,arial;font-size:12px;line-height:15px;background-color:#000000;color:#ae2233;margin-left:20px;}
strong{font-size:12px;}
a{text-decoration:none;}
a:link{color:#ffffff;}
a:hover{color:#ffffff;}
a:visited{color:#ffffff;}
a:active{color:#999999;}
table.itable{}
td.irows{height:20px;background:url("$burl?i=_dots_") repeat-x bottom}
EOT;
// todo: admin panel; kivetelek arrayba; ujrakodolni a kepeket;
// bug: '0' nevu mappa nem latszodik felul;
function e($s){$p=strrpos($s,'.');return substr($s,$p+1,strlen($s));}
function s($a,$c,$o='asc',$t=SORT_STRING){if(!isset($a[0][$c]))return $a;for($i=0;$i<count($a);$i++)$temp[$i]=&$a[$i][$c];$o=($o=='asc')?SORT_ASC:SORT_DESC;$temp2=array_map('strtolower',$temp);array_multisort($temp2,$o,$t,$a);return $a;}
function fs($size){$count=0;$format=array('b','Kb','MB','GB','TB','PB','EB','ZB','YB');while(($size/1024)>1&&$count<8){$size=$size/1024;$count++;}if($format[$count]!='b')$decimals=1;else$decimals=0;$return=number_format($size,$decimals,'.',' ').'&nbsp;'.$format[$count];return $return;}
if(isset($_GET['i']))
{
    $i=strtolower(trim($_GET['i']));
    $imgs['binary']='iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAAElBMVEX////AwMAAAACAgID///8AAL8pzkw4AAAAAXRSTlMAQObYZgAAAAFiS0dEBI9o2VEAAAAWdEVYdFNvZnR3YXJlAGdpZjJwbmcgMi40LjakM4MXAAAALklEQVR42mNggAFjKGAwFAQDIwbDUDCAMJycnIAMiBIgwwUMyGUYw8xRggK4KwAN+BkEjXuBLQAAAABJRU5ErkJggg==';
    $imgs['exe']=&$imgs['binary'];$imgs['cmd']=&$imgs['binary'];$imgs['cab']=&$imgs['binary'];$imgs['msc']=&$imgs['binary'];$imgs['bat']=&$imgs['binary'];$imgs['com']=&$imgs['binary'];$imgs['dll']=&$imgs['binary'];$imgs['msi']=&$imgs['binary'];
    $imgs['compressed']='iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAABPlBMVEX////etQFhqOuLyvnxGQHf9P/H7P2Ew/P667P73WQjX7Asarv81DH0///7887755e0z/bTqR7kzYP84n392UfatCrVpQurbQ1mrO3buTN1seR7vPFot//83m366KG95//b8P/j9f//+5ohW612t/DPoRD/+pDSoAfI2PbX8P/92lGYwvWyeyNEk+Tz5G3374LSphTUpxu4hCiqbQ3aujX2zSyBwPKCxP+JyffUqR7Uowj78L3//aTUqiHVphCt2/n+0zjevjubz/UnZrhuse4warH91TlTqvvUqSB4vv9yu/+sdB3Zvyr6449bmdfZtylhs/9ysebZvyz66a2Iw/PZvC/69ZD82Vj73nT/+IbVphbYrii85fsoZbZ1t/Bitf/59uXZtzG0fA/85IX47sqyfQ4naccIJ2P////PoQ7NL84fAAAAAXRSTlMAQObYZgAAAAFiS0dEaMts9CIAAAAWdEVYdFNvZnR3YXJlAGdpZjJwbmcgMi40LjakM4MXAAAAuUlEQVR42mNgAAN2M+k4FwluEFMoMzNTiImXLcbeKUQlHSiQycLCkpkGBrrxfkARVVF/n6DQREkTx6R0eSmggG0GENgoqUXqpaZrsgMFLDMSUoJ9I8IdGFPTWZmBAoIZfBz8wpwiPDgFPMKs5ZJltdxMgQKKFkABA/1owyg7MSt1oIBCIFDASEfc2BvijnRz13SIwxmYeGUCvDzdnWF8oAh3LBeXMoIPBukofCYGAW0BDTaEWBrUXAYAT+wtHYLRy84AAAAASUVORK5CYII=';
    $imgs['zip']=&$imgs['compressed'];$imgs['rar']=&$imgs['compressed'];$imgs['z']=&$imgs['compressed'];$imgs['torrent']=&$imgs['compressed'];$imgs['bak']=&$imgs['compressed'];$imgs['tar']=&$imgs['compressed'];$imgs['mdf']=&$imgs['compressed'];$imgs['bin']=&$imgs['compressed'];$imgs['image']=&$imgs['compressed'];$imgs['cdi']=&$imgs['compressed'];$imgs['mds']=&$imgs['compressed'];$imgs['cue']=&$imgs['compressed'];$imgs['nrg']=&$imgs['compressed'];$imgs['img']=&$imgs['compressed'];$imgs['iso']=&$imgs['compressed'];$imgs['ace']=&$imgs['compressed'];$imgs['lha']=&$imgs['compressed'];$imgs['uc2']=&$imgs['compressed'];$imgs['arj']=&$imgs['compressed'];$imgs['bz']=&$imgs['compressed'];$imgs['7z']=&$imgs['compressed'];$imgs['gz']=&$imgs['compressed'];$imgs['tgz']=&$imgs['compressed'];$imgs['jar']=&$imgs['compressed'];
    $imgs['images']='iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAB41BMVEX////5/P/+/v9jbHvM1f9EpveKkJz0+v+l0f21yPKVmcT9qlT/dTeQga+vw/DqWSrlxoK0t7+Xx/arwfCWnMaTxPbz8/Sp0PbF4v+22v91pd7/wWG9zfNdZXa6yfSl0v/qhz+BnM/3vmW3g3Bud4eKgbaTgqz/5oC3OjJ0qOZ3tOr3bTWRrc7tynzbkFfW6/+3yfLkwH673f+5YDFzgrWaqNbR6P1jQy6qs/X/j0aXpNLVQh+DqdT21HpvNCjZuoa7v8iIqc59lciLkbamhpT/tVqlq7bIOxzu8PK3fVPi8f+TmqbgkV7Tn3RYQUTB4P/Zk2CyNRPzjDdkbXzBkH3/rFX29vb1izd7hJSkzfZ8p9q8wMff5uy6zPP/z3bsgzXbSSKLjrhic4t6j8L654bmmVSaWzJ+iLNsabiq1P+6iHr/lkmYptPM5v+cyfa1l5P/dznQ0tj/3ZlCk+Dt9v+PfaWgy/bV3+jb4uqlve6ov++xtb2u0vaEo9ZvfK7R6P+Zoczp7PD/h1b6+vp2iLuYksLc7v/CnJXk6e6TlsGIq97uXSyxxfHR2+aHncedjKisl6z323+x2P/4+PiDotXjxIKgYiydrdmXnsn/n3jXoX+qkJuvs7tXYHGHqdz///9YYXJ9mid1AAAAAXRSTlMAQObYZgAAAAFiS0dEn+i1k50AAAAWdEVYdFNvZnR3YXJlAGdpZjJwbmcgMi40LjakM4MXAAAA3klEQVR42mNgmAcGXbXzGKBg3nwQYGKcp5XkhhBgYmRnZS2OcAALNIP5JSwsFunehSCByTO1eSxDpb3a9OVUokECYU3GBdmuRXHcOjKy1SABMQ27QHdr/u6Evti5c0ACHj5p0+KDlMIVHA3AAlMaZ3ma66XaTgyw4QQLKHa0Z/kp+wrohkT1gAWcYiaUZrhMTVHPl+IDCyRXtKryqs22N+zXFAYLtJSb1YhHluUJiXJUgQVMeutzJfyNJCdlylcuAAnUTTfNsWqYIcLV6ZwYLAjyDBvzgrlgsICZjYEBAEgFTirT1DvWAAAAAElFTkSuQmCC';
    $imgs['jpg']=&$imgs['images'];$imgs['tga']=&$imgs['images'];$imgs['psd']=&$imgs['images'];$imgs['psp']=&$imgs['images'];$imgs['gif']=&$imgs['images'];$imgs['pcx']=&$imgs['images'];$imgs['tif']=&$imgs['images'];$imgs['tiff']=&$imgs['images'];$imgs['ico']=&$imgs['images'];$imgs['jpeg']=&$imgs['images'];$imgs['png']=&$imgs['images'];$imgs['bmp']=&$imgs['images'];
    $imgs['media']='iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAABxVBMVEX///9qa5v28+34/P/z+f9ubm6+yv///el0gLL/RiJftfknfd/unJDHpxofRsSenp7v6/ZSUoPpwyRqu/+3zP/M5f8cHBzrxCHX2uNbXItwjzL16ORYWYl0bV35VCz8Uiew2v+wzf/zrps2NTTGoQmQuOoRTcZadx2kyeyVwvVReAFckgF+f39pZl6Eo9Z6fqeFhYUWOrS/3//qxi/r9v/IogeUu2NCleQ4TqFoapfJ5f/2pZKymandsogFevBhY5Cvze0mJiam1f9WVof1m4mtzf+lvqhRbbT8SRfW1dJVVoZkuv5+vQ7z+P+Meivs9f9cXIyQkpK0zP9luv4BAQH/k3br0mTK2P+Kt90dYMtovP66yv+Oxv/E4v/b7f89WCDk8v9bgxRzwAF9rSV3wP6QkJBmaJRme2HW3OLCydn/gF9xmbFkoQGrzf+MeysnMI5fYZC9yv9xo8j0r5v3WC8ycdP35qHT6f+pzv+q2lrqyDZjY2lvb2+Lr+2SvfDI1OJxdZiXuc//fVtvcp7r0GB0eKOFqtqIq96Ptei4z5Pw6/gnMJBxcXH2qZZ9gauUhzPyyznT4b2Hqdz9/v9tbW3////5/P+vOEW0AAAAAXRSTlMAQObYZgAAAAFiS0dElQhgeoMAAAAWdEVYdFNvZnR3YXJlAGdpZjJwbmcgMi40LjakM4MXAAAA5ElEQVR42mNgYGCYBAbtehwMUKA5eSoYcBRBBeomQwAjmzlEoGPyNOZpQMTIyNgHFlDl4TFz07Bi8WVh0zcA8q3lODmTcqQanUz8C1unMDD0yme52Fb6aKtHKCREN09hkC5pKg7M6JqYrJWeEidiOYVBySOUibWHX6AzMV64PMh+CoMNNxcTq2NItYAQb5SoouQUhjY7LiZlMR0J9gm83rGuzlMYMiO5Hfg9p7KHCfUHG+UKAm1xVyutrS9rqTLNjgmvAAkwWBjy8RmLq8imFQTIeIEEGBq68/P8dGumgEAqAzoAAKPuQSOQhD0vAAAAAElFTkSuQmCC';
    $imgs['avi']=&$imgs['media'];$imgs['asf']=&$imgs['media'];$imgs['wmv']=&$imgs['media'];$imgs['wma']=&$imgs['media'];$imgs['ram']=&$imgs['media'];$imgs['rm']=&$imgs['media'];$imgs['3gp']=&$imgs['media'];$imgs['amr']=&$imgs['media'];$imgs['qt']=&$imgs['media'];$imgs['mov']=&$imgs['media'];$imgs['movie']=&$imgs['media'];$imgs['hdmov']=&$imgs['media'];$imgs['pls']=&$imgs['media'];$imgs['m3u']=&$imgs['media'];$imgs['asx']=&$imgs['media'];$imgs['divx']=&$imgs['media'];$imgs['ogg']=&$imgs['media'];$imgs['ogm']=&$imgs['media'];$imgs['mp4']=&$imgs['media'];$imgs['aac']=&$imgs['media'];$imgs['mpe']=&$imgs['media'];$imgs['mpeg']=&$imgs['media'];$imgs['mpg']=&$imgs['media'];$imgs['mp2']=&$imgs['media'];$imgs['mpa']=&$imgs['media'];$imgs['mp3']=&$imgs['media'];$imgs['midi']=&$imgs['media'];$imgs['mid']=&$imgs['media'];$imgs['mkv']=&$imgs['media'];$imgs['mka']=&$imgs['media'];$imgs['flac']=&$imgs['media'];$imgs['ape']=&$imgs['media'];$imgs['ifo']=&$imgs['media'];$imgs['vob']=&$imgs['media'];$imgs['dts']=&$imgs['media'];$imgs['ac3']=&$imgs['media'];$imgs['cda']=&$imgs['media'];$imgs['wav']=&$imgs['media'];$imgs['au']=&$imgs['media'];$imgs['aif']=&$imgs['media'];$imgs['flv']=&$imgs['media'];$imgs['pps']=&$imgs['media'];
    $imgs['text']='iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAsVBMVEX///+GaULUv0uQazF4eMGdgl2UlJSjo6NpaWnBz9br9fNycnLJyeN+goLh4eGcoqK/yti+0NGEbm29wMvBztm/ztW8qpG/zdW9m06khT2NjY2ipaTX19eQkZG+tLrCwsK8zNSAgICNeW+2ys68wMDDycjs9fS7zdC+zdSUfmO5ttOpkG9rbW3b29ufn5/ExuLx+Pf8/f3s9vT3+/pxcXHW1tZ4eMB+f3/p9PL///+7vb1gNwqKAAAAAXRSTlMAQObYZgAAAAFiS0dEOdcAlUAAAAAWdEVYdFNvZnR3YXJlAGdpZjJwbmcgMi40LjakM4MXAAAAf0lEQVR42mNgwAIkmJglmTSZhFhNIXweFhZmMSUtbTlWdj6wgJk+owWjEaMxo6WJDERA3cLCwsjA2NDSBKLHjBNdQNTC3NzcCklAHF2FIEiFuZUlXEADXYUAugoRdBUKFuZWKALKFhZcaqoq0rJsuhDPCPPz6nBwK0rpyWPzOgDQNxtNkr7BnAAAAABJRU5ErkJggg==';
    $imgs['txt']=&$imgs['text'];$imgs['doc']=&$imgs['text'];$imgs['xls']=&$imgs['text'];$imgs['rtf']=&$imgs['text'];$imgs['sfv']=&$imgs['text'];$imgs['lst']=&$imgs['text'];$imgs['nfo']=&$imgs['text'];$imgs['diz']=&$imgs['text'];$imgs['pdf']=&$imgs['text'];$imgs['ctg']=&$imgs['text'];$imgs['sub']=&$imgs['text'];$imgs['srt']=&$imgs['text'];$imgs['cfg']=&$imgs['text'];$imgs['ini']=&$imgs['text'];$imgs['inf']=&$imgs['text'];$imgs['log']=&$imgs['text'];$imgs['dat']=&$imgs['text'];$imgs['faq']=&$imgs['text'];$imgs['jav']=&$imgs['text'];$imgs['java']=&$imgs['text'];$imgs['c']=&$imgs['text'];
    $imgs['web']='iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAABhlBMVEX///+hxOZ/rscyiM3K2P9LiLSYwtUzmOyCtdC/v7+pzv+7zuMmisJSUoNqa5tuxOu+yv9Dqv50gLIpRV+6yv/W1tZtwebs9f96fqcpluW62/t1sd0iRGqtzf/I1v0wc7JAZImIq96ftNg/qfc7ue8oPVuU6fzb7f9HeKUxle0voutoapdIbIlRpuVLbIh0lLRVVoZhY5CEo9Zvcp7T6f/z+f+q0Ou/3/+lueHo8Pe00vD9/v8ztPB0eKMxqvJbXIvJ1/5WVoe0zP8/uu57zfgyj9jb6vjG1fsmnOXs9v8jkdIon+bL6vawzf8mRGYzqd1xo8i91+YfcZ6ZqrwqbKx1qN59gat/osA/eLFLwfmnsb41kdkqe8TJ2P+3zP+9yv9xgbJ9zvsyXY9CleSUt9ZVlcCoxuOit95jbZyhuMo9y/6zxezB0PYznOxLga7G1fzh7PY9oNrk8v9Jpuarzf+22OlJisRr8/87mOrE4v/M5f8eYJqHqdxZlrxYWYmQkJD////39/c1+Qs8AAAAAXRSTlMAQObYZgAAAAFiS0dEgGW9nmgAAAAWdEVYdFNvZnR3YXJlAGdpZjJwbmcgMi40LjakM4MXAAAA2klEQVR42mNgYGCoAQNFIyFRBgiosW4AA6EATqhAQ4NlIAdHaYJAMkSkphYEGpwz+PjC6oF8tlq18iyV1AaxAlMBiXoQ38efh8dbK6/BUzzeFihQ28gUxNgQIxit71YkYlwP4tt4Feu6sgtqcKvHadczNDJFelSDgQK3iZNhPUOmi51kNWMVCKRV+drXM7AWRuRqyjMyMzPrWFXKOgIFgqNCKtiV+fmFU6TMS3jrGRLDG8uSZPxU9ZRyHFi4gAIMZtKhchbp2fnusSwsXAYglzLUIQGwAEM9EgAANINGWfF9t2oAAAAASUVORK5CYII=';
    $imgs['html']=&$imgs['web'];$imgs['url']=&$imgs['web'];$imgs['swf']=&$imgs['web'];$imgs['fla']=&$imgs['web'];$imgs['php']=&$imgs['web'];$imgs['php3']=&$imgs['web'];$imgs['php4']=&$imgs['web'];$imgs['xml']=&$imgs['web'];$imgs['sql']=&$imgs['web'];$imgs['class']=&$imgs['web'];$imgs['css']=&$imgs['web'];$imgs['cgi']=&$imgs['web'];$imgs['js']=&$imgs['web'];$imgs['xhtml']=&$imgs['web'];$imgs['shtml']=&$imgs['web'];$imgs['dhtml']=&$imgs['web'];$imgs['aspx']=&$imgs['web'];$imgs['htm']=&$imgs['web'];$imgs['asp']=&$imgs['web'];$imgs['aam']=&$imgs['web'];
    $imgs['_dir_']='iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAkFBMVEX////MmTT/zGezgRvLmDN/f3/AjSi6hyK9iiWgbghra2vCjyr/5oGufBbHlC+jcQuwfhiIiIjJljGcagS1gh24hSCebAaZZwGaaAK0gRzvvFfcqUT4xWC8iSRKSkqreRPCwsK/jCeodhDms06lcw23hB/ToDv/1G//4HvFki3/64X/95Fqamr//////5n/9I54UBIWAAAAAXRSTlMAQObYZgAAAAFiS0dELc3aQT0AAAAWdEVYdFNvZnR3YXJlAGdpZjJwbmcgMi40LjakM4MXAAAAiUlEQVR42oXOxxKCMBgE4CWhVwEp9i4Ekt/3fzuDE0YdD3633dnDAr8su0i/stKi40cmTfnebckXU2GPj8k0U0mui2KIxYu7q1acA2kv1CxWWQ7RWTTbUhAiYjaNxppqCZcJGowLlRI+O1FvbKiV8FhFnXGnJgT0n+RwvmZBXbbN3tFPHPnm4L8nl3EWVP90I8IAAAAASUVORK5CYII=';
    $imgs['_x_']='iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAM1BMVEX///8AgAD//wD/AAAA////zDPLy8sICAjn59ZVVVWGhoYAAP+ZmZnMzMwAAJn///+AAADJQpymAAAAAXRSTlMAQObYZgAAAAFiS0dEDxi6ANkAAAAWdEVYdFNvZnR3YXJlAGdpZjJwbmcgMi40LjakM4MXAAAAVklEQVR42l3OWw7AIAhEUexLUeu4/9XaUlHq/TwhE4jiKJMUg5b5A2gZvAAzC4wRf3UoZ5E8FMoCfSNM2CqQLNQd6UgG8Fw4N0FfVfCjDiaB2/QC/6IGC9YJmappnzcAAAAASUVORK5CYII=';
    $imgs['_dots_']='iVBORw0KGgoAAAANSUhEUgAAAAMAAAABCAYAAAAb4BS0AAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAATSURBVHjaYkhJSfkPBAwgDBBgADPKCCbhpmMMAAAAAElFTkSuQmCC';
    if(isset($imgs[$i]))$d=&$imgs[$i];else$d=&$imgs['_x_'];
    header('Content-type: image/png');header('Content-Disposition: attachment; filename="'.$i.'"');print base64_decode($d);
}
else
{
    ob_start('ob_gzhandler');
    $imgicon='iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAOFJREFUOE+Nkj0OgzAMhY2EBBJX6cTI0oGJi/RGVa6QpWeBy7CWfqnBGCioVhSC/Z7/5e1kHMcQQtM0LxFu3mg8YJom4Z8PMgxD13VlWWZZBoGbNxr0ClDkTIgxVlUlX3mIQOBWQY91Q+j7viiKBZDQekywgpkjkGXb3s2m7vVYEKxgQKaUQnga4voBMhHq+mZ5nxE0FMhEyPP8mL1nmhXkGuGM4ypfIpDZz4qPdc81+C55kLVL3a1dors6h13vFafKzRx0HMzST8qnjn4/6eMuQbjaJdtH21Y6eLqt6v5/+QBK/0wfdN/R3QAAAABJRU5ErkJggg==';
    if(!$incl)print'<!DOCTYPE html><html><head><link rel="shortcut icon" type="image/png" href="data:image/png;base64,'.$imgicon.'"><link rel="icon" type="image/png" href="data:image/png;base64,'.$imgicon.'"><title>'.$name.'</title><style type="text/css">'.$css.'</style><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body>';
    if($root==''||substr($root,0,1)!='.'){if(substr($root,0,1)!='/')$root=substr_replace($root,'./',0,0);else$root=substr_replace($root,'.',0,0);}
    if($root{strlen($root)-1}!='/')$root=$root.'/';
    $sb=isset($_GET['s'])?$_GET['s']:'n';
    $so=isset($_GET['o'])?$_GET['o']:'asc';
    $st=$sb==='s'||$sb==='m'?SORT_NUMERIC:SORT_STRING;
    $d=isset($_GET['d'])?urldecode($_GET['d']):'';
    $d=$d!==''&&$d!=='/'?trim($d,'/').'/':'';
    $p=$root.$d;
    $h=@opendir($p);
    if($d){$idr=strtolower($d);if($idr==strtolower($ignoredd1)||$idr==strtolower($ignoredd2)||$idr==strtolower($ignoredd3)||strstr($d,'../')||strstr($d,'./'))exit('<a href="'.$burl.'">'.$name.'</a><br><br>You have no right to list <b>'.$idr.'</b> directory.');}
    if(!$h)exit('<a href="'.$burl.'">'.$name.'</a><br><br><b>'.$d.'</b> Directory can\'t be opened. (check the $root setting)');
    $F=array();$D=array();
    while(FALSE!==($f=readdir($h)))if($f[0]!=='.')if(is_dir($p.$f))$D[]=array('n'=>$f,'m'=>filemtime($p.$f),'s'=>filesize($p.$f),'t'=>'Directory');else$F[]=array('n'=>$f,'m'=>filemtime($p.$f),'t'=>e($f),'s'=>filesize($p.$f));
    $F=s($F,$sb,$so,$st);$D=s($D,$sb,$so,$st);
    print'<table class="itable" cellspacing="2"><tr><td colspan="5">';
    print($d!=='')?'<a href="'.$burl.'"><b>'.$name.'</b></a> / ':'<b>'.$name.'</b>';
    if($d!==''){$t=explode('/',trim($d,'/'));for($i=0,$r=array(),$z='';($r[]=@$t[$i]),$z=@$t[$i];$i++){print(implode('/',$r)!==trim($d,'/'))?('<a href="'.$burl.'?d='.implode('%2F',$r).'"><b>'.$z.'</b></a> / '):'<b>'.$z.'</b>';}}
    $f=trim($d,'/');
    if($target)$target=' target="'.$target.'"';
    print'</td></tr><tr><td class="irows">&nbsp;</td>'
    .'<td class="irows"><a href="'.$burl.'?s=n&amp;o='.($so=='asc'?'dsc':'asc').'&amp;d='.urlencode($f).'">File</a></td>'
    .'<td class="irows">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>'
    .'<td class="irows"><a href="'.$burl.'?s=s&amp;o='.($so=='asc'?'dsc':'asc').'&amp;d='.urlencode($f).'">Size</a></td>'
    .'<td class="irows"><a href="'.$burl.'?s=t&amp;o='.($so=='asc'?'dsc':'asc').'&amp;d='.urlencode($f).'">Type</a></td>'
    .'<td class="irows" nowrap="nowrap"><a href="'.$burl.'?s=m&amp;o='.($so=='asc'?'dsc':'asc').'&amp;d='.urlencode($f).'">Modified</a></td>';
    for($i=0,$c='';($c=@$D[$i++]);){$in=strtolower($d.$c['n']);if($in!=strtolower($ignoredd1)&&$in!=strtolower($ignoredd2)&&$in!=strtolower($ignoredd3)){print'<tr><td><img src="'.$burl.'?i=_dir_" alt=""></td><td><a href="'.$burl.'?d='.urlencode($d.$c['n']).'">'.$c['n'].'</a></td><td></td><td>&nbsp;</td><td>&nbsp;</td><td>'.date($date,$c['m']).'</td></tr>';}}
    for($i=0,$c='';($c=@$F[$i++]);){$it=strtolower($c['t']);$if=strtolower($d.$c['n']);if(strstr($root,'/').$d.$c['n']!=$_SERVER['SCRIPT_URL']&&$it!=strtolower($ignoredt1)&&$it!=strtolower($ignoredt2)&&$it!=strtolower($ignoredt3)&&$if!=strtolower($ignoredf1)&&$if!=strtolower($ignoredf2)&&$if!=strtolower($ignoredf3)){print'<tr><td><img src="'.$burl.'?i='.$c['t'].'" alt=""></td><td><a href="'.$root.$d.$c['n'].'"'.$target.'>'.$c['n'].'</a></td><td></td><td>'.fs($c['s']).'&nbsp;</td><td>'.$c['t'].'&nbsp;</td><td>'.date($date,$c['m']).'</td></tr>';}}
    print'<tr><td colspan="6" class="irows" style="text-align:center;background-position:top;">'.$bottom.'</td></tr></table>';
    if(!$incl)print'</body></html>';
}
// Vector Lister 1.7 by: vector @ devs.space