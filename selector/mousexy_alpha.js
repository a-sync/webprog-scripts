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

function set_o(obj) { o = obj; }

if(!document.all) document.captureEvents(Event.MOUSEMOVE);
document.onmousemove = showMouseXY;





var move = false;

var s = 0;
var bw = false;
var bh = false;
function drawSel1(e)
{
  if(document.getElementById(o.id+"sel") == null) o.innerHTML += ("<div id=\""+o.id+"sel\" style=\"margin: 0; position: relative; border: 1px dashed white; visibility: hidden;\"></div>");
  var sel = document.getElementById(o.id+"sel");

  if(s == 0)
  {
    mxy = showMouseXY(e);

    if(mxy[0] != false && mxy[1] != false)
    {
      sel.style.visibility = "visible";
      if(!move)
      {
        sel.style.left = mxy[0] + "px";
        sel.style.top = mxy[1] + "px";
        sel.style.width = "0px"
        sel.style.height = "0px"
      }
      bw = mxy[0];
      bh = mxy[1];
      s = 1;
    }
  }
  else if(s == 1) s = (move) ? 2 : 0;
  else if(s == 2) s = 0;
}

function drawSel2(e)
{
  var sel = document.getElementById(o.id+"sel");
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
        sel.style.width = bw + "px";
        sel.style.left = nx + "px";
      }
      else
      {
        nw = bw - mxy[0];
        sel.style.width = nw + "px";
        sel.style.left = mxy[0] + "px";
      }
      if(nh > 0 && mxy[1] > bh)
      {
        sel.style.height = bh + "px";
        sel.style.top = ny + "px";
      }
      else
      {
        nh = bh - mxy[1];
        sel.style.height = nh + "px";
        sel.style.top = mxy[1] + "px";
      }
    }
  }
}

if(!document.all) document.captureEvents(Event.MOUSEDOWN);
document.onmousedown = drawSel1;
if(!document.all) document.captureEvents(Event.MOUSEMOVE);
document.onmousemove = drawSel2;