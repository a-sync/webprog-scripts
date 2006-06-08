<?php

	ob_start();
	$f=fopen('a.txt','a');
	fputs($f,$_SERVER['QUERY_STRING']."\n");
	fclose($f);
	ob_end_clean();

// hibaüzenetek elnyeléséhez kimenet pufferelés
	ob_start();

	require_once("snoopy.class.php");
	require_once("config.php");
	require_once("sun.php");
	
	global $cfg;
	
		
// lap beolvasasa a seed/leech infokhoz
	$bth=new Snoopy;

	$bth->cookies['rid']=$cfg['auth_id'];
	$bth->cookies['pass']=$cfg['pass'];
	$bth->cookies['uid']=$cfg['auth_id'];

	$bth->fetch("http://bithumen.ath.cx/userdetails.php?id=".$cfg['id']);

	$file=$bth->results;
//var_dump($file);die;	
// alapadatok beolvasasa

// nem muxik ha vmelyik hianyzik :(
//	preg_match(
//		"/Üdv"				."[^<]*(?U)(?:<?[^>]*>)*"
//		."(?P<user>[^<]+)(?=<)"		."[^<]*(?U)(?:<?[^>]*>|\s|[^<]*)*"	//nev
//		."Heti pozíció(?!\:)"		."[^<]*(?U)(?:<?[^>]*>|\s|[^<]*)*"
//		."(?P<week_pos>[0-9\.]+)(?=<)"	."[^<]*(?U)(?:<?[^>]*>|\s|[^<]*)*"	//heti pozicio
//		."Havi pozíció(?!\:)"		."[^<]*(?U)(?:<?[^>]*>|\s|[^<]*)*"
//		."(?P<month_pos>[0-9\.]+)(?=<)"	."[^<]*(?U)(?:<?[^>]*>|\s|[^<]*)*"	//havi pozicio
//		."Feltöltött(?!\:)"		."[^<]*(?U)(?:<?[^>]*>|\s|[^<]*)*"
//		."(?P<up>[0-9\.]+\ [GT]B)"	."[^<]*(?U)(?:<?[^>]*>|\s|[^<]*)*"	//feltoltott
//		."Letöltött(?!\:)"		."[^<]*(?U)(?:<?[^>]*>|\s|[^<]*)*"
//		."(?P<down>[0-9\.]+\ [GT]B)"	."[^<]*(?U)(?:<?[^>]*>|\s|[^<]*)*"	//letoltott
//		."Megosztási arány(?!\:)"	."[^<]*(?U)(?:<?[^>]*>|\s|[^<]*)*"
//		."(?P<rate>[0-9\.]+)(?=<)"	."[^<]*(?U)(?:<?[^>]*>|\s|[^<]*)*"	//arany
//		."Szint(?!\:)"			."[^<]*(?U)(?:<?[^>]*>|\s)*"
//		."(?P<level>[^<]+)(?=<)"	."[^<]*(?U)(?:<?[^>]*>|\s|[^<]*)*"	//szint
//		."Város(?!\:)"			."[^<]*(?U)(?:<?[^>]*>|\s)*"
//		."(?P<city>[^<]+)(?=<)"	."[^<]*(?U)(?:<?[^>]*>|\s|[^<]*)*/"		//varos
//		,$file,$d);
//

	preg_match("/BitHUmen :: Details for "						."(?P<user>[^<]+)(?=<)/"	, $file, $res);	//nev
	$d['user'] = $res['user'];
	preg_match("/Heti pozíció(?!\:)"	."[^<]*(?U)(?:<?[^>]*>|\s|[^<]*)*"	."(?P<week_pos>[0-9\.]+)(?=<)/"	, $file, $res);	//heti pozicio
	$d['week_pos'] = $res['week_pos'];
	preg_match("/Havi pozíció(?!\:)"	."[^<]*(?U)(?:<?[^>]*>|\s|[^<]*)*"	."(?P<month_pos>[0-9\.]+)(?=<)/", $file, $res);	//havi pozicio
	$d['month_pos'] = $res['month_pos'];
	preg_match("/Feltöltött(?!\:)"		."[^<]*(?U)(?:<?[^>]*>|\s|[^<]*)*"	."(?P<up>[0-9\.]+\ [GT]B)/"	, $file, $res);	//feltoltott
	$d['up'] = $res['up'];
	preg_match("/Letöltött(?!\:)"		."[^<]*(?U)(?:<?[^>]*>|\s|[^<]*)*"	."(?P<down>[0-9\.]+\ [GT]B)/"	, $file, $res);	//letoltott
	$d['down'] = $res['down'];
	preg_match("/Megosztási arány(?!\:)"	."[^<]*(?U)(?:<?[^>]*>|\s|[^<]*)*"	."(?P<rate>[0-9\.]+)(?=<)/"	, $file, $res);	//arany
	$d['rate'] = $res['rate'];
	preg_match("/Szint(?!\:)"		."[^<]*(?U)(?:<?[^>]*>|\s)*"		."(?P<level>[^<]+)(?=<)/"	, $file, $res);	//szint
	$d['level'] = $res['level'];
	preg_match("/Város(?!\:)"		."[^<]*(?U)(?:<?[^>]*>|\s)*"		."(?P<city>[^<]+)(?=<)/"	, $file, $res);	//varos
	$d['city'] = $res['city'];

	foreach ($d as $k=>$v) if ( is_int($k) ) unset($d[$k]);

	$d['rankings'] = substr_count($file,"bullet_star.png");

