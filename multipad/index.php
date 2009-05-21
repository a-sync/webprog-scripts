<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title>Multipad</title>

	<script type="text/javascript">
	<!--
	  if(window.attachEvent) window.attachEvent('onload', mp_srv_init);
    else window.addEventListener('load', mp_srv_init, false);
    
	  if(window.attachEvent) window.attachEvent('ondblclick', mp_srv_refresh);
    else window.addEventListener('dblclick', mp_srv_refresh, false);
    
	  var pads = [];
	  pads[0] = 0; // amelyik fókuszban van
	  pads[1] = 'text_pad1';
	  pads[2] = 'text_pad2';
	  pads[3] = 'text_pad3';
	  
    function mp_srv_refresh(forced)
    {
      //alert(pads[0]);
      if(pads[0] == 0 || forced == true) window.location.reload(true);
    }
    
	  function mp_srv_init()
	  {
	    //eventek hozzárendelése for-ral
	    
	    //setInterval('refresh_pads();', 1000); // nemkell-e mégsézéshez
	    //alert(pads.length);
	    for(var i = 1; i < pads.length; i++)
	    {
	      //alert('pads['+i+']= '+pads[i]);
	      pads[i].disabled = true;
	      
	      setTimeout('refresh_pad('+i+', true);', ((700 * i) + 50));
	      //setTimeout('refresh_pad('+i+', true);', 1000);
	      
	      //setInterval('refresh_pad('+i+');', 1000);
	    }
	  }
	  
	  function pad_focus(n)
	  {
      log(n, 'focus');
      pads[0] = n;
      if(n > 0) _id(pads[n]).style.backgroundColor = '#eaead8';
	  }
	  
	  function pad_keyup(n)
	  {
	    log(n, 'keyup');
	    
	    //adatok küldése (eseményenként töltődik fel)
      var keres = [];
      keres['n'] = n;
      keres['value'] = _id(pads[n]).value;
      ajax_post(keres, 10);
	  }
	  
	  function pad_blur(n)
	  {
	    log(n, 'blur');
      
	    pads[0] = 0;
	    setTimeout('refresh_pad('+n+');', 1000);
	    if(n > 0) _id(pads[n]).style.backgroundColor = '#fdfdee';
	  }
	  
	  
	  function refresh_pad(n, force)//pad refreshre atnevezni
	  {
	    //alert('refresh_pad n: '+n);
	    //alert('pads[0]: '+pads[0]);
	    
      if(pads[0] == n && force != true) // ez van fókuszban, ezt küldeni
	    {
	      //pad_keyup(n); // (ciklusokban töltődik fel)
	    }
	    else if(pads[n]) // ezt olvasni
	    //if(pads[n])
	    {
	      //pads[i].value;
	      //adatok betöltése
        if(force == true) pads[n].disabled = true;
        var keres = [];
        keres['n'] = n;
        ajax_post(keres, 20);
	    }
	  }
	  
	  var log_c = 0;
	  function log(n, text)
	  {
	    var elem = _id(pads[n]);
	    
	    switch(text)
	    {
	      case 'focus': elem.style.borderColor = pad_color(n);
          break;
	      case 'blur': elem.style.borderColor = pad_color(0);
	        break;
	    }
	    
	    return true;//debug
	    log_c++;
	    elem.value += log_c + ': ' + text + "\n";
	  }
    
    function pad_color(n)
    {
        switch(n)
        {
          case 1: return 'blue';
            break;
	        case 2: return 'maroon';
            break;
	        case 3: return 'darkorange';
            break;
          default: return 'black';
        }
    }
	  
    // ajax poster
    var req = false;
    function ajax_post(data, type)
    {
       req = false;
       if (window.XMLHttpRequest)
       {//Mozilla, Safari
          req = new XMLHttpRequest();
          if (req.overrideMimeType) req.overrideMimeType("text/html");
       }
       else if (window.ActiveXObject)
       {//IE
          try { req = new ActiveXObject("Msxml2.XMLHTTP"); }
          catch (e)
          {
            try { req = new ActiveXObject("Microsoft.XMLHTTP"); }
            catch (e) {}
          }
       }
       if (!req) return false;
       //if (!req) alert('An error occurred during the XMLHttpRequest!');

       var parameters = '';
       var amp = '';
       for(var i in data)
       {
         parameters += amp + i + '=' + escape(encodeURI( data[i] ));
         if(amp == '') amp = '&';
       }

       req.onreadystatechange = ajax_response;
       req.open("POST", "ajax.php?type=" + type, true);
       req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
       //req.setRequestHeader("Content-type", "text/plain;charset=utf-8");
       req.setRequestHeader("Content-length", parameters.length);
       req.setRequestHeader("Connection", "close");
       req.send(parameters);

       return true;
    }

    function ajax_response()
    {
      if (req.readyState == 4)
      {
        if (req.status == 200)
        {
          var type = req.responseText.substr(0, 2);
          var data = req.responseText.substr(2);

          if(type == '00') alert(" - Debug:\n"+req.responseText);// debug

          // response router
          if(type == '21')
          {
            var n = data.split(':')[0];
            var d = data.split(':')[1];
            data = data.substr((n.length + d.length + 2));//), 1024);// max karakter // ~1 mega
            //alert(data);
            if(pads[n] != '') _id(pads[n]).value = data;
            pads[n].disabled = false;
            
            //setTimeout('refresh_pad('+n+');', 1000);
            
            _id('text_pad'+n+'_time').innerHTML = '['+d.split(' ')[1]+']';
            _id('text_pad'+n+'_time').style.color = pad_color(n * 1);
            _id('text_pad'+n+'_time').setAttribute('title', d.split(' ')[0]);
            
            setTimeout('refresh_pad('+n+');', 1000);
          }
        }
        else return false;
        //else alert('An error occurred during the ajax request!');
      }
    }
    
    function _id(id) {
      if(!id) return false;
      var id = document.getElementById(id);
      if(!id) return false;
      //else return true;
      else return id;
    }
	  
	-->
	</script>

	<style type="text/css">
	<!--
	  * {
	    border: 0;
	    padding: 0;
	    margin: 0 auto;
	    background-color: transparent;
	    text-align: center;
	  }
	  
	  html, body {
	    background-color: #040404;
	  }
	  
	  #container {
	    width: 600px;
	    height: 540px;
	  }
	  
	  .text_pads {
	    display: block;
	    width: 570px;
	    height: 180px;
	    border-top: 5px solid black;
	    border-bottom: 5px solid black;
	    overflow: auto;
	    text-align: left;
	    background-color: #fdfdee;
	    padding: 5px 2px;
	    font-family: Verdana, Helvetica, Arial, sans-serif;
	    font-size: 13px;
	    font-weight: normal;
	    line-height: 15px;
	    
	  }
    
    #status {
      font-size: 13px;
      font-weight: bold;
      font-family: Arial, sans-serif;
      color: lightgray;
      background-color: #0f0f0f;
    }
    
    .status {
      cursor: pointer;
      background-color: #2a2a2a;
    }

	-->
	</style>

	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<meta name="revisit-after" content="2 days" />
	<meta name="googlebot" content="index, follow, archive" />
	<meta name="robots" content="all, index, follow" />
	<meta name="msnbot" content="all, index, follow" />

	<meta name="page-type" content="" />
	<meta name="subject" content="" />

	<meta name="rating" content="general" />

	<meta name="Language" content="en" />
	<meta name="resource-type" content="document" />
	<meta name="Distribution" content="Global" />
	<meta name="Author" content="Vector Akashi" />

	<meta name="Copyright" content="www.nextserver.hu" />
	
  <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
  <link href="favicon.ico" rel="icon" type="image/x-icon" />
</head>
<body id="body">
<div id="container">
<textarea id="text_pad1" class="text_pads" rows="12" cols="55" onfocus="pad_focus(1);" onkeyup="pad_keyup(1);" onblur="pad_blur(1);"></textarea>
<textarea id="text_pad2" class="text_pads" rows="12" cols="55" onfocus="pad_focus(2);" onkeyup="pad_keyup(2);" onblur="pad_blur(2);"></textarea>
<textarea id="text_pad3" class="text_pads" rows="12" cols="55" onfocus="pad_focus(3);" onkeyup="pad_keyup(3);" onblur="pad_blur(3);"></textarea>
<span id="status"><!-- onclick="refresh_pad(1, true);"-->
 &nbsp; <span class="status" onclick="mp_srv_refresh(true);" id="text_pad1_time">[]</span> &nbsp; 
<span class="status" onclick="mp_srv_refresh(true);" id="text_pad2_time">[]</span> &nbsp; 
<span class="status" onclick="mp_srv_refresh(true);" id="text_pad3_time">[]</span> &nbsp; 
</span>
</div>
</body>
</html>