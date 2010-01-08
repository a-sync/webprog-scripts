// @name        Sliding Toggler
// @namespace   http://www.onethreestudio.com/ (old) // http://www.nextserver.hu/ - Hidden dev.
// @version     1.06
// @author      Vector
// @description Toggle element display with a sliding effect. (For elements without a Height setting!) (The default closed elements shuld have: style="display: none" in their tag!)

function toggle(id, tag)
{
  var e = _(id, tag); //get the element

  if (e.style.display == "none") //if element is closed
  {
    e.style.overflow = "hidden"; //hide overflow
    e.style.display = ""; //open element
    var oh = e.offsetHeight; //get elements (offset)Height

    slide(id, tag, oh, "out"); //run slide function
  }
  else
  {
    e.style.overflow = "hidden"; //hide overflow
    var oh = e.offsetHeight; //get elements (offset)Height

    slide(id, tag, oh, "in"); //run slide function
  }
}

function slide(id, tag, oh, t, p)
{
  var e = _(id, tag); //get the element

  if (t == "out") //if we are sliding it out
  {
    var fps = 20; //frames per second
    var duration = 350; //duration of slide in millisec
    var f = Math.round(fps * (duration / 1000)); //frames number
    var d = duration / f; //delay time
    //var f = 20; //frames number
    //var d = 10; //delay time
    if (!p) var p = 0; //default period number

    if (p < f) //if the current frame is not the last
    {
      if(oh <= 0 || isNaN(oh))
      {
        oh = "auto";
        e.style.height = oh; //set the elements height
      }
      else
      {
        e.style.height = Math.round((oh / f) * p) + "px"; //set the elements height
      }
      
      p++;//+1 to period number
      setTimeout("slide('" + id + "', '" + tag + "', '" + oh + "', 'out', '" + p + "')", d); //run slide function after delay
    }
    //if (p == f) e.style.height = oh + "px"; //if this is the last frame, set the default height
    if (p == f) e.style.height = "auto"; //if this is the last frame, set the default height
  }
  else if (t == "in") //if we are sliding it in
  {
    var fps = 30; //frames per second
    var duration = 300; //duration of slide in millisec
    var f = Math.round(fps * (duration / 1000)); //frames number
    var d = duration / f; //delay time
    //var f = 20; //frames number
    //var d = 10; //delay time
    if (!p) var p = 0; //default period number

    if (p < f) //if the current frame is not the last
    {
      e.style.height = (oh - Math.round((oh / f) * p)) + "px"; //set the elements height
      p++;//+1 to period number
      setTimeout("slide('" + id + "', '" + tag + "', '" + oh + "', 'in', '" + p + "')", d); //run slide function after delay
    }
    if (p == f) //if this is the last frame
    {
      e.style.display = "none"; //close the element
      //e.style.height = oh + "px"; //set the default height
      e.style.height = "auto"; //set the default height
    }
  }
}