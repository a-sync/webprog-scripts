<?php
error_reporting(0);

$_word['md5'] = md5($_GET['w']); // 

function szimbolumok($w) {
  // itt: szétveszem a hasht 16 darabra(ahány darabka kell / lehet)
  // str_split // v amelyik kell
  for($i = 0; $i < 32; $i++) { // ennyi darabunk van
    $_letters[] = substr($_word['md5'], $i, 1);
  }
  
  return $_letters;
}

if(!function_exists('str_split')) {
    function str_split($string,$string_length=1) {
        if(strlen($string)>$string_length || !$string_length) {
            do {
                $c = strlen($string);
                $parts[] = substr($string,0,$string_length);
                $string = substr($string,$string_length);
            } while($string !== false);
        } else {
            $parts = array($string);
        }
        return $parts;
    }
}

$_letters = str_split($_word['md5']);

// 32 darabokat gyártani a hashböl, ha több kell ujrahesselni a hasht és igy tobább amig annyi darab nem lesz amennyi kell//vagy kevesebbet kivenni az elejetol kezdve
// darabokat fájlban tárolni !!!
$_piece[0] = '&copy;';
$_piece[1] = '&micro;';
$_piece[2] = '&para;';
$_piece[3] = '&reg;';
$_piece[4] = '&sect;';
$_piece[5] = '&diams;';
$_piece[6] = '&hearts;';
$_piece[7] = '&clubs;';
$_piece[8] = '&infin;';
$_piece[9] = '&sum;';
$_piece['a'] = '&empty;';
$_piece['b'] = '&trade;';
$_piece['c'] = '&piv;';
$_piece['d'] = '&bull;';
$_piece['e'] = '&part;';
$_piece['f'] = '&nabla;';
        



// solid kinézet


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="language" content="hu" />

  <meta name="copyright" content="Vector" />
  <meta name="Author" content="Vector" />

  <meta name="googlebot" content="index, follow, archive" />
  <meta name="robots" content="all, index, follow" />

  <meta name="msnbot" content="all, index, follow" />

  <meta name="rating" content="general" />
  <meta name="resource-type" content="document" />
  <meta name="distribution" content="Global" />
  <meta name="revisit-after" content="1 day" />

  <meta name="page-type" content="" />
  <meta name="subject" content="" />
  <meta name="description" content="" />

  <meta name="keywords" content="" />

  <title>inline search</title>

  <style type="text/css" rel="stylesheet">
  <!--
    html, body {
      background-color: #3a3a3a;
    }
    #container {
      width: 200px;
      /*height: 400px;
      border: 1px dashed #0a0a0a;*/
      margin: 0 auto;
      padding: 10px;
      text-align: center;
     }
    .pic_box {
      float: left;
      background-color: #ffefef;
      color: #040404;
      margin: 0;
      padding: 0;
      border: 0;
      width: 50px;
      height: 50px;
      font-size: 40px;
      font-family: sans-serif;
      font-weight: bold;
    }
  -->
  </style>
</head>

<body>

<div id="container">

<?php

//foreach($_pictures as $n => $p) {
// 4x4 --> 16 darabka kell (32(betű) van)
  // nem generalunk ujabb hasheket a boviteshez egyelore
$n = -1;
for($i = 0; $i < 16; $i++) {
  
  if($n >= (32 - 1)) {
    $n = 0;
    
    $_word['md5'] = md5($_word['md5']);
    //$_pictures = szimbolumok($_word['md5']);
    $_letters = str_split($_word['md5']);
  }
  else $n++;
  
  //$c = $_pictures[$n];
  $p = $_piece[$_letters[$n]];
  
  
  // 16 féle értékű lehet a szín
  $c1 = $_letters[$n];
  //ha végén van akor csuszas lesz mert változik a szin a ciklus vegen
    // n-et is nullazni kellene de ez a ciklus vege, és a következő elején ugyis meglesz csinálva
  $c2 = ($n >= (32 - 1)) ? $_letters[0] : $_letters[++$n];
  
  $c3 = ($n >= (32 - 1)) ? $_letters[0] : $_letters[++$n];
  
  $c4 = ($n >= (32 - 1)) ? $_letters[0] : $_letters[++$n];
  
  $c5 = ($n >= (32 - 1)) ? $_letters[0] : $_letters[++$n];
  
  $c6 = ($n >= (32 - 1)) ? $_letters[0] : $_letters[++$n];
  
  $echo1_1 .= '<div class="pic_box" style="background-color: #'.$c1.$c1.$c1.$c1.$c1.$c1.'">'.$p.'</div>'; // monokróm 1
  $echo1_2 .= '<div class="pic_box" style="background-color: #'.$c2.$c2.$c2.$c2.$c2.$c2.'">'.$p.'</div>'; // monokróm 2
  $echo1_3 .= '<div class="pic_box" style="background-color: #'.$c3.$c3.$c3.$c3.$c3.$c3.'">'.$p.'</div>'; // monokróm 3
  $echo1_4 .= '<div class="pic_box" style="background-color: #'.$c4.$c4.$c4.$c4.$c4.$c4.'">'.$p.'</div>'; // monokróm 4
  $echo1_5 .= '<div class="pic_box" style="background-color: #'.$c5.$c5.$c5.$c5.$c5.$c5.'">'.$p.'</div>'; // monokróm 5
  $echo1_6 .= '<div class="pic_box" style="background-color: #'.$c6.$c6.$c6.$c6.$c6.$c6.'">'.$p.'</div>'; // monokróm 6
  $echo2 .= '<div class="pic_box" style="background-color: #'.$c1.$c2.$c3.$c4.$c5.$c6.'">'.$p.'</div>'; // sima
  $echo3 .= '<div class="pic_box" style="background-color: #'.$c1.$c1.$c2.$c2.$c3.$c3.'">'.$p.'</div>'; // páros
  $echo4 .= '<div class="pic_box" style="background-color: #'.$c1.$c1.$c1.$c2.$c2.$c2.'">'.$p.'</div>'; // dupla
  $echo5 .= '<div class="pic_box" style="background-color: #'.$c1.$c6.$c2.$c5.$c3.$c4.'">'.$p.'</div>'; // kereszt
  $echo6 .= '<div class="pic_box" style="background-color: #'.$c1.$c4.$c2.$c5.$c3.$c6.'">'.$p.'</div>'; // rumba
  
}
$_sep = '<div style="width: 200px; height: 10px; clear: both;"></div>';

  echo $echo6
      .$_sep.'<br/><br/><h2 style="color: white">Többi</h2>'
      .$echo5
      .$_sep
      .$echo4
      .$_sep
      .$echo3
      .$_sep
      .$echo2
      .$_sep
      .$echo1_1
      .$_sep
      .$echo1_2
      .$_sep
      .$echo1_3
      .$_sep
      .$echo1_4
      .$_sep
      .$echo1_5
      .$_sep
      .$echo1_6;



echo '</div><pre style="clear: both; padding: 20px;">'.print_r($_letters, true).'</pre></body></html>';
?>