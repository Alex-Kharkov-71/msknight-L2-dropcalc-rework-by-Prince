
/*
	This script is (c) 2000 Ivanopulo http://www.damn.to
	Please leave this message intact if you use the script.
*/
/*
        Adjusted for NN 6, Level 5 browser 
        by Michelle Knight 2002 - http://www.msknight.com
*/

browserName = navigator.appName;
browserVer = parseInt(navigator.appVersion);
bNetscape4 = (browserName == "Netscape" && browserVer >= "4" && browserVer < "5");
bNetscape5 = (browserName == "Netscape" && browserVer >= "5");
bExplorer4 = (browserName == "Microsoft Internet Explorer" && browserVer == "4");
bExplorer5 = (browserName == "Microsoft Internet Explorer" && browserVer >= "5");
bOpera = (browserName == "Opera");
bdivMenuTop = 0;
bdivLinkTop = 0;
bscale = 8;
bOldText = ""
/*
  Code to stop dotted lines around link elements in Explorer.
*/ 

function ExplorerFix() {
     for (a in document.links) document.links[a].onfocus = document.links[a].blur;
     }
     if (document.all) {
      document.onmousedown = ExplorerFix;
     }

/*
  Window pop up code - from Dax Assist.
*/

var enlarger_w = null;
function popit (pic,swidth,sheight)
{
 url=pic;
 if(enlarger_w==null || enlarger_w.closed)
{

enlarger_w = window.open(url,'enlarger','location=no,toolbar=no,directories=no,menubar=no,resizable=yes,scrollbars=no,status=no,width='+swidth+',height='+sheight);
}
else{
//viewerpop.close;
enlarger_w.close ();
enlarger_w = 
window.open(url,'enlarger','location=no,toolbar=no,directories=no,menubar=no,resizable=yes,scrollbars=no,status=no,width='+swidth+',height='+sheight);

enlarger_w.resizeTo(swidth,sheight);
enlarger_w.focus();
}
if ( browserName == "Netscape"&& browserVer >= 3 )
  enlarger_w.focus();
enlarger_w.focus();
}

/*
  Window pop up code - from Dax Assist.
*/

var enlarger_w = null;
function popot (pic,swidth,sheight)
{
 url=pic;
 if(enlarger_w==null || enlarger_w.closed)
{

enlarger_w = window.open(url,'enlarger','location=no,toolbar=no,directories=no,menubar=no,resizable=yes,scrollbars=yes,status=no,width='+swidth+',height='+sheight);
}
else{
//viewerpop.close;
enlarger_w.close ();
enlarger_w = 
window.open(url,'enlarger','location=no,toolbar=no,directories=no,menubar=no,resizable=yes,scrollbars=no,status=no,width='+swidth+',height='+sheight);

enlarger_w.resizeTo(swidth,sheight);
enlarger_w.focus();
}
if ( browserName == "Netscape"&& browserVer >= 3 )
  enlarger_w.focus();
enlarger_w.focus();
}
