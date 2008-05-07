<html>
  <head>
    <link rel="stylesheet" href="defaultxx.css" type="text/css" />
    <script type="text/javascript">

      function debugxy(id, x, y)
      {
        var d = "clickable";
        var r = 10;
        var n = "";

        c = document.getElementById(id).innerHTML.split("<!---->", r);
        for(i=0; i<c.length-1; i++) n += c[i]+"<!---->";
        document.getElementById(id).innerHTML = "X: "+x+" Y: "+y+"<br/><!---->"+n;

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



      function get(eid)
      {
        var d = document;
        var r = d.getElementById(eid);
        return r;
      }

      function showMouseXY(e)
      {
        if ('undefined' == typeof e)
        {
          e = window.event;
          var x = e.offsetX;
          var y = e.offsetY;
        }
        else
        {
          var d = get('clickable');
          var l = d.offsetLeft;
          var t = d.offsetTop;
          var x = e.clientX - l;
          var y = e.clientY - t;
        }
        //alert('x=' + x + ' and y=' + y);
        debugxy("clickable", x, y);//debug
      }

      function register()
      {
        var d = get('clickable');

        if (!document.all)
        {
          d.addEventListener("click", showMouseXY, true);
        }
        else
        {
          d.attachEvent("onclick", showMouseXY);
        }
      }

      window.onload = register;
      //if(!document.all) document.captureEvents(Event.MOUSEMOVE)
      //document.onmousemove = showMouseXY;


    </script>
  </head>
  <body>
    <div id="container">


      <div class="room"> </div>
      <div id="clickable" class="room"> </div>
      <div class="room"> </div>


    </div>
  </body>
</html>