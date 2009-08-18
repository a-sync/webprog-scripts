// ==UserScript==
// @name		     QL Alt Server Browser (Vector's mod v2.2)
// @namespace	   http://d8d.org/
// @description  A redesign of the QL server browser. Thanks to sponge and coda for their contributions!
// @include	     http://*.quakelive.com/*
// @require	     http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.js
// @require	     http://tablesorter.com/jquery.tablesorter.min.js
// ==/UserScript==

/**
 * time to wait before refreshing the list
 */
var BRWS_REFTIME = 30; // value is in seconds

$("head").append(
	"<style type='text/css'>\n"
	+ ".even { background-color: #fefefe; background-repeat: no-repeat; background-position: left center; }\n"
	+ ".odd { background-color: #e6e6e6; background-repeat: no-repeat; background-position: left center; }\n"
	+ ".best { background-color: darkorange; background-repeat: no-repeat; background-position: left center; }\n"
	+ ".server_name { background-repeat: no-repeat; background-position: center center; }\n"
	+ ".city { background-repeat: no-repeat; background-position: left center; padding-left: 17px !important; }\n"

	+ "#qlv_postlogin_matches { width: 660px; height: 545px; margin-top: -5px; padding-top: 0; }\n"
	+ "#qlv_mainLogo { margin-top: 0 !important; }\n"
	+ ".mainLogo { margin-top: 0 !important; }\n"
	+ "#newnav_top { top: 57px !important; }\n"
	+ "#postlogin_dataready .filterbar_expanded { -moz-opacity: 0.8; opacity: 0.8; }\n"
	+ "#reloadlist { float: right; font-weight: bold; font-size: 9px; }\n"
	+ "#manual_connect, #demo_play, #command_line, #hidden_maps { color: #a02018; font-weight: bold; font-size: 9px; }\n"
	+ "#alt_tools { width: 620px; margin-left: 20px; padding-bottom: 4px; }\n"
	+ "#brws { background: url(data:image/gif;base64,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) no-repeat; display:none; width: 620px; margin-left: 20px; margin-bottom: 20px; border: 1px solid #A1A0A3; color: black; padding: 1px;}\n"
	+ "#brws th,.headerSortUp,.headerSortDown { color: white; font-size: 12px; padding: 2px; font-weight: bold; background: transparent;}\n"
	+ "#brws th:hover { cursor: pointer;}\n"
	+ "#brws td {padding: 1px;}\n"
	+ "#brws tbody tr:hover { background:url(data:image/gif;base64,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);color:white;border: 1px solid #000; cursor: default; }\n"
	+ "#brws td.skill { text-align:center; }\n"
	+ "#brws td.skill img { height:16px; width:16px; }\n"
	+ ".cond {letter-spacing: -1px;font-family:arial}\n"
	+ ".bold {font-weight:bold}\n"
	+ ".cur {cursor:pointer;}\n"
	+ ".left{float: left;}\n"
	+ ".right{float: right;}\n"
	+ 'body {background-color: #1b1612 !important;background-image: url(http://quakelive.com/css/valances/default/default.jpg) !important;background-position: center top !important;background-repeat: no-repeat !important;} #qlv_topFadeAds {display: none !important;} #spon_vert, img.spon_media {display: none !important;} #valance_clicker {display: none !important;} #qlv_contentBody {min-height: 0 !important;} #im-body, #qlv_chatControl {height: auto !important;} div.thirtypxhigh {display: none !important;} div.filterbar_notice {top: -18px !important;} #lgi_tip {margin-top: -10px;} .rosteritem-selected {background-color: darkorange !important; background-image: none !important;}'
	+ "</style>"
);

$.fn.hoverIntent=function(f,g){var cfg={sensitivity:7,interval:100,timeout:0};cfg=$.extend(cfg,g?{over:f,out:g}:f);var cX,cY,pX,pY;var track=function(ev){cX=ev.pageX;cY=ev.pageY};var compare=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);if((Math.abs(pX-cX)+Math.abs(pY-cY))<cfg.sensitivity){$(ob).unbind("mousemove",track);ob.hoverIntent_s=1;return cfg.over.apply(ob,[ev])}else{pX=cX;pY=cY;ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}};var delay=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);ob.hoverIntent_s=0;return cfg.out.apply(ob,[ev])};var handleHover=function(e){var p=(e.type=="mouseover"?e.fromElement:e.toElement)||e.relatedTarget;while(p&&p!=this){try{p=p.parentNode}catch(e){p=this}}if(p==this){return false}var ev=jQuery.extend({},e);var ob=this;if(ob.hoverIntent_t){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t)}if(e.type=="mouseover"){pX=ev.pageX;pY=ev.pageY;$(ob).bind("mousemove",track);if(ob.hoverIntent_s!=1){ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}}else{$(ob).unbind("mousemove",track);if(ob.hoverIntent_s==1){ob.hoverIntent_t=setTimeout(function(){delay(ev,ob)},cfg.timeout)}}};return this.mouseover(handleHover).mouseout(handleHover)}

