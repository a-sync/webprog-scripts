<?php
//Vec Captcha 1.1 -- Captcha generáló fájl
session_start();

	$img_w = 80;//kép szélessége
	$img_h = 20;//kép magassága

	$str_x = 18;//szöveg pozíciója az X tengelyen
	$str_y = 2;//szöveg pozíciója az Y tengelyen

	$xlines = 1;//teljes vízszintes síkú vonalak száma
	$ylines = 1;//teljes függõleges síkú vonalak száma
	$junks = 1;//véletlen vonaldarabok száma

	$chars = 5;//karakterek / számjegyek száma (max. 32)
	$csize = 12;//karakterek mérete

	$type = 0;//0 = vegyes karakterek beolvasása; 1 = szöveggé alakított számsor beolvasása

	if($chars > 32) { $chars = 32; }
	if($chars < 1) { $chars = 1; }

	if($type === 0) {
		$md5 = bin2hex(md5(microtime() * mt_rand(1, mktime()), TRUE));
		$text_string = substr($md5, mt_rand(0, (32-$chars)), $chars);
		$number_string = $text_string;
	}
	elseif($type === 1) {
		$num_str0 = array(2 => 'száz', 3 => 'ezer', 6 => 'millió', 9 => 'milliárd', 12 => 'billió', 15 => 'billiárd', 18 => 'trillió', 21 => 'trilliárd', 24 => 'kvadrillió', 27 => 'kvadrilliárd', 30 => '	kvintillió');
		$num_str1 = array(1 => 'egy', 2 => 'két', 3 => 'három', 4 => 'négy', 5 => 'öt', 6 => 'hat', 7 => 'hét', 8 => 'nyolc', 9 => 'kilenc');
		$num_str2 = array(1 => 'tizen', 2 => 'huszon', 3 => 'harminc', 4 => 'negyven', 5 => 'ötven', 6 => 'hatvan', 7 => 'hetven', 8 => 'nyolcvan', 9 => 'kilencven');

		for($i = 0; $chars > $i; $i++) {
			$number = mt_rand(1, 9);
	
			if(!isset($number_string)){ $number_string = $number; }
			else { $number_string = $number.$number_string; }
	
			if(!isset($text_string)) {
				if($number == 2) { $num_str = 'kettõ'; }
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

	$captcha = /*@*/imagecreate($img_w, $img_h) or die('A kép nem hozható létre!');
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
//Vec Captcha 1.1 -- Példa form fájl
	session_start();

	if(bin2hex(md5($_POST['captcha'], TRUE)) == $_SESSION['captcha']) {
		echo 'A megerõsítõkód helyes!';

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
		<input type="text" name="captcha"><?php if($_POST['captcha'] != '') { echo ' Hibás!'; } ?>
		<br>
		<input type="submit" name="send">
		</form>
<?php
	}
*/?>
