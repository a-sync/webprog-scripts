<?php

	require_once("auth.php");

	global $auth;

	$cfg=array(
		'bg_color'=>array(200,200,255),
		'text_color'=>array(255,255,100),
		'text_color_dark'=>array(0,0,255),
		'labels'=>array(
			'user'=>array(0,0,200,'Név'),
			'level'=>array(0,0,0,'Szint'),
			'up'=>array(0,150,0,'Fel'),
			'down'=>array(200,0,0,'Le'),
			'rate'=>array(0,0,200,'Arány'),
			'seed'=>array(0,150,0,'Seed'),
			'leech'=>array(200,0,0,'Leech'),
			'week_pos'=>array(0,0,200,'Heti pozíció'),
			'month_pos'=>array(0,0,200,'Havi pozíció'),
			'rankings'=>array(0,150,0,'Kitüntetések'),
			'city'=>array(0,0,0,'Város')
		),
		'width'=>150,
		'line_height'=>12,
		'margin'=>4,
		'gfx_dir'=>'gfx/',
		'bgimage'=>'./avatarbg.png',

		'id'=>isset($_GET['id'])? $_GET['id']:$auth['uid'],
		'pass'=>(isset($_GET['id']) && isset($_GET['pass']))? $_GET['pass']:$auth['pass'],
		'auth_id'=>(isset($_GET['id']) && isset($_GET['pass']))? $_GET['id']:$auth['uid'],

		'w' => 150,
		'h' => 85,
		'origo' => array(75,75),
		'radius' => 55,
		
		'midnight_fade' => 0.4,
		'sunrise_fade' => 0.8,
		'sunset_fade' => 0.8,
		
		'ground_height' => 15,
		'sun_size' => 20,
		'moon_size' => 15,
		'moon_position' => array(112.5,25),
		'stars_size' => 1,
		'stars_ratio' => 0.1,
		
		'colors' => array(
			'ground' => array(0,255,0),
			'sky' => array(0,0,255),
			'surface' => array(0,155,0),
			'sun' => array(255,217,0),
			'stars' => array(255,255,0),
			'moon' => array(255,255,0),
			'fade' => array(0,0,0),
			'fade_sun' => array(255,0,0),
			'fade_moon' => array(255,255,0),
			'fade_stars' => array(255,255,0)
		),
		
		'latitude' => 35.417,
		'longitude' => 19.2548,
		'horizont' => 90,
		'gmt_offset' => 1,
		'format' => SUNFUNCS_RET_TIMESTAMP
	);
	
?>