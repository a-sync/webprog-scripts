// ==UserScript==
// @name        index galéria kép link
// @namespace   hokindex
// @include     http://galeria.index.hu/*
// @include     http://galeria.velvet.hu/*
// @include     http://galeria.sportgeza.hu/*
// @include     http://galeria.divany.hu/*
// @version     2.0.0
// @author      Vector
// ==/UserScript==

var kep = document.getElementById('nagykep');
//console.log('dbg: kep: '+kep);
if(!kep || kep.getAttribute('src') == '')
{
  // #szoveg img (kep.index.hu/)
}
else
{
  var linkek = document.createElement('DIV');
  linkek.style.display = 'none';
  linkek.setAttribute('style', 'color:gray;font-size:15px;line-height:22px;background-color:white;border-radius:5px;position:fixed;right:70px;top:60px;padding:10px;box-shadow:0 0 5px 0 darkgrey;z-index:9001;');
  
  var k = kep.getAttribute('src');
  //console.log('dbg:  k: '+k);
  var ka = k.split('_');
  var k_t = ka.pop();
  
  var ko = ka.join('_')+'_o.'+k_t.split('.')[1];
  var kx = ka.join('_')+'_x.'+k_t.split('.')[1];
  /*var kt = ka.join('_')+'_t.'+k_t.split('.')[1];
  var kq = ka.join('_')+'_q.'+k_t.split('.')[1];
  var kl = ka.join('_')+'_l.'+k_t.split('.')[1];*/
  
  linkek.innerHTML = '<div style="display: none"><a href="'+ko+'">o.'+k_t.split('.')[1]+'</a>'
                   + '<img id="igkl_ko" src="'+ko+'" style="display:none" /></div>';
  
  var oimg = ko.split('/').pop().split('_');
  var koo = 'http://kep.index.hu/1/0/'+oimg[0].substring(0,3)+'/'+oimg[0].substring(0,4)+'/'+oimg[0].substring(0,5)+'/'+oimg.join('_');

  linkek.innerHTML += '<div style="display: none"><a href="'+koo+'">oo.'+k_t.split('.')[1]+'</a>'
                   + '<img id="igkl_koo" src="'+koo+'" style="display:none" /></div>';
  
  if(k_t.split('.')[0] == 'x')
  {
    linkek.innerHTML += '<a title="'+kep.width+'x'+kep.height+'" id="igkl_k" href="'+k+'">'+k_t+'</a>';
  }
  else
  {
    linkek.innerHTML += '<div style="display: none"><a href="'+kx+'">x.'+k_t.split('.')[1]+'</a>'
                     + '<img id="igkl_kx" src="'+kx+'" style="display:none" /></div>';
  }
  
  /*if(k_t.split('.')[0] == 't')
  {
    linkek.innerHTML += '<a title="'+kep.width+'x'+kep.height+'" id="igkl_k" href="'+k+'">'+k_t+'</a>';
  }
  else
  {
    linkek.innerHTML += '<div style="display: none"><a href="'+kt+'">t.'+k_t.split('.')[1]+'</a>'
                     + '<img id="igkl_kt" src="'+kt+'" style="display:none" /></div>';
  }
  
  if(k_t.split('.')[0] == 'q')
  {
    linkek.innerHTML += '<a title="'+kep.width+'x'+kep.height+'" id="igkl_k" href="'+k+'">'+k_t+'</a>';
  }
  else
  {
    linkek.innerHTML += '<div style="display: none"><a href="'+kq+'">q.'+k_t.split('.')[1]+'</a>'
                     + '<img id="igkl_kq" src="'+kq+'" style="display:none" /></div>';
  }
  
  if(k_t.split('.')[0] != 'l')
  {
    linkek.innerHTML += '<div style="display: none"><a href="'+kl+'">l.'+k_t.split('.')[1]+'</a>'
                     + '<img id="igkl_kl" src="'+kl+'" style="display:none" /></div>';
  }*/
  
  //if(k_t.split('.')[0] != 'x' && k_t.split('.')[0] != 'o' && k_t.split('.')[0] != 't' && k_t.split('.')[0] != 'q')
  if(k_t.split('.')[0] != 'x' && k_t.split('.')[0] != 'o')
  {
    linkek.innerHTML += '<a title="'+kep.width+'x'+kep.height+'" id="igkl_k" href="'+k+'">'+k_t+'</a>';
  }
  
  document.body.appendChild(linkek);
  
  eventon('mouseover', function(){ this.style.color = 'red'; }, document.getElementById('igkl_k'));
  eventon('mouseout', function(){ this.style.color = 'gray'; }, document.getElementById('igkl_k'));
  
  eventon('load', function(){ this.parentNode.style.display = 'block'; this.previousSibling.setAttribute('title', this.width+'x'+this.height); }, document.getElementById('igkl_ko'));
  eventon('mouseover', function(){ this.style.color = 'red'; }, document.getElementById('igkl_ko').previousSibling);
  eventon('mouseout', function(){ this.style.color = 'gray'; }, document.getElementById('igkl_ko').previousSibling);
  
  eventon('load', function(){ this.parentNode.style.display = 'block'; this.previousSibling.setAttribute('title', this.width+'x'+this.height); }, document.getElementById('igkl_koo'));
  eventon('mouseover', function(){ this.style.color = 'red'; }, document.getElementById('igkl_koo').previousSibling);
  eventon('mouseout', function(){ this.style.color = 'gray'; }, document.getElementById('igkl_koo').previousSibling);
  
  if(k_t.split('.')[0] != 'x')
  {
    eventon('load', function(){ this.parentNode.style.display = 'block'; this.previousSibling.setAttribute('title', this.width+'x'+this.height); }, document.getElementById('igkl_kx'));
    eventon('mouseover', function(){ this.style.color = 'red'; }, document.getElementById('igkl_kx').previousSibling);
    eventon('mouseout', function(){ this.style.color = 'gray'; }, document.getElementById('igkl_kx').previousSibling);
  }
  /*if(k_t.split('.')[0] != 't')
  {
    eventon('load', function(){ this.parentNode.style.display = 'block'; this.previousSibling.setAttribute('title', this.width+'x'+this.height); }, document.getElementById('igkl_kt'));
    eventon('mouseover', function(){ this.style.color = 'red'; }, document.getElementById('igkl_kt').previousSibling);
    eventon('mouseout', function(){ this.style.color = 'gray'; }, document.getElementById('igkl_kt').previousSibling);
  }
  if(k_t.split('.')[0] != 'q')
  {
    eventon('load', function(){ this.parentNode.style.display = 'block'; this.previousSibling.setAttribute('title', this.width+'x'+this.height); }, document.getElementById('igkl_kq'));
    eventon('mouseover', function(){ this.style.color = 'red'; }, document.getElementById('igkl_kq').previousSibling);
    eventon('mouseout', function(){ this.style.color = 'gray'; }, document.getElementById('igkl_kq').previousSibling);
  }
  if(k_t.split('.')[0] != 'l')
  {
    eventon('load', function(){ this.parentNode.style.display = 'block'; this.previousSibling.setAttribute('title', this.width+'x'+this.height); }, document.getElementById('igkl_kl'));
    eventon('mouseover', function(){ this.style.color = 'red'; }, document.getElementById('igkl_kl').previousSibling);
    eventon('mouseout', function(){ this.style.color = 'gray'; }, document.getElementById('igkl_kl').previousSibling);
  }*/
  
  linkek.style.display = 'block';
}

function eventon(type, func, elem)
{
  if(!elem) elem = window;
  if(elem.attachEvent) elem.attachEvent('on'+type, func);
  else elem.addEventListener(type, func, false);
}

