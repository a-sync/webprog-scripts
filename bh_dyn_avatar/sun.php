<?php

	if ( !isset($cfg) ) require_once("config.php");

	define("_NIGHT",0);
	define("_DAY",1);
	if ( !isset($_GET['time']) || ($timestamp = @strtotime($_GET['time'])) === -1) {
		define("TIME",time());
	} else {
		define("TIME",$timestamp);
	}
//	define("TIME",mktime(2, 40, 0, date("m"),  date("d"), date("Y")));
	
	define("SUNRISE",date_sunrise(mktime(12,0,0,date("m",TIME),date("d",TIME),date("Y",TIME))
			,$cfg['format'],$cfg['latitude'],$cfg['longitude'],$cfg['horizont'],$cfg['gmt_offset']));
	define("NOON",mktime(12, 0, 0, date("m"),  date("d"), date("Y")));
	define("SUNSET",date_sunset(mktime(12,0,0,date("m",TIME),date("d",TIME),date("Y",TIME))
			,$cfg['format'],$cfg['latitude'],$cfg['longitude'],$cfg['horizont'],$cfg['gmt_offset']));
	define("DAY_START",mktime(6, 0, 0, date("m"),  date("d"), date("Y")));
	define("DAY_END",mktime(18, 0, 0, date("m"),  date("d"), date("Y")));
	if ( TIME>SUNRISE ) {
		define("NIGHT_START",mktime(date("H",SUNSET), date("i",SUNSET)+15, date("s",SUNSET)
								, date("m"),  date("d"), date("Y")));
		define("MIDNIGHT",mktime(0, 0, 0, date("m"),  date("d")+1, date("Y")));
		define("NIGHT_END",mktime(date("H",SUNRISE), date("i",SUNRISE)-15, date("s",SUNRISE)
								, date("m"),  date("d")+1, date("Y")));
	} else {
		define("NIGHT_START",mktime(date("H",SUNSET), date("i",SUNSET)+15, date("s",SUNSET)
								, date("m"),  date("d")-1, date("Y")));
		define("MIDNIGHT",mktime(0, 0, 0, date("m"),  date("d"), date("Y")));
		define("NIGHT_END",mktime(date("H",SUNRISE), date("i",SUNRISE)-15, date("s",SUNRISE)
								, date("m"),  date("d"), date("Y")));
	}
	define("DAY_START",mktime(6, 0, 0, date("m"),  date("d"), date("Y")));
	define("DAY_END",mktime(18, 0, 0, date("m"),  date("d"), date("Y")));
	
	function night() {
		return ( NIGHT_START<TIME && TIME<NIGHT_END );
	}

	function fade_ratio ( $dayornight ) {
		
		global $cfg;
		
		switch ( $dayornight ) {
			case _DAY:
				switch ( true ) {
					case TIME<=SUNRISE:
						$past=TIME-MIDNIGHT;
						$period=SUNRISE-MIDNIGHT;
						$perc=$past/$period;
						$res=($cfg['sunrise_fade']-$cfg['midnight_fade'])*(sin(($perc-1)*M_PI/2)+1)
								+$cfg['midnight_fade'];
						break;
					case SUNRISE<TIME && TIME<=NOON:
						$past=TIME-SUNRISE;
						$period=NOON-SUNRISE;
						$perc=$past/$period;
						$res=$cfg['sunrise_fade']+(1-$cfg['sunrise_fade'])*sin($perc*M_PI/2);
						break;
					case NOON<TIME && TIME<=SUNSET:
						$past=TIME-NOON;
						$period=SUNSET-NOON;
						$perc=$past/$period;
						$res=$cfg['sunset_fade']+(1-$cfg['sunset_fade'])*sin(($perc+1)*M_PI/2);
						break;
					case SUNSET<TIME;
						$past=TIME-SUNSET;
						$period=MIDNIGHT-SUNSET;
						$perc=$past/$period;
						$res=($cfg['sunset_fade']-$cfg['midnight_fade'])*(sin(($perc+2)*M_PI/2)+1)
								+$cfg['midnight_fade'];
						break;
				}
				break;
			case _NIGHT:
				$res=0;
				break;
		}
		
		return $res;
	}

	function day_image(&$im, $small=false) {
		
		global $cfg;

		$day=DAY_END-DAY_START;
		$day_past=TIME-DAY_START;	
		$day_perc=$day_past/$day;
		
		$alpha_rad=M_PI*$day_perc;
		
		$im=imagecreate($cfg['w'],$cfg['h']);
		
/* modify the original colors with fade */

		foreach ( $cfg['colors'] as $oname => $rgb) {
			$fader_color='fade';
			$perc=fade_ratio(_DAY);
			switch ( $oname ) {
				case 'moon':
				case 'stars':
					$perc=fade_ratio(_NIGHT);
				case 'sun':
					$fader_color.='_'.$oname;
				case 'ground':
				case 'sky':
				case 'surface':
					foreach ($rgb as $key=>$component) {
						$rgb_faded[$key]=$component
											*$perc
										+$cfg['colors'][$fader_color][$key]
											*(1-$perc);
					}
					$colors[$oname]=imagecolorallocate($im,$rgb_faded[0],$rgb_faded[1],$rgb_faded[2]);
					break;
			}
		}
		if (night())
			$text_color_name="text_color";
		else 
			$text_color_name="text_color_dark";
		$text_color=imagecolorallocate($im,
				$cfg[$text_color_name][0],$cfg[$text_color_name][1],$cfg[$text_color_name][2]);
		
/* sky */
		imagealphablending( $im, false );
		imagefilledrectangle($im,0,0,$cfg['w'],$cfg['h'],$colors['sky']);
/* sun */
		imagefilledellipse($im,$cfg['origo'][0]-$cfg['radius']*cos($alpha_rad)
								,$cfg['origo'][1]-$cfg['radius']*sin($alpha_rad)
								,$cfg['sun_size'],$cfg['sun_size'],$colors['sun']);
								
		if ( night() ) {
/* starts */
			for ( $i=0; $i<($cfg['w']-20)*($cfg['h']-$cfg['ground_height']-20)/10*$cfg['stars_ratio']; $i++ ) {
				$x=$cfg['moon_position'][0];
				$y=$cfg['moon_position'][1];
				$ratio=2;
				while ( ($cfg['moon_position'][0]-($cfg['moon_size']/2*($ratio-1)))<$x
						&& $x<($cfg['moon_position'][0]+($cfg['moon_size']/2*$ratio))
						&& ($cfg['moon_position'][1]-($cfg['moon_size']/2*$ratio))<$y
						&& $y<($cfg['moon_position'][1]+($cfg['moon_size']/2*$ratio))) {
					$x=rand(10,$cfg['w']-10);
					$y=rand(10,$cfg['h']-$cfg['ground_height']-10);
				}
				imagefilledellipse($im,$x,$y,$cfg['stars_size']
							,$cfg['stars_size'],$colors['stars']);
			}
/* moon */
			imagefilledarc($im,$cfg['moon_position'][0],$cfg['moon_position'][1]
							,$cfg['moon_size'],$cfg['moon_size']
							,-90,90,$colors['moon'],IMG_ARC_PIE);
			imagefilledarc($im,$cfg['moon_position'][0],$cfg['moon_position'][1]
							,$cfg['moon_size']/2,$cfg['moon_size']
							,-90,90,$colors['sky'],IMG_ARC_PIE);
		}
/* ground */
		imagefilledrectangle($im,0,$cfg['h']-$cfg['ground_height']
							,$cfg['w'],$cfg['h'],$cfg['color_ground']);
/* surface */
		imageline($im,0,$cfg['h']-$cfg['ground_height'],$cfg['w']
					,$cfg['h']-$cfg['ground_height'],$colors['surface']);

/* date */
		imagealphablending( $im, true );
		$text=date("Y-m-d H:i:s",TIME);
		imagestring($im, 2, ($cfg['w']-strlen($text)*6)/2, $cfg['h']-($cfg['ground_height']+12)/2, $text, $text_color);

		if ( $small ) {
			$smallimg=imagecreate($cfg['w']/2,$cfg['h']/2);
			imagecopyresampled($smallimg,$im,0,0,0,0,$cfg['w']/2,$cfg['h']/2,$cfg['w'],$cfg['h']);
			imagedestroy($im);
			$im=$smallimg;
//			imagedestroy($smallimg);
		}
					
		return array($cfg['w'],$cfg['h']);
	}	

	if( $_SERVER["SCRIPT_FILENAME"]==str_replace('\\','/',__FILE__) ) {
		day_image($im);
		$types=array(
			'jpg'=>array('jpeg','jpeg'),
			'jpeg'=>array('jpeg','jpeg'),
			'gif'=>array('GIF','gif'),
			'png'=>array('Png','png'),
			'wbmp'=>array('wbmp','vnd.wap.wbmp')
		);	
		$type=( isset($types[strtolower($_GET['type'])]) )? $types[strtolower($_GET['type'])]: $types['png'];
		if ( $type[0]=='png' ) {
			imagealphablending( $im, false );
			imagesavealpha( $im, true );
//			imagepng( $im );
		}
		header("Content-type: image/{$type[1]}");
		header('Cache-Control: no-store, no-cache, max-age:1');
		$imagefnc="image{$type[0]}";
		$imagefnc($im);
//		imagePNG($im);
		imagedestroy($im);
		
	}
	
?>
