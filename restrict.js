var isCtrl = false;
document.onkeyup=function(e)
{
    if(e.which == 17)
        isCtrl=false;
}
document.onkeydown = function(e) {
    if(event.keyCode == 123) {
    alert('For Security Purposes, This function is disabled!');
    return false;
    }
    if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
    alert('For Security Purposes, This function is disabled!');
    return false;
    }
    if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
    alert('For Security Purposes, This function is disabled!');
    return false;
    }
    if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
    alert('For Security Purposes, This function is disabled!');
    return false;
    }
    }


document.onkeydown2=function(e)
{
    if(e.which == 123)
        isCtrl=true;
    if (((e.which == 85) || (e.which == 65) || (e.which == 88) || (e.which == 67) || (e.which == 86) || (e.which == 2) || (e.which == 3) || (e.which == 123) || (e.which == 83)) && isCtrl == true)
    {
        alert('For Security Purposes, This function is disabled!');
        return false;
    }
}

// right click code
var isNS = (navigator.appName == "Netscape") ? 1 : 0;
if(navigator.appName == "Netscape") document.captureEvents(Event.MOUSEDOWN||Event.MOUSEUP);
function mischandler(){
    alert('For Security Purposes, Right Click is disabled!');
    return false;
}
function mousehandler(e){
    var myevent = (isNS) ? e : event;
    var eventbutton = (isNS) ? myevent.which : myevent.button;
    if((eventbutton==2)||(eventbutton==3)) return false;
}
document.oncontextmenu = mischandler;
document.onmousedown = mousehandler;
document.onmouseup = mousehandler;
function killCopy(e){
    return false
}
function reEnable(){
    return true
}
document.onselectstart=new Function ("return false")
if (window.sidebar){
    document.onmousedown=killCopy
    document.onclick=reEnable
}