quakelive = unsafeWindow.quakelive;
module = quakelive.mod_home;

// used for "quick actions"
var cmdString = "+set gt_user \"" + quakelive.username + "\" "
			  + "+set gt_pass \"" + quakelive.xaid + "\" "
			  + "+set gt_realm \"" + quakelive.siteConfig.realm + "\" ";


$.tablesorter.addParser({
	id: 'integer',
	is: function(s) { return false; }, // don't auto detect
	format: function(s) { return $.tablesorter.formatInt(s); },
	type: 'numeric'
});

$.tablesorter.addParser({
	id: 'players',
	is: function(s) { return false; }, // don't auto detect
	format: function(s) { var split = s.split("/"); return $.tablesorter.formatInt(split[0]); },
	type: 'numeric'
});


// retrieve the initial sort order
var brwsSort = [5,1]; // default: sort by player count DESC
if ( _cookie( 'brws_index' ) ) {
	brwsSort = _cookie( 'brws_index' ).split( ",", 2 );
}
$('#brws th:eq('+brwsSort[0]+')').addClass( 'sort'+brwsSort[1] );
set_cookie( 'brws_index', brwsSort.join() );


// shift+R will refresh the list
$(document).keypress( function (e) {
	if (e.which == 82) { $('#reloadlist').click(); } //shift+R
});


// module.ReloadServerList call was crashing FF
var autoReload = function() {
	$.ajax( {
		type: 		'get',
		url: 		'/home/matches/'+unsafeWindow.Base64.encode(unsafeWindow.JSON.stringify(module.filter)),
		success:	module.ReloadServerList_Success,
		error:		module.ReloadServerList_Error
	});
}


quakelive.mod_home.ShowServerListError = function ( msg ) {
	$('#qlv_postlogin_matches').html( 
		'<p class="tc thirtyPxTxt sixtypxv midGrayTxt">Unable to load the server list</p>' +
		'<p class="tc TwentyPxTxt midGrayTxt">' + msg + '</p>' +
		'<p class="tc TwentyPxTxt midGrayTxt"><a href="#" onclick="quakelive.mod_home.ReloadServerList();" style="color:#000">CLICK HERE</a> to try and reload&hellip;</p>'
	);

	module.waitHandle = setTimeout( autoReload, 1 * 1000 );
}


