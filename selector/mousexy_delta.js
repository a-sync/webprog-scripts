function debugxy(x, y)//debug
{
  if(o == false) { return false; }

  var r = 10;

  var n = "";
  c = o.innerHTML.split("&nbsp;", r);
  for(i = 0; i < c.length - 1; i++) n += c[i] + "&nbsp;";
  o.innerHTML = "X: " + x + " Y: " + y + "&nbsp;<br/>" + n;

  return true;
}



/*

EFFECT

*/

var fade_elements = [];
var fade_timeouts = [];
function fade_elem_opacity(e, to, op_diff, time_int, display_none)
{
  if(!op_diff) op_diff = 5;
  if(!time_int) time_int = 50;
  if(!display_none) display_none = 0;

  var elem = fade_elements[e];
  var eo = elem.style.opacity;

  if(eo !== 0 && (eo == '' || isNaN(eo))) eo = 100;
  else eo = eo * 100;

  if(to > eo)
  {
    var no = eo + op_diff;
    if(no > to) no = to;
  }
  else// if(to < eo)
  {
  var no = eo - op_diff;
    if(no < to) no = to;
  }
  //dbg(no, 5);

  set_opacity(elem, no);

  if(no == 0)
  {
    if(display_none == 1) elem.style.display = 'none';
    else elem.style.visibility = 'hidden';
  }

  if(!fade_timeouts[e])
  {
    fade_timeouts[e] = setInterval('fade_elem_opacity(' + e + ', ' + to + ', ' + op_diff + ', ' + time_int + ', ' + display_none + ');', time_int);
  }
  else if(no == to)
  {
    clearInterval(fade_timeouts[e]);
    fade_timeouts[e] = false;
  }

  return true;
}

function set_opacity(elem, op) {
 if(!op) op = 0;
 elem.style.opacity = (op * 0.01);
 elem.style.MozOpacity = (op * 0.01);
 elem.style.KhtmlOpacity = (op * 0.01);
 elem.style.filter = 'alpha(opacity=' + op + ')';
 return true;
}





/*
//get mouse position
if(!document.all) document.captureEvents(Event.MOUSEMOVE)
document.onmousemove = getMouseXY;

function getMouseXY(e) {
  if (!e) var e = window.event;
  var mouseX = 0;
  var mouseY = 0;
  if(document.all) {
    mouseX = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
    mouseY = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
  }
  else {
    mouseX = e.pageX;
    mouseY = e.pageY;
  }

  debugxy(mouseX, mouseY);//debug

  return false;
}
*/

var block = true;

var o = false;
var x = false;
var y = false;
var w = false;
var h = false;
function showMouseXY(e)
{
  if(typeof e == "undefined")
  {
    e = window.event;
    var x = e.offsetX;
    var y = e.offsetY;
    //if(o == false) o = document.body;
  }
  else
  {
    var x = e.clientX - o.offsetLeft;
    var y = e.clientY - o.offsetTop;
  }
  var w = o.offsetWidth;
  var h = o.offsetHeight;

  if(block == true)
  {
    if(x > w || x < 0) x = false;
    if(y > h || y < 0) y = false;
    x = (y == false) ? false: x;
    y = (x == false) ? false: y;
  }

  //debugxy(x, y);//debug
  //debugxy(w, h);//debug
  return [x, y, w, h];
}

function set_o(obj)
{
  o = obj;
  o.style.textAlign = "left";//debug
}

//if(!document.all) document.captureEvents(Event.MOUSEMOVE);
//document.onmousemove = showMouseXY;

// 1x1, akkor display: none;



var move = false;

var s = 0;
var bw = false;
var bh = false;
function drawSel1up(e) {
  drawSel1(e, true);
}
function drawSel1(e, up)
{
  if(document.getElementById(o.id + "sel") == null) o.innerHTML += ("<div id=\"" + o.id + "sel\" style=\"margin: 0; position: relative; border: 1px dashed white; visibility: hidden;\"><div id=\"" + o.id + "sel-effect\"></div></div>");
  var sel = document.getElementById(o.id + "sel");
  
  // effect
  //tip_init(e);
  //tip_init();
  
  var sel_e = document.getElementById(o.id + "sel-effect");
  fade_elements[0] = sel_e;
  
  //if(up == true) s = 1;
  
  if(s == 0 && up != true)
  {
    mxy = showMouseXY(e);

    if(mxy[0] != false && mxy[1] != false)
    {
      sel.style.visibility = "visible";
      sel.style.left = mxy[0] + "px";
      sel.style.top = mxy[1] + "px";
      if(!move)
      {
        sel.style.width = "0px"
        sel.style.height = "0px"
      }
      bw = mxy[0];
      bh = mxy[1];
      s = 1;
      
      if(up != true) set_opacity(sel_e, 0); // effect
    }
  }
  else if(s == 1)
  {
    s = 0;
    if(up == true)
    {
      if(sel.style.width.split('px')[0] < 5 && sel.style.height.split('px')[0] < 5)
      {
        sel.style.visibility = "hidden";
        tip_box(false);
      }
      
      fade_elem_opacity(0, 30, 2, 20); // effect
      //tip_box(false);
    }
  }
  
  sel_e.style.width = sel.style.width;
  sel_e.style.height = sel.style.height;
}

function drawSel2(e)
{
  var sel = document.getElementById(o.id + "sel");
  if(s == 1)
  {
    mxy = showMouseXY(e);

    nx = sel.style.left.split("px");
    ny = sel.style.top.split("px");
    nw = mxy[0] - nx[0];
    nh = mxy[1] - ny[0];

    if(mxy[0] != false && mxy[1] != false)
    {
      if(nw > 0 && mxy[0] > bw)
      {
        sel.style.width = nw + "px";
        sel.style.left = bw + "px";
      }
      else
      {
        nw = bw - mxy[0];
        sel.style.width = nw + "px";
        sel.style.left = mxy[0] + "px";
      }
      if(nh > 0 && mxy[1] > bh)
      {
        sel.style.height = nh + "px";
        sel.style.top = bh + "px";
      }
      else
      {
        nh = bh - mxy[1];
        sel.style.height = nh + "px";
        sel.style.top = mxy[1] + "px";
      }
    }
    
    tip_box('w: ' + sel.style.width + '&lt;br/&gt;h: ' + sel.style.height);
    tip_pos(e);
  }
}



/*
function drawSel3(e)
{
  s = 0;
}


if(!document.all) document.captureEvents(Event.MOUSEUP);
document.onmousemove = drawSel1;

if(!document.all) document.captureEvents(Event.MOUSEDOWN);
document.onmousedown = drawSel1;
if(!document.all) document.captureEvents(Event.MOUSEMOVE);
document.onmousemove = drawSel2;
*/
if(window.attachEvent) window.attachEvent('onmousemove', drawSel2);
else window.addEventListener('mousemove', drawSel2, false);

if(window.attachEvent) window.attachEvent('onmousedown', drawSel1);
else window.addEventListener('mousedown', drawSel1, false);
if(window.attachEvent) window.attachEvent('onmouseup', drawSel1up);
else window.addEventListener('mouseup', drawSel1up, false);

/*

if ( window.addEventListener ) window.addEventListener( "load", CheckboxKezeles, false );
else if ( window.attachEvent ) window.attachEvent( "onload", CheckboxKezeles );
*/
