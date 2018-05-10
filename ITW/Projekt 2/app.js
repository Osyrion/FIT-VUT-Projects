/* SLIDESHOW */
$("#slideshow > div:gt(0)").hide();

setInterval(function() { 
  $('#slideshow > div:first')
    .fadeOut(1000)
    .next()
    .fadeIn(1000)
    .end()
    .appendTo('#slideshow');
},  3000);


/* COOKIES */
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function togglePanel(cname)
{
  var val = getCookie(cname);
  var element = document.getElementById(cname);
  var openButton = document.getElementById("open");
  var closeButton = document.getElementById("close");
  
  if (val == 1)
  {
    element.style.display = "none";
    openButton.style.display = "block";
    closeButton.style.display = "none";
    setCookie(cname, 2, 7);
  }
  else if (val == -1)
  {
    element.style.display="none";
    openButton.style.display = "block";
    closeButton.style.display = "none";
    setCookie(cname, 2, 7);
  }
  else
  {
    element.style.display="block";
    openButton.style.display = "none";
    closeButton.style.display = "block";
    setCookie(cname, 1, 7);
  }
}

function setDisplayPanel()
{
   var val = getCookie("show");
   var element = document.getElementById("show");
   var openButton = document.getElementById("open");
   var closeButton = document.getElementById("close");

   if (val == -1)
   {
      setCookie("show", 2, 7);
      element.style.display="none";
      openButton.style.display = "block";
      closeButton.style.display = "none";
   }
   else
   {
     if (val == 1)
     {
       element.style.display="block";
       openButton.style.display = "none";
       closeButton.style.display = "block";
     }
     else
     {
       element.style.display="none";
       openButton.style.display = "block";
       closeButton.style.display = "none";
     }
   }
}