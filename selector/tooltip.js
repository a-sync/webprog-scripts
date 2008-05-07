// version 0.2 beta
// All Rights Reserved to www.nextserver.hu

//néhány beállítás
var tipID = 'tipbox';//a doboz ID-je
var tipBox = false;//a doboz objektum
var tipElemCSS = 'cursor: help;';//ha van belőve css előre akkor legyen false
var tipBoxCSS = 'color: #1c1a1b; visibility: hidden; position: absolute; z-index: 314; max-width: 250px; padding: 4px; background-color: #fff0cc; border: 1px solid gray; text-align: left;';//ha van belőve css előre akkor legyen false
var tipFollow = true;//az objektumon mozgatva, kövesse-e a tooltip az egeret

var tipOnload = true;//oldalbetöltéskor keresse meg a beálított atribútumú elemeket és rendelje a megfelelő eventet hozzá
var tipAttr = 'rel';//milyen attribútumba van rakva a szöveg
var tipTags = false;//adott tageket nézzen meg tipAttr attributumot keresve (false/* mind)


//inicializálás indítása onload-ra
if(tipOnload)
{
  if(window.attachEvent) window.attachEvent('onload', tip_init);
  else window.addEventListener('load', tip_init, false);
}

//inicializálás, beállított attributumnak megfelelően rendelje hozzá az eventeket
function tip_init(e)
{
  if(!tipTags) var tipTags = '*';
  var elements = document.getElementsByTagName(tipTags);

  var i;
  for(i in elements)
  {
    var elem = elements[i];
    if(elem.attributes && elem.attributes[tipAttr] && elem.attributes[tipAttr].value.substr(0, 4) == 'tip:')//ha szerepel benne a tip attributum
    {
      if(elem.attachEvent) elem.attachEvent('onmouseover', tip);
      else elem.addEventListener('mouseover',  tip, false);
	  
	    elem.setAttribute('style', tipElemCSS);
      elem.style.cssText = tipElemCSS;//ie fix
    }
  }
}

//szöveg kinyerése, föléhúzásra doboz megjelenítése (tip_box), pozíció követése (tip_pos), és kihúzásra event, doboz eltüntetése
function tip(event, text)
{
  if(event == false && !text) tip_box(false);
  else
  {
    var event = (!event) ? window.event : event;

    if(event)
    {
      var elem = event.currentTarget || event.srcElement;

      if(!text && elem.attributes && elem.attributes[tipAttr] && elem.attributes[tipAttr].value.substr(0, 4) == 'tip:') var text = elem.attributes[tipAttr].value.substr(4);
      else if(!text) text = false;
    }
    else text = false;
  }

  if(text) //onmouseover
  {
    tip_pos(event);//dbg

    if(elem.attachEvent)
    {
      if(tipFollow == true) elem.attachEvent('onmousemove', tip_pos);
      elem.attachEvent('onmouseout', function () { tip(false); });
    }
    else
    {
      if(tipFollow == true) elem.addEventListener('mousemove', tip_pos, false);
      elem.addEventListener('mouseout', function () { tip(false); }, false);
    }

    tip_box(text);//ha máshonnan kel szedni a szöveget akkor itt a helye
  }
}

//doboz létrehozása, megjelenítése, eltüntetése a kapott tartalommal
function tip_box(text)
{
  if(tipBox == false) //ha nincsen belőve a doboz, akkor inicializálni
  {
    tipBox = document.getElementById(tipID);
    if(!tipBox)
    {
      tipBox = document.createElement('div');
      tipBox.setAttribute('id', tipID);

      if(tipBoxCSS)
      {
        tipBox.setAttribute('style', tipBoxCSS);
        tipBox.style.cssText = tipBoxCSS;//ie fix
      }

      document.body.insertBefore(tipBox, null);//nem tolja el a DOM fát

      tipBox = document.getElementById(tipID);
    }
  }

  if(text == false)
  {
    tipBox.style.visibility = 'hidden';
    //tipBox.innerHTML = '';//tartalom ürítése
  }
  else
  {
    tipBox.style.visibility = 'visible';
    tipBox.innerHTML = text.split('&lt;').join('<').split('&gt;').join('>');//########## todo: adatszűrés! ##########
  }
}

//doboz pozíciójának frissítése az egér pozíciója alapján
function tip_pos(event)
{
  if(tipBox == false) tip_box(false);

  var mPos = mouSeXY(event);
  var o = event.currentTarget || event.srcElement;//dbg

  tipBox.style.left = (mPos[0] + 10) + 'px';
  tipBox.style.top = (mPos[1] + 20) + 'px';
}

/* egér x és y koordinátája az oldalon */
function mouSeXY(event)
{
  var mX = 0;
	var mY = 0;

  var e = (!event) ? window.event : event;
  //var elem = e.currentTarget || e.srcElement;

  if(e.pageX || e.pageY)
  {
    mX = e.pageX;
    mY = e.pageY;
  }
  else if(e.clientX || e.clientY)
  {
    mX = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
    mY = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
  }

  return [mX, mY];
  //return [mX, mY, elem];
}

//todo: a tooltip magasságát bekérni és az alapján meg az oldal méretei alapján eltolni ha valahol kilógna
//todo: settimouttal idoztetése az eltüntetésnek