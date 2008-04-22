<?php

$dir = "avatars/";

$avatars = array();

if (is_dir($dir)) {
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			if (filetype($dir.$file) == "file" && getimagesize($dir.$file)) {
				array_push($avatars, $file);
			}
		}
		closedir($dh);
	}
}

$img = $dir.$avatars[rand(0, count($avatars)-1)];
$info = getimagesize($img);

if ($info[2] == 2) {
	header('Content-Type: image/jpeg');

	$img = imagecreatefromjpeg($img);
	imagejpeg($img);
}

elseif ($info[2] == 3) {
	header('Content-Type: image/png');

	$img = imagecreatefrompng($img);
	imagepng($img);
}

else {
	header('Content-Type: image/gif');

	$img = imagecreatefromgif($img);
	imagegif($img);
}

imagedestroy($img);
?>
