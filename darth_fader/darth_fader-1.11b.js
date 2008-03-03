// @name        Darth Fader Script
// @namespace   http://www.onethreestudio.com/
// @version     1.11b
// @author      Vector Akashi
// @description Based on Adam Michela's The Fade Anything Technique (http://www.axentric.com/aside/fat/)


// #############################################
// ## USING CLASSES TO FADE MULTIPLE ELEMENTS ##
// #############################################
// fade_all(tagsname);
// tagsname:      (default: '*') the tagname of the element (if you want to locate the element by it's tagname)
//
// ex.: fade_all();
// ex.: fade_all('p');
// ex.: fade_all('div');
//
// dfader-from-to-transparent-fps-duration
// from:          (default: ff8c00) starting color (color name: 'Darkorange' | short hexadecimal: 'f80' | default hexadecimal: 'ff8c00' | the elements default color: null)
// to:            (default: ffffff) ending color (color name: 'White' | short hexadecimal: 'fff' | default hexadecimal: 'ffffff' | the elements default color: null)
// transparent:   (default: 0) at the end of the fade, set the color to transparent (no: 0 | yes: 1)
// fps:           (default: 30) frames per second (decimal number: 30)
// duration:      (default: 3000) the duration of the animation in milisecond (decimal number: 3000)
//
// ex.: <h1 class="dfader">This will fade with the default settings</h1>
// ex.: <p class="dfader-ffffaa-navy-0-30-3000">This will fade with custom colors</p>
// ex.: <p class="dfader-ffa-navy">This is the same as above</p>
// ex.: <div class="myclass dfader-green-black-1-50-5000">You can use other classes as well with dfader...</div>
// ex.: <h2 class="header2 dfader-ff5-red-0-60 center">You can use other classes as well with dfader...</h2>
// ### end ###

// #######################
// ## FADING ONE ELMENT ##
// #######################
// fade(id, from, to, transparent, fps, duration, tagsname);
// id:            element ID (or tagname ID if you want to locate the element by it's tagname)
// from:          (default: #ff8c00) starting color (color name: 'Darkorange' | short hexadecimal: '#f80' | default hexadecimal: '#ff8c00' | rgb: 'rgb(255,140,0)' | the elements default color: null)
// to:            (default: #ffffff) ending color (color name: 'White' | short hexadecimal: '#fff' | default hexadecimal: '#ffffff' | rgb: 'rgb(255,255,255)' | the elements default color: null)
// transparent:   (default: 0) at the end of the fade, set the color to transparent (no: 0 | yes: 1)
// fps:           (default: 30) frames per second (decimal number: 30)
// duration:      (default: 3000) the duration of the animation in milisecond (decimal number: 3000)
// tagsname:      (default: null) the tagname of the element (if you want to locate the element by it's tagname)
//
// ex.: fade('fadeid');
// ex.: fade('fade', null, '#af3333', 0, 30, 3000);
// ex.: fade(0, 'white', '#f00', 1, 20, 1000, 'p');
// ex.: fade('divid', 'orange', 'white');
// ### end ###


/* fade_all */
function fade_all (tagsname)
{
  if (!tagsname) var tagsname = "*";

  var a = document.getElementsByTagName(tagsname);

  var i;
  for (i in a)
  {
    var o = a[i];
    var r = /dfader-?(\w*)?-?(\w*)?-?(\w*)?-?(\w*)?-?(\w*)?/.exec(o.className);

    if (r)
    {
      fade(i, r[1], r[2], r[3], r[4], r[5], tagsname);
      //alert(i + ", " + r[1] + ", " + r[2] + ", " + r[3] + ", " + r[4] + ", " + r[5] + ", " + tagsname);//debug
    }
  }
}


