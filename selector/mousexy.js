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

  debugxy("clickable", mouseX, mouseY);//debug

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

//if(!document.all) document.captureEvents(Event.MOUSEMOVE);
//document.onmousemove = showMouseXY;







if(!document.all) document.captureEvents(Event.MOUSEMOVE);//debug
document.onmousemove = drawSel2;//debug

var s = 0;

function drawSel1()
{
  if(s == 0) s = 1;
  else if(s == 1) s = 2;
  else if(s == 2) s = 0;
}

function drawSel2(e)
{
  var sel = document.getElementById("selector");

  if(s == 1)
  {
    mxy = showMouseXY(e);
    x = mxy[0];
    y = mxy[1];

    if(x != false && y != false)
    {
      sel.style.left = x + "px";
      sel.style.top = y + "px";
    }
  }
  else if(s == 2)
  {
    mxy = showMouseXY(e);
    x = mxy[0];
    y = mxy[1];

    nx = sel.style.left.split("px");
    ny = sel.style.top.split("px");
    nw = x - nx[0];
    nh = y - ny[0];

    if(x != false && y != false)
    {
      document.getElementById("selector").style.width = nw + "px";
      document.getElementById("selector").style.height = nh + "px";
    }
  }
}