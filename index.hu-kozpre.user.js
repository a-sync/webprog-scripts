// ==UserScript==
// @name           index kozepre v2
// @author         vec
// @include        http://index.hu*
// ==/UserScript==

var bg = (Math.random() < 0.5) ? 
'url("http://www.turf-tech-me.org/wp-content/flashxml/photo-rotator-fx/assets/backgroundImages/wood.jpg") repeat center center' : 
'url("http://www.hv-designs.co.uk/tutorials/webdesign_layout9/wood.jpg") repeat fixed center top';
// random background 50-50%

// a css
kell(' body{text-align:center !important;} body > div, body > ul {margin-left: auto !important; margin-right: auto !important; text-align: left !important;} #page {float: none !important;} #featured{left: auto !important;} #navi_belfold #featured {margin-left: -65px;} #navi_kulfold #featured {margin-left: -145px;} #navi_bulvar #featured {margin-left: -225px;} #navi_gazdasag #featured {margin-left: -300px;} #navi_tech #featured {margin-left: -397px;} #navi_tudomany #featured {margin-left: -460px;} #navi_kultur #featured {margin-left: -560px;} #navi_velemeny #featured {margin-left: -684px;} BODY {width: 1000px; margin: 15px auto 10px auto; box-shadow: 0px 0px 30px 6px black; -webkit-box-shadow: 0px 0px 30px 6px black; -webkit-border-radius: 7px; -moz-border-radius: 7px; border-radius: 7px; padding: 10px;} html { background: '+bg+';} #content {padding-left: 15px;} /*#superbanner { display: none;} #features { display: none;} #header { display: none;} #also_bannerek { display: none;} .hirdetes_container { display: none;} .hirdetes_container_bottom { display: none;}*/');

var t = false;
function kell(css){
  if(t === false) t = (typeof GM_addStyle != 'undefined') ? 1 : ((typeof addStyle != 'undefined') ? 2 : 0);
	if(css){
		if(t == 1)GM_addStyle(css);//GM
		else if(t == 2)addStyle(css);//Opera
		else{// FF, Opera, Chrome, IE
      var head = document.getElementsByTagName('head')[0];
      if (!head) { return; }
      var style = document.createElement('style');
      style.type = 'text/css';
      style.rel = 'stylesheet';
      style.innerHTML = css;
      head.appendChild(style);
		}
	}
}