//var_dump($d);die;
///	preg_match("/(<table id=torrenttable[^>]*>.*)/", $file, $torrenttables);

	$seedtable_begin=strpos($file,"<table id=torrenttable");
	$seedtable_end=strpos($file,"</table>",$seedtable_begin);
	$leechtable_begin=strpos($file,"<table id=torrenttable",$seedtable_end);
	$leechtable_end=strpos($file,"</table>",$leechtable_begin);
	$params[3] = substr_count(substr($file,$seedtable_begin,$seedtable_end-$seedtable_begin),"<tr")-1;
	$params[4] = substr_count(substr($file,$leechtable_begin,$leechtable_end-$leechtable_begin),"<tr")-1;
	$d['seed']=is_int($params[3])? ''.$params[3]:'(N/A)';
	$d['leech']=is_int($params[4])? ''.$params[4]:'(N/A)';
	
	foreach ( $cfg['labels'] as $label=>$params ) {
		if ( !empty($d[$label]) || $d[$label]==="0" )
		$sorok[]=array_merge(array($params[3].':',$d[$label]),$params);
	} // adatok rendezése
	
	$bgimage=imagecreatefrompng($cfg['bgimage']); // háttérkép beolvasása
	$width=$cfg['width'];
	$height=count($sorok)*$cfg['line_height']+2*$cfg['margin']; // összenyomott magasság a szélességgel arányban
	$fimage = ImageCreate ( $width,$height ); // Üres kép létrehozása 
	ImageCopyResampled($fimage,$bgimage,0,0,0,0,$width,$height,$width,$height); // háttér másolása az adatlapra
	ImageDestroy($bgimage); // háttér törlése
	foreach ( $sorok as $index=>$sor ) {
		$tc  = ImageColorAllocate ($fimage, $sor[2], $sor[3], $sor[4]); // szöveg színének beállítása
		ImageString($fimage, 2, $cfg['margin'], $cfg['margin']+$index*$cfg['line_height'],
				"{$sor[0]} {$sor[1]}", $tc); // szöveg adatlapra írása
	}

	$size=day_image($dayimage); // napocskás kép generálása és méreteinek meghatározása
	$dheight = $width*$size[1]/$size[0];
	$im=imagecreate($width,$height+$dheight); // végleges kép létrehozása
	imagecopyresampled($im,$fimage,0,$dheight,0,0,$width,$height,$width,$height); // adatlap másolása a véglegesre
	imagecopyresampled($im,$dayimage,0,0,0,0,$width,$dheight,$size[0],$size[1]); // napocskás kép másolása a véglegesre
	
	ob_end_clean(); // hibaüzenetek kidobása
	
	header("Content-type: image/png");
	header('Cache-Control: no-store, no-cache, max-age:1');
	imagePNG($im);
	imagedestroy($im);
	imagedestroy($dayimage);
	imagedestroy($fimage);

?>
