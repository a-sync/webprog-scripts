<?php
//Vec Captcha 1.1 -- Captcha gener�l� f�jl
session_start();

	$img_w = 80;//k�p sz�less�ge
	$img_h = 20;//k�p magass�ga

	$str_x = 18;//sz�veg poz�ci�ja az X tengelyen
	$str_y = 2;//sz�veg poz�ci�ja az Y tengelyen

	$xlines = 1;//teljes v�zszintes s�k� vonalak sz�ma
	$ylines = 1;//teljes f�gg�leges s�k� vonalak sz�ma
	$junks = 1;//v�letlen vonaldarabok sz�ma

	$chars = 5;//karakterek / sz�mjegyek sz�ma (max. 32)
	$csize = 12;//karakterek m�rete

	$type = 0;//0 = vegyes karakterek beolvas�sa; 1 = sz�vegg� alak�tott sz�msor beolvas�sa

	if($chars > 32) { $chars = 32; }
	if($chars < 1) { $chars = 1; }

	if($type === 0) {
		$md5 = bin2hex(md5(microtime() * mt_rand(1, mktime()), TRUE));
		$text_string = substr($md5, mt_rand(0, (32-$chars)), $chars);
		$number_string = $text_string;
	}
	elseif($type === 1) {
		$num_str0 = array(2 => 'sz�z', 3 => 'ezer', 6 => 'milli�', 9 => 'milli�rd', 12 => 'billi�', 15 => 'billi�rd', 18 => 'trilli�', 21 => 'trilli�rd', 24 => 'kvadrilli�', 27 => 'kvadrilli�rd', 30 => '	kvintilli�');
		$num_str1 = array(1 => 'egy', 2 => 'k�t', 3 => 'h�rom', 4 => 'n�gy', 5 => '�t', 6 => 'hat', 7 => 'h�t', 8 => 'nyolc', 9 => 'kilenc');
		$num_str2 = array(1 => 'tizen', 2 => 'huszon', 3 => 'harminc', 4 => 'negyven', 5 => '�tven', 6 => 'hatvan', 7 => 'hetven', 8 => 'nyolcvan', 9 => 'kilencven');

		for($i = 0; $chars > $i; $i++) {
			$number = mt_rand(1, 9);
	
			if(!isset($number_string)){ $number_string = $number; }
			else { $number_string = $number.$number_string; }
	
			if(!isset($text_string)) {
				if($number == 2) { $num_str = 'kett�'; }
				else { $num_str = $num_str1[$number]; }
				$text_string = $num_str;
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

	$captcha = /*@*/imagecreate($img_w, $img_h) or die('A k�p nem hozhat� l�tre!');
	imagecolorallocate($captcha, mt_rand(150, 255), mt_rand(150, 255), mt_rand(150, 255));

	for($i = 0; $xlines > $i; $i++) {
		imageline($captcha, 0, mt_rand(0, $img_h), $img_w, mt_rand(0, $img_h), imagecolorallocate($captcha, mt_rand(75, 200), mt_rand(75, 200), mt_rand(75, 200)));
	}
	for($i = 0; $ylines > $i; $i++) {
		imageline($captcha, mt_rand(0, $img_w), 0, mt_rand(0, $img_w), $img_h, imagecolorallocate($captcha, mt_rand(75, 200), mt_rand(75, 200), mt_rand(75, 200)));
	}
	for($i = 0; $junks > $i; $i++) {
		imageline($captcha, mt_rand(0, $img_w), mt_rand(0, $img_h), mt_rand(0, $img_w), mt_rand(0, $img_h), imagecolorallocate($captcha, mt_rand(75, 200), mt_rand(75, 200), mt_rand(75, 200)));
	}

	imagestring($captcha, $csize, $str_x, $str_y, $text_string, imagecolorallocate($captcha, mt_rand(0, 175), mt_rand(0, 175), mt_rand(0, 175)));

	$_SESSION['captcha'] = bin2hex(md5($number_string, TRUE));

	header('Content-type: image/png');
	header( 'Expires: Sat, 22 Aug 1987 06:06:06 GMT' );
	header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	header( 'Cache-Control: no-store, no-cache, must-revalidate' );
	header( 'Cache-Control: post-check=0, pre-check=0', false );
	header( 'Pragma: no-cache' );

	imagepng($captcha);
?>


<?php/*
//Vec Captcha 1.1 -- P�lda form f�jl
	session_start();

	if(bin2hex(md5($_POST['captcha'], TRUE)) == $_SESSION['captcha']) {
		echo 'A meger�s�t�k�d helyes!';

		session_unset();
		session_destroy();
	}
	else {
		session_unset();
		session_destroy();
?>
		<form method="post">
		<img src="captcha.php" border="0">
		<br>
		<input type="text" name="captcha"><?php if($_POST['captcha'] != '') { echo ' Hib�s!'; } ?>
		<br>
		<input type="submit" name="send">
		</form>
<?php
	}
*/?>