/* fade */
function fade (id, from, to, transparent, fps, duration, tagsname)
{
  if (!tagsname || tagsname == 'null') tagsname = null;

  if (from == undefined) from = check_color(from, 0);
  if (from == null || from == 'null') from = check_color(get_bgcolor(id, tagsname), 0);
  else from = check_color(from, 0);

  if (to == undefined) to = check_color(to, 1);
  if (!to || to == null || to == 'null') to = check_color(get_bgcolor(id, tagsname), 1);
  else to = check_color(to, 1);

  if (!transparent) transparent = 0;
  if (!fps) fps = 30;
  if (!duration) duration = 3000;

  var frames = Math.round(fps * (duration / 1000));
  var interval = duration / frames;
  var delay = interval;
  var frame = 0;

  var rf = parseInt(from.substr(1, 2), 16);
  var gf = parseInt(from.substr(3, 2), 16);
  var bf = parseInt(from.substr(5, 2), 16);
  var rt = parseInt(to.substr(1, 2), 16);
  var gt = parseInt(to.substr(3, 2), 16);
  var bt = parseInt(to.substr(5, 2), 16);

  var r, g, b, h;
  while (frame < frames)
	{
    r = Math.floor(rf * ((frames - frame) / frames) + rt * (frame / frames));
    g = Math.floor(gf * ((frames - frame) / frames) + gt * (frame / frames));
    b = Math.floor(bf * ((frames - frame) / frames) + bt * (frame / frames));
    h = make_hex(r,g,b);

    if (tagsname == null) setTimeout("set_bgcolor('" + id + "', '" + h + "')", delay);
    else setTimeout("set_bgcolor(" + id + ", '" + h + "', '" + tagsname + "')", delay);

    frame++;
    var delay = interval * frame; 
  }

  if (tagsname == null)
  {
    if (transparent == 0) setTimeout("set_bgcolor('" + id + "', '" + to + "')", delay);
    else setTimeout("set_bgcolor('" + id + "', 'transparent')", delay);
  }
  else
  {
    if (transparent == 0) setTimeout("set_bgcolor(" + id + ", '" + to + "', '" + tagsname + "')", delay);
    else setTimeout("set_bgcolor(" + id + ", 'transparent', '" + tagsname + "')", delay);
  }
}


/* make_hex */
function make_hex (r, g, b)
{
  var r = r.toString(16); if (r.length == 1) r = '0' + r;
  var g = g.toString(16); if (g.length == 1) g = '0' + g;
  var b = b.toString(16); if (b.length == 1) b = '0' + b;
  return "#" + r + g + b;
}


/* check_color */
function check_color (c, d)
{
  if (d == 1) var d = "#ffffff"; //default TO color
  else var d = "#ff8c00"; //default FROM color

  if (c == undefined || c == "" || c == "transparent") var c = d;
  else
  {
    var rgb = c.match(/rgb\s*\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)/);
    if (rgb) var c = make_hex(parseInt(rgb[1]), parseInt(rgb[2]), parseInt(rgb[3]));

    if (c.charAt(0) != "#")
    {
      var n = colorName_to_Hex(c);
      if (n == undefined) var c = "#" + c;
      else var c = n;
    }

    if (c.length == 4) var c = "#" + c.charAt(1) + c.charAt(1) + c.charAt(2) + c.charAt(2) + c.charAt(3) + c.charAt(3);

    //var m = c.match(/^\#[A-Fa-f0-9]{6}$/);//only valid color string
    var m = c.match(/\#[A-Fa-f0-9]{6}/);//lets try to fix the color string
    if (m) var c = m[0];
    else var c = d;
  }
  return c;
}


