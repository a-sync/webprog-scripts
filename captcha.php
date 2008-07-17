<?php
//Vec Captcha 1.2 -- Captcha gener�l� f�jl
//(C) 2008 Vector Akashi - Minden Jog Fenntartva!
//www.onethreestudio.com

/* //Vec Captcha 1.2 -- P�lda form f�jl
	<?php
    session_start();

    if(md5($_POST['captcha']) == $_SESSION['captcha']) {
      echo 'A meger�s�t�k�d helyes!';
      session_unset();
      session_destroy();
    }
    else {
      session_unset();
      session_destroy();

			if($_POST['captcha'] != '') { echo 'A meger�s�t�k�d hib�s!<br/>'; }
			echo '
				<form method="post">
				<img src="captcha.php" border="0"/><br/>
				<input type="text" name="captcha"/><br/>
				<input type="submit"/>
				</form>
			';
		}
	?>
*/


// ##### Be�ll�t�sok ##### //
	$img_w = 450;//k�p sz�less�ge
	$img_h = 40;//k�p magass�ga

	$chars = 5;//karakterek / sz�mjegyek sz�ma (max. 32)
	$csize = 12;//karakterek m�rete

	$str_x = 18;//sz�veg poz�ci�ja az X tengelyen
	$str_y = 12;//sz�veg poz�ci�ja az Y tengelyen

	$xlines = 2;//teljes v�zszintes s�k� vonalak sz�ma
	$ylines = 4;//teljes f�gg�leges s�k� vonalak sz�ma
	$junks = 2;//v�letlen vonaldarabok sz�ma

	$chars_rgb = array(mt_rand(0, 175), mt_rand(0, 175), mt_rand(0, 175));//karakterek / sz�mjegyek RGB sz�ne h�rom elem� t�mbben
	$xlines_rgb = array(mt_rand(75, 200), mt_rand(75, 200), mt_rand(75, 200));//teljes v�zszintes s�k� vonalak RGB sz�ne h�rom elem� t�mbben
	$ylines_rgb = array(mt_rand(75, 200), mt_rand(75, 200), mt_rand(75, 200));//teljes f�gg�leges s�k� vonalak RGB sz�ne h�rom elem� t�mbben
	$junks_rgb = array(mt_rand(75, 200), mt_rand(75, 200), mt_rand(75, 200));//v�letlen vonaldarabok RGB sz�ne h�rom elem� t�mbben

	$type = 1;//0 = vegyes karakterek beolvas�sa; 1 = sz�vegg� alak�tott sz�msor beolvas�sa


// ##### Adatfeldolgoz�s ##### //
	if($chars > 32) { $chars = 32; }
	if($chars < 1) { $chars = 1; }

  session_start();

	if($type === 0) {
		$md5 = md5(microtime() * mt_rand(1, mktime()));
		$text_string = substr($md5, mt_rand(0, (32-$chars)), $chars);
		$number_string = $text_string;
	}
	elseif($type === 1) {
		$num_str0 = array(
      0 => '',
      2 => 'sz�z',
      3 => 'ezer',
      6 => 'milli�',
      9 => 'milli�rd',
      12 => 'billi�',
      15 => 'billi�rd',
      18 => 'trilli�',
      21 => 'trilli�rd',
      24 => 'kvadrilli�',
      27 => 'kvadrilli�rd',
      30 => '	kvintilli�'
    );
		$num_str1 = array(
      0 => '',
      1 => 'egy',
      2 => 'k�t',
      3 => 'h�rom',
      4 => 'n�gy',
      5 => '�t',
      6 => 'hat',
      7 => 'h�t',
      8 => 'nyolc',
      9 => 'kilenc'
    );
		$num_str2 = array(
      0 => '',
      1 => 'tizen',
      2 => 'huszon',
      3 => 'harminc',
      4 => 'negyven',
      5 => '�tven',
      6 => 'hatvan',
      7 => 'hetven',
      8 => 'nyolcvan',
      9 => 'kilencven'
    );

		for($i = 0; $chars > $i; $i++) {
      //if(($i + 1) == $chars) { $number = mt_rand(1, 9); }
      //else { $number = mt_rand(0, 9); }
			$number = mt_rand(1, 9);

			if(!isset($number_string)){ $number_string = $number; }
			else { $number_string = $number.$number_string; }

			if(!isset($text_string)) {
				if($number == 2) { $text_string = 'kett�'; }
				else { $text_string = $num_str1[$number]; }
				$next = 2;
			}
			else {
				if($next == 1) {
					if($number == 1 && $i == 3 && ($i+1) == $chars) { $dash = ''; $num_str = ''; }
					else { $dash = '-'; $num_str = $num_str1[$number]; }
					$text_string = $num_str.$num_str0[$i].$dash.$text_string;
					$next = 2;
				}
				elseif($next == 2) {
					$text_string = $num_str2[$number].$text_string;
					$next = 3;
				}
				elseif($next == 3) {
					if($number == 1) { if($i == 2 && ($i+1) != $chars) { $num_str = $num_str1[$number]; } else { $num_str = ''; } }
					else { $num_str = $num_str1[$number]; }
					$text_string = $num_str.$num_str0[2].$text_string;
					$next = 1;
				}
			}
		}
	}


// ##### K�pfeldolgoz�s ##### //
	$captcha = /*@*/imagecreate($img_w, $img_h) or die('A k�p nem hozhat� l�tre!');
	imagecolorallocate($captcha, mt_rand(150, 255), mt_rand(150, 255), mt_rand(150, 255));

	for($i = 0; $xlines > $i; $i++) {
		imageline($captcha, 0, mt_rand(0, $img_h), $img_w, mt_rand(0, $img_h), imagecolorallocate($captcha, $xlines_rgb[0], $xlines_rgb[1], $xlines_rgb[2]));
	}
	for($i = 0; $ylines > $i; $i++) {
		imageline($captcha, mt_rand(0, $img_w), 0, mt_rand(0, $img_w), $img_h, imagecolorallocate($captcha, $ylines_rgb[0], $ylines_rgb[1], $ylines_rgb[2]));
	}
	for($i = 0; $junks > $i; $i++) {
		imageline($captcha, mt_rand(0, $img_w), mt_rand(0, $img_h), mt_rand(0, $img_w), mt_rand(0, $img_h), imagecolorallocate($captcha, $junks_rgb[0], $junks_rgb[1], $junks_rgb[2]));
	}

	@imagestring($captcha, $csize, $str_x, $str_y, $text_string, imagecolorallocate($captcha, $chars_rgb[0], $chars_rgb[1], $chars_rgb[2]));

	$_SESSION['captcha'] = md5($number_string);

	header('Content-type: image/png');
	header('Expires: Sat, 22 Aug 1987 06:06:06 GMT');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', false);
	header('Pragma: no-cache');

	@imagepng($captcha);
?>