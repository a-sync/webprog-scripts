// ==UserScript==
// @name        IMDB Full Size Cast Images
// @namespace   imdb
// @include     http://www.imdb.com/title/tt*/
// @include     http://www.imdb.com/title/tt*/*?*
// @include     http://www.imdb.com/title/tt*/fullcredits*
// @include     http://www.imdb.com/media/rm*/nm*
// @include     http://www.imdb.com/media/rm*/tt*
// @version     1.0.0
// ==/UserScript==
var maxwidth = 300;
var thumbresizew = false;
var fullsizethumbsrc = false;

function imdb_fsci()
{
  var pimg = document.getElementById('primary-img');
  var cast = document.getElementById('titleCast');
  if(!cast) cast = document.getElementById('tn15content');
  
  if(!pimg || cast)
  {
    var imgs = cast.getElementsByTagName('img');
    
    for(var i = 0; i < imgs.length; i++)
    {
      if(imgs[i].hasAttribute('width') && (imgs[i].getAttribute('width') == 23 || imgs[i].getAttribute('width') == 32))
      {
        if(imgs[i].hasAttribute('loadlate'))
        {
          imgs[i].src = imgs[i].getAttribute('loadlate');
          imgs[i].removeAttribute('loadlate');
          imgs[i].removeAttribute('class');
        }
        
        if(imgs[i].src.indexOf('http://ia.media-imdb.com/images/M/') == 0)
        {
          if(!isNaN(thumbresizew) && thumbresizew != false)
          {
            var id = imgs[i].src.substr(34).split('.')[0];
            imgs[i].src = 'http://ia.media-imdb.com/images/M/'+id+'._SX'+thumbresizew+'_.jpg';
            imgs[i].setAttribute('width', thumbresizew);
            imgs[i].removeAttribute('height');
          }
          
          eventon('mouseover', function()
          {
            if(this.nextSibling == null)
            {
              var imgid = this.src.substr(34).split('.')[0];
              
              var newimg = new Image()
              newimg.src = 'http://ia.media-imdb.com/images/M/'+imgid+'._SX'+maxwidth+'_.jpg';
              
              if(fullsizethumbsrc) this.src = 'http://ia.media-imdb.com/images/M/'+imgid+'.jpg';
              
              newimg.style.display = '';
              newimg.style.position = 'absolute';
              newimg.style.zIndex = 123;
              //newimg.style.maxWidth = maxwidth+'px';
              newimg.style.marginLeft = '4px';
              newimg.style.boxShadow = '1px 1px 8px 0px black';
              newimg.style.borderRadius = '4px';
              newimg.style.backgroundColor = 'white';
              newimg.style.bottom = '0';
              
              this.parentNode.parentNode.style.position = 'relative';
              this.parentNode.insertBefore(newimg, this.nextSibling);
            }
            else this.nextSibling.style.display = '';

          }, imgs[i]);
          eventon('mouseout', function()
          {
            if(this.nextSibling != null) this.nextSibling.style.display = 'none';
          }, imgs[i]);
        }
      }
    }
  }
  else
  {
    if(pimg.src.indexOf('http://ia.media-imdb.com/images/M/') == 0)
    {
      var imgid = pimg.src.substr(34).split('.')[0];
      pimg.src = 'http://ia.media-imdb.com/images/M/'+imgid+'.jpg';
      
      if(history.length == 1 && document.referrer != '') document.location = pimg.src;
      
      pimg.style.position = 'relative';
      pimg.style.zIndex = 123;
      pimg.style.maxWidth = '640px';
      pimg.style.cursor = 'pointer';
      eventon('click', function() {
        document.location = this.src;
      },pimg);
    }
  }
}

function eventon(type, func, elem)
{
  if(!elem) elem = window;
  if(elem.attachEvent) elem.attachEvent('on'+type, func);
  else elem.addEventListener(type, func, false);
}

//setTimeout(imdb_fsci, 0);
//eventon('load', imdb_fsci);
imdb_fsci();