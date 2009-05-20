// ==UserScript==
// @name           QL tools
// @namespace      http://www.w3.org/1999/xhtml
// @include        http://www.quakelive.com*
// ==/UserScript==


if(window.attachEvent) window.attachEvent('onload', ql_cleanup);
else window.addEventListener('load', ql_cleanup, false);


if(window.attachEvent) window.attachEvent('ondblclick', qll_init);
else window.addEventListener('dblclick', qll_init, false);

function qll_init(event)
{
  var mail_mezo = _id('in_email');
  var jelszo_mezo = _id('in_password');
  var megjegyez_mezo = _id('in_remember');
  var chat_msg = _id('chat-msg');

  if(mail_mezo != false && jelszo_mezo != false)
  {
    if(mail_mezo.value == 'user1@gmail.com')
    {
      mail_mezo.value = 'user1@gmail.com';
      jelszo_mezo.value = 'pw01';
    }
    else
    {
      mail_mezo.value = 'user2@gmail.com';
      jelszo_mezo.value = 'pw02';
    }
    //alert('dbg1');
    
    megjegyez_mezo.checked = true;
  }
  else if(chat_msg != false)
  {
    // Quake Live active chat overflow eXpl0it
    // **************************************************
    var _str = '\\\\¯\\\_\(\ツ\)\_\/\¯\\//';
    for(var i = 0; i < 50000; i++) _str += '++++++++++';
    // **************************************************
    
    if(chat_msg.value == '') chat_msg.value = _str;
  }
}

function _id(id) {
  if(!id) return false;
  var id = document.getElementById(id);
  if(!id) return false;
  //else return true;
  else return id;
}


/* kinezét */
// állapítsuk meg milyen környezetben vagyunk, hogy ne kelljen minden futásnál
var t = (typeof GM_addStyle != 'undefined') ? 1 : ((typeof addStyle != 'undefined') ? 2 : 0);
//if(GM_addStyle) var t = 1; else if(addStyle) var t = 2; else var t = 0;
function add_css(css)
{
	if(css)// ha létezik a kapott változó
	{
	//GM az első, mert az tartalmazhat másfajta addStyle-t
		if(t == 1)// GreaseMonkey esetén (GM_addStyle)
		{
			GM_addStyle(css);
		}
		else if(t == 2)// Opera esetén (addStyle)
		{
			addStyle(css);
		}
		else// egyéb (ha van a böngészőben funkció sima .js fájlok betöltésére oldalakhoz, akkor is működik)
		{
      var head = document.getElementsByTagName('head')[0];
      if (!head) { return; }
      var style = document.createElement('style');
      style.type = 'text/css';
      style.innerHTML = css;
      head.appendChild(style);
		}
	}
}

function ql_cleanup()
{
  if(_cookie('QLALU') == '' || _cookie('QLALP') == '') return true;
  else if(_id('in_password') != false && _id('in_email') != false) window.location.reload(true);

  add_css('body {background-color: #05000A !important;background-image: url(http://quakelive.com/css/valances/default/default.jpg) !important;background-position: center top !important;background-repeat: no-repeat !important;}#qlv_topFadeAds {display: none !important;}#spon_vert, img.spon_media {display: none !important;}#valance_clicker {display: none !important;}div.personal_stats {overflow: hidden !important;height: 44px !important;height: 230px !important;}/*div.personal_stats:hover {height: auto !important;}*/#qlv_contentBody {min-height: 0 !important;}#im-body, #qlv_chatControl {height: auto !important;}div.thirtypxhigh {display: none !important;/*height: 10px !important;*/}div#qlv_mainLogo {margin-top: 0 !important;height: 37px !important;}div#qlv_mainLogo a {display: none;}div.mainLogoRight a {display: inline !important;}div.mainLogoRight {float: right !important;height: 33px !important;width: 980px !important;}div.mainLogoRight div.fourpxv {display: none !important;}div#qlv_userInfoContainer {width: 490px !important;margin-left: 0px !important;float: left !important;height: 33px !important;}a#qlv_userTmb {display: none !important;}#qlv_userInfo {left: 0px !important;width: 485px !important;height: 33px !important;}div#qlv_navBackground {float: left !important;width: 485px !important;bottom: 0 !important;}div#qlv_siteStatus {top: -23px !important;}div#qlv_siteStatus div.front, div#qlv_siteStatus div.outline {background-color: firebrick !important;}div#qlv_topLinks {position: relative !important;top: -20px !important;}div#qlv_postlogin_matches {padding-top: 0 !important;}div.qlv_pls_box, div.qlv_pls_bestpick_box {background-image: none !important;margin-bottom: 8px !important;height: 123px !important;overflow: hidden !important;}img.best_pick {display: none !important;}div.filterbar_notice {top: -18px !important;}div.qlv_inner_box {bottom: -7px !important;left: 9px !important;}div.qlv_pls_box img.thumb, div.qlv_pls_bestpick_box img.thumb {top: 0px !important;}div.qlv_inner_box div.players {font-weight: bold !important;}');
}

/* adott süti kinyerése */
function _cookie(name)
{
  var cookie_place = document.cookie.indexOf(name + '=');

  if(cookie_place != -1)
  {
    return document.cookie.substr(cookie_place + name.length + 1).split('; ')[0];
  }
  else
  {
    return '';
  }
}