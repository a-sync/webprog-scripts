// @name        Vec JSmenu
// @namespace   http://www.onethreestudio.com/
// @version     1.00
// @author      Vector Akashi
// @description Simple JavaScript menu handler.

var hiding = new Array(0);
var opened = new Array(0);

function show(id, level)
{
  if(id != false)
  {
    var e = document.getElementById(id);
    if(e.style.visibility == "hidden")
    {
      e.style.visibility = "visible";
      e.style.zIndex = "666";
    }

    hiding[id] = 0;

    opened[id] = level;
  }

  for(i in opened)
  {
    if(level >= opened[i] && i != id) document.getElementById(i).style.visibility = "hidden";
  }
}

function hide(id, epoch)
{
  if(epoch)
  {
    if(hiding[id] == epoch) document.getElementById(id).style.visibility = "hidden";
  }
  else
  {
    document.getElementById(id).style.zIndex = "314";

    var date = new Date();
    var epoch = date.getTime();

    hiding[id] = epoch;
    setTimeout("hide('"+ id +"', '"+ epoch +"')", 500);
  }
}