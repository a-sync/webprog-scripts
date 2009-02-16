/* ---------- DEBUG ---------- */
var dbg_n = 0;
var dbg_box = false;
var dbg_objects = [];
var dbg_objects_n = 0;

function dbg(a, c, o)
{
//return true; //cock blocker
  if(a == 'cls') dbg_box.innerHTML = ''; //clear gomb

  if(o >= 0 && a === false)
  {
    a = dbg_objects[o];
  }
  
  if(dbg_box == false)
  {
    // eval doboz
    var dbg_eval_style = 'z-index: 666; top: 10px; right: 10px; color: lavender; width: 304px; height: 19px; background-color: #101010; border: 1px solid darkgray; font-size: 12px; font-family: Arial, Verdna, Tahoma, serif;';

    dbg_eval_box = document.createElement('input');

    dbg_eval_box.setAttribute('type', 'text');

    if(dbg_eval_box.attachEvent)
    {
      dbg_eval_box.attachEvent('onkeydown', function (event) {
        var o = event.currentTarget || event.srcElement;
        if(event.keyCode == 13 || event.which == 13)
        {
          if(o.value == 'cls') { dbg_box.innerHTML = ''; }
          else { dbg(eval(o.value), 5); }
        }
      });
    }
    else
    {
      dbg_eval_box.addEventListener('keydown', function (event) {
        var o = event.currentTarget || event.srcElement;
        if(event.keyCode == 13 || event.which == 13)
        {
          if(o.value == 'cls') { dbg_box.innerHTML = ''; }
          else { dbg(eval(o.value), 5); }
        }
      }, false);
    }
    
    dbg_eval_box.style.cssText = 'position: absolute; ' + dbg_eval_style;
    dbg_eval_box.setAttribute('style', 'position: fixed; ' + dbg_eval_style);

    document.body.insertBefore(dbg_eval_box, null);

    // debug doboz
    var dbg_style = 'text-align: left; right: 10px; top: 32px; z-index: 666; width: 300px; height: 100px; margin: 0; padding: 2px; overflow: auto; font-size: 10px; background-color: black; font-family: Arial, Verdna, Tahoma, serif; border: 1px dotted darkgray; line-height: 12px; color: dimgray;';

    dbg_box = document.createElement('div');

    dbg_box.style.cssText = 'position: absolute; ' + dbg_style;
    dbg_box.setAttribute('style', 'position: fixed; ' + dbg_style);

    dbg_box.setAttribute('onmouseover', 'this.style.height = \'90%\'; this.style.width = \'50%\';');
    dbg_box.setAttribute('onmouseout', 'this.style.height = \'100px\'; this.style.width = \'300px\';');

    document.body.insertBefore(dbg_box, null);
  }

  var s = '';
  switch(c)
  {
    case 1: color = 'darkred'; break;
    case 2: color = 'orange'; break;
    case 3: color = 'royalblue'; break;
    case 4: color = 'linen'; break;
    case 5: color = 'purple'; break;
    default: color = 'limegreen';
    c = 0;
  }
  c++;

  if(a instanceof Array || typeof(a) == 'object')
  {
    dbg(' ', (c - 1));
    dbg_n++;
    s += '<b>' + dbg_n + ' . </b>&nbsp; <span style="color: ' + color + ';">Array (</span><br/>';

    for(var i in a)
    {
      dbg_n++;
      if(a[i] instanceof Array || typeof(a[i]) == 'object')
      {
        dbg_objects[dbg_objects_n] = a[i];

        s += '<b>' + dbg_n + ' . </b>&nbsp; &nbsp; <span onclick="dbg(false, ' + c + ', ' + dbg_objects_n + ');" style="cursor: pointer; text-decoration: underline; color: ' + color + ';">[' + i + '] => ' + a[i] + '</span><br/>';

        dbg_objects_n++;
      }
      else
      {
        s += '<b>' + dbg_n + ' . </b>&nbsp; &nbsp; <span style="color: ' + color + ';">[' + i + '] => ' + a[i] + '</span><br/>';
      }
    }

    dbg_n++;
    s += '<b>' + dbg_n + ' . </b>&nbsp; <span style="color: ' + color + ';">)</span><br/>';
  }
  else
  {
    dbg_n++;
    s += '<b>' + dbg_n + ' . </b>&nbsp; <span style="color: ' + color + ';">' + a + '</span><br/>';
  }

  dbg_box.innerHTML += s;

  dbg_box.scrollTop = dbg_box.scrollHeight;
}