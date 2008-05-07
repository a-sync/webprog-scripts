<html>
  <head>
    <link rel="stylesheet" href="default.css" type="text/css" />
    <script type="text/javascript">

      var rtd = false;
      function rte(that)
      {
        that.style.backgroundImage='url(rating-p.gif)';
        rtd = that;
      }
      function rto(that)
      {
        if(rtd != that) that.style.backgroundImage='url(rating.gif)';
        var rtd = false;
      }

      var rated = false;
      function rate(that, w)
      {
        if(w == 1) that.style.backgroundImage='url(rating-p.gif)';
        else that.style.backgroundImage='url(rating.gif)';

        //if(rated != false) rated.style.backgroundImage='url(rating-p.gif)';
        return true;
      }
      function rateit(that)
      {
        if(rated != that) {
          that.style.backgroundImage='url(rating-p.gif)';
          rated = that;
          //alert(rated.id);//debug
          return true;
        }
      }

      function rateimg(that)
      {
        if(that.src.substr(that.src.lastIndexOf("/")+1) != 'rating.gif') that.src = 'rating.gif';
        else that.src = 'rating-p.gif';
      }


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
/*
        var r = 5;
        var n = "";
        c = document.getElementById("coords").innerHTML.split("<!---->", r);
        for(i=0; i<c.length; i++) n += c[i]+"<!---->";
        document.getElementById("coords").innerHTML = "X: "+mouseX+" Y: "+mouseY+"<br/><!---->"+n;
*/
        return false;
      }



    </script>
  </head>
  <body>
    <div id="container">

<br /><br />

      <div id="star1" class="str" onclick="rte(this)" onmouseout="rto(this)">
        <div id="star2" class="str" onclick="rte(this)" onmouseout="rto(this)">
          <div id="star3" class="str" onclick="rte(this)" onmouseout="rto(this)">
            
          </div>
        </div>
      </div>

<br /><br />

      <div id="starx" class="str" onmouseover="rate(this, 1)" onmouseout="rate(this)" onclick="rateit(this)">
        <div id="stary" class="str" onmouseover="rate(this, 1)" onmouseout="rate(this)" onclick="rateit(this)">
          <div id="starz" class="str" onmouseover="rate(this, 1)" onmouseout="rate(this)" onclick="rateit(this)">
            
          </div>
        </div>
      </div>

<br /><br />

      <img src="rating.gif" class="strimg" onmouseout="rateimg(this)" onmouseover="rateimg(this)" onclick="rateimg(this)" />
      <img src="rating.gif" class="strimg" onmouseout="rateimg(this)" onmouseover="rateimg(this)" onclick="rateimg(this)" />
      <img src="rating.gif" class="strimg" onmouseout="rateimg(this)" onmouseover="rateimg(this)" onclick="rateimg(this)" />


      <div id="coords" onclick="getpos(this)"> </div>

    </div>
  </body>
</html>