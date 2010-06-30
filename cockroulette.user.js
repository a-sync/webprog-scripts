// ==UserScript==
// @name           cockRoulette
// @namespace      http://redir.at
// @description    Chatroulette tools...
// @version        1.18
// @author         Vector
// @include        http://*chatroulette.com/*
// @include        https://*chatroulette.com/*


// ==/UserScript==

var panel_num = 15;

if(window.attachEvent) window.attachEvent('onload', cockRoulette);
else window.addEventListener('load', cockRoulette, false);

function cockRoulette(event)
{
  /* panel setup */
  add_css(''
   + '#panel { position: relative; z-index: 10; float: right; background: url("http://chatroulette.com/img/buttons.png") repeat-y 0 0 #dee3e9; width: 400px; height: ' + (panel_num * 31) +'px; border: 4px solid #dee3e9; -moz-border-radius: 7px; -webkit-border-radius: 7px; border-radius: 7px; }'
   
   + '.txts { width: 285px; height: 20px; outline: none; border: 1px solid lightgray; font: 11px, Calibri, Verdana, sans-serif; color: #000; margin: 5px 0 4px 10px; padding: 0; display: inline-block; background: #fefefe; }'
   
   + '.btns {	font: 10px Verdana; color: #000; margin: 0; padding: 0; display: inline-block; width: 97px; height: 23px; cursor: pointer; outline: none; border: none; line-height: 20px; position: relative; top: 0; background: url("http://chatroulette.com/img/buttons_bg.png") no-repeat 0 0 lightgray; }'
   
   + '.btns:hover{ background-position: 0 -23px; }'
   + '.btns:focus  { background-position: 0 -69px; }'
   + '.btns:active { background-position: 0 -46px; }'
   
   + '.btn_repeat { font: 9px Verdana; color: #000; margin: 0; padding: 0; display: inline-block; width: 14px; height: 14px; cursor: pointer; outline: none; border: none; }'
   + '.btn_repeat:hover, .btn_repeat:focus { background: darkgreen; color: lightgray; }'
   + '.btn_repeat:active { background: darkred; color: white; }'
   + '#log { width: auto !important; margin-right: 392px !important; position: static !important; }'
   + '.cam { width: 324px !important; /*fw res*/ }'
   + '#chat { left: 330px !important; }'
   
   + '');
  
  var panel = document.createElement('DIV');
  panel.id = 'panel';
  panel.innerHTML = '';
  
  for(var i = 0; i < panel_num; i++)
  {
    panel.innerHTML += '<input onkeypress="if(((event.keyCode == 0xA) || (event.keyCode == 0xD))) { getMovie().messageFromUser(this.value); }" class="txts" id="txt_' + i + '" type="text" value="' + _cookie('txt_' + i) + '" /> <input class="btns" id="btn_' + i + '" type="button" value="Send text" onclick="getMovie().messageFromUser(get(\'txt_' + i + '\').value);"/><br/>';
  }
  
  _id('chat').appendChild(panel);
  
  /* events setup */
  for(var i = 0; i < panel_num; i++)
  {
    if(_id('btn_' + i).attachEvent) _id('btn_' + i).attachEvent('onclick', panel_send);
    else _id('btn_' + i).addEventListener('click', panel_send, false);
    
    if(_id('txt_' + i).attachEvent) _id('txt_' + i).attachEvent('onchange', panel_set);
    else _id('txt_' + i).addEventListener('change', panel_set, false);
  }
  
  log_check(true);
}

var log_size = 0;
function log_check(e)
{
  if(e == false)
  {
    // ciklus
    if(_id('log').innerHTML.length != log_size)
    {
      var lines = _id('log').innerHTML.split('<br>');
      
      for(var i in lines)
      {
        if(lines[i].indexOf('in:') === 0)
        {
          lines[i] = '<input title="Repeat" class="btn_repeat" type="button" value="+" onclick="getMovie().messageFromUser(\'' + lines[i].split('in:').slice(1).join('') + '\'); this.value=\'*\';"/> ' + lines[i];
        }
        else if(lines[i].indexOf('out:') === 0)
        {
          lines[i] = '<input title="Repeat" class="btn_repeat" type="button" value="+" onclick="getMovie().messageFromUser(\'' + lines[i].split('out:').slice(1).join('')  + '\'); this.value=\'*\';"/> ' + lines[i];
        }
      }
      
      _id('log').innerHTML = lines.join('<br />');
      log_size = _id('log').innerHTML.length;
    }
    else
    {
      // blank
    }
  }
  else if(log_size == 0)
  {
    setInterval(log_check, 200);
  }
}

function panel_set(event)
{
  var elem = event.srcElement || event.currentTarget;
  
  if(elem.id && elem.id.indexOf('txt_') == 0)
  {
    var n = elem.id.split('_')[1];
    var v = elem.value;
    
    set_cookie('txt_' + n, v);
  }
}

function panel_send(event)
{
  var elem = event.srcElement || event.currentTarget;
  
  if(elem.id != '' && elem.id.indexOf('btn_') == 0)
  {
		_id('log').scrollTop = _id('log').scrollHeight;
		_id('chatarea').focus();
  }
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

function _id(id)
{
  if(!id) return false;
  var id = document.getElementById(id);
  if(!id) return false;
  //else return true;
  else return id;
}