/* colorName_to_Hex_colors */
var colorName_to_Hex_colors = { aliceblue: '#f0f8ff', antiquewhite: '#faebd7', aqua: '#00ffff', aquamarine: '#7fffd4', azure: '#f0ffff', beige: '#f5f5dc', bisque: '#ffe4c4', black: '#000000', blanchedalmond: '#ffebcd', blue: '#0000ff', blueviolet: '#8a2be2', brown: '#a52a2a', burlywood: '#deb887', cadetblue: '#5f9ea0', chartreuse: '#7fff00', chocolate: '#d2691e', coral: '#ff7f50', cornflowerblue: '#6495ed', cornsilk: '#fff8dc', crimson: '#dc143c', cyan: '#00ffff', darkblue: '#00008b', darkcyan: '#008b8b', darkgoldenrod: '#b8860b', darkgray: '#a9a9a9', darkgrey: '#a9a9a9', darkgreen: '#006400', darkkhaki: '#bdb76b', darkmagenta: '#8b008b', darkolivegreen: '#556b2f', darkorange: '#ff8c00', darkorchid: '#9932cc', darkred: '#8b0000', darksalmon: '#e9967a', darkseagreen: '#8fbc8f', darkslateblue: '#483d8b', darkslategray: '#2f4f4f', darkslategrey: '#2f4f4f', darkturquoise: '#00ced1', darkviolet: '#9400d3', deeppink: '#ff1493', deepskyblue: '#00bfff', dimgray: '#696969', dimgrey: '#696969', dodgerblue: '#1e90ff', firebrick: '#b22222', floralwhite: '#fffaf0', forestgreen: '#228b22', fuchsia: '#ff00ff', gainsboro: '#dcdcdc', ghostwhite: '#f8f8ff', gold: '#ffd700', goldenrod: '#daa520', gray: '#808080', grey: '#808080', green: '#008000', greenyellow: '#adff2f', honeydew: '#f0fff0', hotpink: '#ff69b4', indianred: '#cd5c5c', indigo: '#4b0082', ivory: '#fffff0', khaki: '#f0e68c', lavender: '#e6e6fa', lavenderblush: '#fff0f5', lawngreen: '#7cfc00', lemonchiffon: '#fffacd', lightblue: '#add8e6', lightcoral: '#f08080', lightcyan: '#e0ffff', lightgoldenrodyellow: '#fafad2', lightgray: '#d3d3d3', lightgrey: '#d3d3d3', lightgreen: '#90ee90', lightpink: '#ffb6c1', lightsalmon: '#ffa07a', lightseagreen: '#20b2aa', lightskyblue: '#87cefa', lightslategray: '#778899', lightslategrey: '#778899', lightsteelblue: '#b0c4de', lightyellow: '#ffffe0', lime: '#00ff00', limegreen: '#32cd32', linen: '#faf0e6', magenta: '#ff00ff', maroon: '#800000', mediumaquamarine: '#66cdaa', mediumblue: '#0000cd', mediumorchid: '#ba55d3', mediumpurple: '#9370d8', mediumseagreen: '#3cb371', mediumslateblue: '#7b68ee', mediumspringgreen: '#00fa9a', mediumturquoise: '#48d1cc', mediumvioletred: '#c71585', midnightblue: '#191970', mintcream: '#f5fffa', mistyrose: '#ffe4e1', moccasin: '#ffe4b5', navajowhite: '#ffdead', navy: '#000080', oldlace: '#fdf5e6', olive: '#808000', olivedrab: '#6b8e23', orange: '#ffa500', orangered: '#ff4500', orchid: '#da70d6', palegoldenrod: '#eee8aa', palegreen: '#98fb98', paleturquoise: '#afeeee', palevioletred: '#d87093', papayawhip: '#ffefd5', peachpuff: '#ffdab9', peru: '#cd853f', pink: '#ffc0cb', plum: '#dda0dd', powderblue: '#b0e0e6', purple: '#800080', red: '#ff0000', rosybrown: '#bc8f8f', royalblue: '#4169e1', saddlebrown: '#8b4513', salmon: '#fa8072', sandybrown: '#f4a460', seagreen: '#2e8b57', seashell: '#fff5ee', sienna: '#a0522d', silver: '#c0c0c0', skyblue: '#87ceeb', slateblue: '#6a5acd', slategray: '#708090', slategrey: '#708090', snow: '#fffafa', springgreen: '#00ff7f', steelblue: '#4682b4', tan: '#d2b48c', teal: '#008080', thistle: '#d8bfd8', tomato: '#ff6347', turquoise: '#40e0d0', violet: '#ee82ee', wheat: '#f5deb3', white: '#ffffff', whitesmoke: '#f5f5f5', yellow: '#ffff00', yellowgreen: '#9acd32' };

function colorName_to_Hex (color_string)
{
  return colorName_to_Hex_colors[color_string.toLowerCase()];
}


/* get_bgcolor */
function get_bgcolor (id, tagsname)
{
  if (!tagsname) var o = document.getElementById(id);
  else var o = document.getElementsByTagName(tagsname)[id];

  while(o)
  {
    var c;

    if (window.getComputedStyle) c = window.getComputedStyle(o,null).getPropertyValue("background-color");
    if (o.currentStyle) c = o.currentStyle.backgroundColor;
    if ((c != "" && c != "transparent") || o.tagName == "BODY") { break; }

    o = o.parentNode;
  }
  return c;
}


/* set_bgcolor */
function set_bgcolor (id, c, tagsname)
{
  if (!tagsname) var o = document.getElementById(id);
  else var o = document.getElementsByTagName(tagsname)[id];

  o.style.backgroundColor = c;
}


/* "Mennyi eltemetetlen hülyeségnek vagy a hordozója,
    Mennyi okot találtál már aminek nem volt okozója..." */