quakelive.mod_home.ReloadServerList_Success = function ( data ) {
	var json = quakelive.Eval( data );
	if ( !json || !json.servers ) {
		module.ShowServerListError( 'Unable to load server list', ( json && json.error ) || 'Unknown error' );
		return;
	}
	
	if(json.lfg_message){
		quakelive.SendModuleMessage( 'OnLFGMessage', { msg: json.lfg_message } );
	}
	
	// force invalidate any tooltips since matches could be shifted around between loads
	quakelive.HideTooltip();
	if ( json.servers ) {
		module.serverList = json.servers;	// load the new server list
		
		var mapthumb_url = $("#qlv_mainLogo img.mainLogo").attr("src").replace(/\.png$/, ".jpg").replace("sf/general/logo", "levelshots/md/%s");
		var flagicon_url = $("#qlv_mainLogo img.mainLogo").attr("src").replace(/\.png$/, "").replace("/images/sf/general/logo", "%s");
		var hidden_maps = _cookie('hidden_maps');
    
		// make sure all the server entries are properly initialized
		for ( var serverIndex = 0; serverIndex < module.serverList.length; ++serverIndex ) {
			var server = module.serverList[serverIndex];
			if ( !server.players ) {
				server.players = [];
			}
			
			var matchId = '#match_' + serverIndex;
			
			var cityName, flagIcon;
			var loc = unsafeWindow.locdb.GetByID(server.location_id);
			
			if ( loc ) {
				cityName = loc.GetCityState();
				flagIcon = loc.GetFlagIcon();
			} else {
				cityName = 'QUAKE LIVE';
				flagIcon = '/images/flags3cc/usa.gif';
			}
			
			var mapFullName = unsafeWindow.mapdb.getBySysName(server.map.toLowerCase()).name || "Unknown";
			
			var servName = server.host_name.split(" ");
			
			var gametype = quakelive.GetGameTypeByID( server.game_type );
			
			var tierName = 'TIER_' + gametype.name.toUpperCase();
			var skill = unsafeWindow.GetSkillRankInfo( server, quakelive.userinfo[tierName] );
			
			var s_nc = (server.num_clients > 9) ? server.num_clients : '<span style="visibility: hidden;">0</span>' + server.num_clients;
			var s_mc = (server.max_clients > 9) ? server.max_clients : server.max_clients + '<span style="visibility: hidden;">0</span>';
			var playerCountString = '<b>' + s_nc + '</b>' + ' / ' + s_mc;
			
			var hidethis = (hidden_maps.indexOf(server.map.toLowerCase()) != -1) ? true : false;
			
			var node = $('<tr id="' + matchId + '" style="'+ ( (hidethis) ? 'display: none; ' : '' ) +'" onmouseover="document.getElementById(\'' + matchId + '_sn\').style.backgroundImage=\'url(' + mapthumb_url.replace("%s", server.map.toLowerCase()) + ')\'; document.getElementById(\'' + matchId + '_hn\').style.visibility=\'hidden\';" onmouseout="document.getElementById(\'' + matchId + '_sn\').style.backgroundImage=\'none\'; document.getElementById(\'' + matchId + '_hn\').style.visibility=\'visible\'">'
				+ '<td style="background-image: url(' + flagicon_url.replace("%s", flagIcon.replace(/\.gif$/, "")) + '.gif);" class="city">' + cityName + '</td>'
				+ '<td id="' + matchId + '_sn" class="server_name" style=""><div class="bold left" id="' + matchId + '_hn">' + server.host_name + '</div></td>'
				+ '<td><span class="bold">' + server.map.toLowerCase() + '</span> - <span class="cond">' + mapFullName + '</span></td>'
				+ '<td>' + ((servName[0] == "pro") ? "pro " : "") + gametype.name + '</td>' // FIXME: assumes "pro" will be the first token
				+ '<td class="skill"><a onclick="prompt(\'Copy your link to clipboard. (Ctrl + C)\', \'http://www.quakelive.com/r/home/join/' + server.public_id + '\'); return false;" href="http://www.quakelive.com/r/home/join/' + server.public_id + '"><img border="0" src="' + skill.img + '" /></a></td>'
				+ '<td align="center">' + playerCountString + '</td>'
				+ '</tr>'
			);
			
			if ( serverIndex < 3 ) {
				node.addClass( 'best ' );
			}
			
			// FIXME: might not be safe to bind the tooltip right away
			/*
      node.each(
				function () {
					var server = module.serverList[serverIndex];	// store a local copy of this
					quakelive.matchtip.BindMatchTooltip( $(this), server.public_id );
				}
			);
      */
      var server = module.serverList[serverIndex];
      quakelive.matchtip.BindMatchTooltip( node, server.public_id );
      
			server.node = node;
		}

		module.ShowMatches();
		module.StopMatchRefresh();
    
		module.waitHandle = setTimeout( autoReload, BRWS_REFTIME * 1000 );
	}
};

