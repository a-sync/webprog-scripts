/* ---------- DEBUG ---------- */
var dbg_n = 0;
var dbg_box = false;

function dbg(a, color)
{
//return true;//cock blocker

  if(dbg_box == false)
  {
    // eval doboz
    var dbg_eval_style = 'z-index: 666; top: 10px; right: 10px; color: lavender; width: 242px; height: 19px; background-color: #101010; border: 1px solid darkgray; font-size: 12px; font-family: Arial, Verdna, Tahoma, serif;';

    dbg_eval_box = document.createElement('input');

    dbg_eval_box.setAttribute('type', 'text');

    if(dbg_eval_box.attachEvent) dbg_eval_box.attachEvent('onkeydown', function (event) { var o = event.currentTarget || event.srcElement; if(event.keyCode == 13 || event.which == 13) dbg(eval(o.value), 5); });
    else dbg_eval_box.addEventListener('keydown', function (event) { var o = event.currentTarget || event.srcElement; if(event.keyCode == 13 || event.which == 13) dbg(eval(o.value), 5); }, false);
    
    dbg_eval_box.style.cssText = 'position: absolute; ' + dbg_eval_style;
    dbg_eval_box.setAttribute('style', 'position: fixed; ' + dbg_eval_style);

    document.body.insertBefore(dbg_eval_box, null);

    // debug doboz
    var dbg_style = 'right: 10px; top: 32px; z-index: 666; width: 236px; height: 50px; margin: 0; padding: 2px; overflow: auto; font-size: 10px; background-color: black; font-family: Arial, Verdna, Tahoma, serif; border: 1px dotted darkgray; line-height: 12px; color: dimgray;';

    dbg_box = document.createElement('div');

    dbg_box.style.cssText = 'position: absolute; ' + dbg_style;
    dbg_box.setAttribute('style', 'position: fixed; ' + dbg_style);

/*
    if(dbg_box.attachEvent) dbg_box.attachEvent('onmouseover', function (event) { var o = event.currentTarget || event.srcElement; setTimeout('o.style.height = \'500px\';', 2000); });
    else dbg_box.addEventListener('mouseover', function (event) { var o = event.currentTarget || event.srcElement;  setTimeout('o.style.height = \'500px\';', 2000); }, false);

    if(dbg_box.attachEvent) dbg_box.attachEvent('onmouseout', function (event) { var o = event.currentTarget || event.srcElement; o.style.height = '50px'; o.scrollTop = o.scrollHeight; });
    else dbg_box.addEventListener('mouseout', function (event) { var o = event.currentTarget || event.srcElement;  o.style.height = '50px'; o.scrollTop = o.scrollHeight; }, false);
*/
    dbg_box.setAttribute('onmouseover', 'this.style.height = \'90%\';');
    dbg_box.setAttribute('onmouseout', 'this.style.height = \'50px\';');
    //dbg_box.setAttribute('ondblclick', 'this.style.height = (this.style.height == \'50px\') ? \'50px\' : \'500px\';');

    document.body.insertBefore(dbg_box, null);
  }

  var s = '';
  switch(color)
  {
    case 1: color = 'darkred'; break;
    case 2: color = 'orange'; break;
    case 3: color = 'royalblue'; break;
    case 4: color = 'linen'; break;
    case 5: color = 'purple'; break;
    default: color = 'limegreen';
  }

  if(a instanceof Array || typeof(a) == 'object')
  {
    dbg_n++;
    s += '<b>' + dbg_n + '.</b>&nbsp; <span style="color: ' + color + ';">Array (</span><br/>';

    for(var i in a)
    {
      dbg_n++;
      s += '<b>' + dbg_n + '.</b>&nbsp; &nbsp; <span style="color: ' + color + ';">[' + i + '] => ' + a[i] + '</span><br/>';
    }

    dbg_n++;
    s += '<b>' + dbg_n + '.</b>&nbsp; <span style="color: ' + color + ';">)</span><br/>';
  }
  else
  {
    dbg_n++;
    s += '<b>' + dbg_n + '.</b>&nbsp; <span style="color: ' + color + ';">' + a + '</span><br/>';
  }

  dbg_box.innerHTML += s;

  dbg_box.scrollTop = dbg_box.scrollHeight;
}