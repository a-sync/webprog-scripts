<html>
<head>
<link rel="shortcut icon" type="image/png" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAOFJREFUOE+Nkj0OgzAMhY2EBBJX6cTI0oGJi/RGVa6QpWeBy7CWfqnBGCioVhSC/Z7/5e1kHMcQQtM0LxFu3mg8YJom4Z8PMgxD13VlWWZZBoGbNxr0ClDkTIgxVlUlX3mIQOBWQY91Q+j7viiKBZDQekywgpkjkGXb3s2m7vVYEKxgQKaUQnga4voBMhHq+mZ5nxE0FMhEyPP8mL1nmhXkGuGM4ypfIpDZz4qPdc81+C55kLVL3a1dors6h13vFafKzRx0HMzST8qnjn4/6eMuQbjaJdtH21Y6eLqt6v5/+QBK/0wfdN/R3QAAAABJRU5ErkJggg==">
<link rel="icon" type="image/png" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAOFJREFUOE+Nkj0OgzAMhY2EBBJX6cTI0oGJi/RGVa6QpWeBy7CWfqnBGCioVhSC/Z7/5e1kHMcQQtM0LxFu3mg8YJom4Z8PMgxD13VlWWZZBoGbNxr0ClDkTIgxVlUlX3mIQOBWQY91Q+j7viiKBZDQekywgpkjkGXb3s2m7vVYEKxgQKaUQnga4voBMhHq+mZ5nxE0FMhEyPP8mL1nmhXkGuGM4ypfIpDZz4qPdc81+C55kLVL3a1dors6h13vFafKzRx0HMzST8qnjn4/6eMuQbjaJdtH21Y6eLqt6v5/+QBK/0wfdN/R3QAAAABJRU5ErkJggg==">
<title>Base64 kódolás/dekódolás</title>
<meta name="description" content="Vector Akashi's Base64 encoder/decoder">
<meta name="generator" content="Notepad2">
<meta name="author" content="Vector Akashi">
<meta name="copyright" content="Vector Akashi 2006">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-2">
<style type="text/css">
<!--
body,td,th {
	color: #000000;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
body {
	background-color: #ae0000;
}
.style3 {font-size: 10px}
h1 { font-family: verdana, arial, sans-serif; font-size: 16pt; color: #000000; font-weight: bold }
h2 { font-family: verdana, arial, sans-serif; font-size: 14pt; color: #000000 }
a:link {
	color: #FFFFFF;
}
a:visited {
	color: #FFFFFF;
}
a:hover {
	color: #999999;
}
a:active {
	color: #999999;
}
-->
</style>
</head>
<body oncontextmenu="return false;">
<?php
 if (!isset($_POST['send'])){
?>
<h1 align="center">Base64 kódolás/dekódolás</h1>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
<table border="0" align="center">
<tr>
<td valign="top" align="right"><b><font color="#ffffff">Szöveg:<br>+<br>+<br>+<br>+<br>+<br>+<br>+<br>+<br>+<br>+<br>+<br>+<br>+<br>+<br>+<br>+<br>+<br>+<br>+<br>Fájl:</font></b></td><td>
<textarea name="message" rows="16" cols="64"></textarea><br>
<input type="file" name="datafile" size="47"><font color="#ffffff"><b>Alternatív feltöltés:</b></font><input type="radio" name="method" value="safe">
</td>
</tr>
<tr>
<td valign="top"><input type="submit" name="send"></td>
<td><font color="#ffffff">++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++</font></td>
</tr>
</table>
</form>
<div align="center"><br>
<span class="style3">(C) <a href="mailto:vector @ uw . hu">Vector Akashi</a> - I Know My Rights...</span><br><br>
<a href="http://hungarian-83529822795.spampoison.com"><img src="http://pics3.inxhost.com/images/sticker.gif" border="0" width="80" height="15"/></a>
</div>
<? 
 }else{
	if (!$_POST['message'] && !$_FILES['datafile']['tmp_name']){
		echo "<b>HIBA:</b> Nem adtál meg adatot.<br><a href=JavaScript:window.history.back()><b>Vissza</b></a>";
		return;
	}
	if ($_POST['message']){
		$text = $_POST['message'];
	}else{ //ha nincs szöveg megadva, használjuk a megadott fájlt
		if ($_POST['method']=='safe'){
			move_uploaded_file($_FILES['datafile']['tmp_name'], 'b64file');
			//php 5.x.x
			$text = file_get_contents('b64file');
/*			//php 4.x.x
			$filename = 'b64file';
			$handle = fopen($filename, 'rb');
			$text = fread($handle, filesize($filename));
			fclose($handle);
*/
		}else{
			//php 5.x.x
			$text = file_get_contents($_FILES['datafile']['tmp_name']);
/*			//php 4.x.x
			$filename = $_FILES['datafile']['tmp_name'];
			$handle = fopen($filename, 'rb');
			$text = fread($handle, filesize($filename));
			fclose($handle);
*/
		}
	}

	//kódoljuk/dekódoljuk a kapott adatokat
	$encoded = base64_encode($text);
	$decoded = base64_decode($text);

	//írjuk bele fájlba a kódolt/dekódolt adatokat
	$file1 = fopen('encoded.txt', 'w'); //w=mindig csak egy kód van a fájlban, a=a kovetkezo kódot az elõzõ után rakja
	fwrite($file1,$encoded);
	fclose($file1);
	$file2 = fopen('decoded.txt', 'w'); //w=mindig csak egy kód van a fájlban, a=a kovetkezo kódot az elõzõ után rakja
	fwrite($file2,$decoded);
	fclose($file2);

	//írjuk ki textarea segítségével a kódolt/dekódolt adatokat
	echo "<h2>Kódolva</h2><pre><textarea rows=8 cols=64 readonly>";
	print $encoded;
	echo "</textarea></pre>*<a href=encoded.txt>encoded.txt</a>&nbsp;&nbsp;<font color=#ffffff><i>(".filesize('encoded.txt')."&nbsp;bytes)</i></font><br><br><hr>
		<h2>Dekódolva</h2><pre><textarea rows=8 cols=64 readonly>";
	print $decoded;
	echo "</textarea></pre>*<a href=decoded.txt>decoded.txt</a>&nbsp;&nbsp;<font color=#ffffff><i>(".filesize('decoded.txt')."&nbsp;bytes)</i></font><br><br><br><a href=".$_SERVER['PHP_SELF']."><b>Vissza</b></a>";

	//debug
//	print "<br><br><br><b>Debug:</b><br><br>Text:<br>".$text."<br><br>GIF:<img src='data:image/gif;base64,".$text."'>&nbsp;&nbsp;PNG:<img src='data:image/png;base64,".$text."'>&nbsp;&nbsp;ICON:<img src='data:image/x-icon;base64,".$text."'>&nbsp;&nbsp;JPG:<img src='data:image/jpg;base64,".$text."'>&nbsp;&nbsp;BMP:<img src='data:image/bmp;base64,".$text."'><br><br><b>Debug encoded:</b><br><br>GIF:<img src='data:image/gif;base64,".$encoded."'>&nbsp;&nbsp;PNG:<img src='data:image/png;base64,".$encoded."'>&nbsp;&nbsp;ICON:<img src='data:image/x-icon;base64,".$encoded."'>&nbsp;&nbsp;JPG:<img src='data:image/jpg;base64,".$encoded."'>&nbsp;&nbsp;BMP:<img src='data:image/bmp;base64,".$encoded."'><br><br><b>Debug coded:</b><br><br>GIF:<img src='data:image/gif;base64,".$coded."'>&nbsp;&nbsp;PNG:<img src='data:image/png;base64,".$coded."'>&nbsp;&nbsp;ICON:<img src='data:image/x-icon;base64,".$coded."'>&nbsp;&nbsp;JPG:<img src='data:image/jpg;base64,".$coded."'>&nbsp;&nbsp;BMP:<img src='data:image/bmp;base64,".$coded."'>";
 }
?>
</body>
</html>