quakelive.mod_home.ShowMatches = function () {
	var list = module.serverList;
	
	$('#brws tbody').empty();
	$('#reloadlist').val('Refresh').removeAttr('disabled').blur();
	
	$('#qlv_postlogin_matches').html("<div id='alt_tools' class='cl'><button id='reloadlist' onclick='quakelive.mod_home.ReloadServerList();this.value=\"Refreshing...\";this.disabled=\"true\";' title='Shift+R'>Refresh</button>"
									 + "<button id='manual_connect'>connect to...</button> &nbsp; "
									 + "<button id='demo_play'>play a demo...</button> &nbsp; "
									 + "<button id='command_line'>launch w/ command line...</button> &nbsp; "
									 + "<button id='hidden_maps'>hidden maps...</button>  &nbsp; </div>"
									 + "<table id='brws' border='1'>"
									 + "<thead><tr><th width='110'>Location</th><th>Server Name</th><th>Map</th><th>Mode</th>"
									 + "<th>S</th><th width='46'>#</th></tr></thead>"
									 + "<tbody></tbody></table>"
	);

	// FIXME: don't recreate header code each time...
	// requires class to be re-added
	$('#brws th:eq('+brwsSort[0]+')').addClass('sort'+brwsSort[1]);
	
	// update the sort order
	$('#brws th').click(function() {
		// couldn't get .index() to work...
		var val = $(this).html();
		var index = 0, fin = $('#brws th').length - 1;
		
		$('#brws th').each(function() {
			if ( $(this).html() === val ) { fin = index; return true; }
			$(this).removeClass('sort0 sort1');
			index++;
		});

		var head = $('#brws th:eq('+fin+')');
		if ( $(head).hasClass('sort0') ) {
			$(head).removeClass('sort0').addClass('sort1');
		} else if ( $(head).hasClass('sort1') ) {
			$(head).removeClass('sort1').addClass('sort0');
		} else {
			$(head).addClass('sort0');
		}
		
		brwsSort[0] = fin;
		brwsSort[1] = ($(head).hasClass('sort1')) ? 1 : 0;
		set_cookie( 'brws_index', brwsSort[0] + "," + brwsSort[1] );
	});

	// connect options	
	$("#manual_connect").click( function() {
	var ip = prompt("Type IP or game ID to manually join here (eg. 127.0.0.1:27015 or 77654)", _cookie('manual_connect'));
		if ((ip+'').toLowerCase() != 'null') set_cookie('manual_connect', ip)
		if ( ip != '' && ip.length > 7 && /^\d{1,3}[\.]\d{1,3}[\.]\d{1,3}[\.]\d{1,3}:\d{5}$/.test(ip) ) {
			unsafeWindow.LaunchGame(cmdString + "+connect " + ip);
		}
		else if (ip != '' && !isNaN(ip * 1)) window.location = 'http://www.quakelive.com/r/home/join/' + ip;
		return false;
	});

	$("#demo_play").click( function() {
		var demo = prompt("Type demo name to playback (eg. demoname)",  _cookie('demo_play'));
		if ((demo+'').toLowerCase() != 'null') set_cookie('demo_play', demo);
		if (demo) {
			unsafeWindow.LaunchGame(cmdString + "+exec demo.cfg +demo " + demo);
		}
		return false;
	});

	$("#command_line").click( function() {
		var cmdline = prompt("Type command line to launch game with (eg. +exec mycfg.cfg +devmap qzdm1)", _cookie('command_line'));
		if ((cmdline+'').toLowerCase() != 'null') set_cookie('command_line', cmdline);
		if (cmdline) {
			unsafeWindow.LaunchGame(cmdString + cmdline);
		}
		return false;
	});
	
	$("#hidden_maps").click( function() {
	  var maplist = prompt("Type your list of maps to hide (eg. qzdm6 qzca3)", _cookie('hidden_maps'));
	  if ((maplist+'').toLowerCase() != 'null') {
		  set_cookie('hidden_maps', maplist.toLowerCase());
		  $('#reloadlist').click();
		}
		return false;
	});
	// end connect options
	
	
	for ( var matchIndex in list ) {
		var server = list[matchIndex];
		// move the node to the proper location
		if ( server.node.parentNode ) {
		server.node.remove();
		}

		$('#brws tbody').append( server.node );
		matchIndex++;
	}

	module.refreshCount++;
	
	
	$("#brws:has(tbody tr)").tablesorter({sortList: [[brwsSort[0],brwsSort[1]],[5,1]], headers: {5: {sorter: 'players'}}, widgets: ['zebra']});
	//$("#brws:has(tbody tr)").tablesorter({sortList: [[5,1]], headers: {5: {sorter: 'players'}}, widgets: ['zebra']});
	
	$("#brws").show();
};

function _cookie(name, f)
{
  var cookie_place = document.cookie.indexOf(name + '=');

  if(cookie_place != -1)
  {
    return document.cookie.substr(cookie_place + name.length + 1).split('; ')[0];
  }
  else
  {
    if(f) return false;
    else return '';
  }
}

function set_cookie(name, val, del)
{
  if(del) del = 'Thu, 01-Jan-1970 00:00:01 GMT';
  else del = 'Mon, 22 Aug 2087 03:14:15 GMT';

  document.cookie = name + '=' + val + '; expires=' + del + '; path=/';
}

