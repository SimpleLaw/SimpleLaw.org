function law(item){//links to the appropriate state law site
	window.location = "http://www.simplelaw.org/laws.php?stateName="+item.id;
}
var request=null;//used to make sure that api is only called for states that user stops to look at
var lawsDefault=null;//used to reset the laws tab after user is done with it
window.onload=function(){
	lawsDefault=document.getElementById("laws").innerHTML;
}
function beginHover(item){//hoverover: make the hovered state to blue, make the popup visible, sets the recent laws, stops the timer that will make popup disappear again
	window.clearTimeout(request);
	item.style.fill="#009FCF";
	popup.style.visibility="visible";
	document.getElementById("laws").innerHTML=lawsDefault;
	document.getElementById("laws").style.visibility="hidden";
	document.getElementById("stateName").innerHTML="<h3>"+item.id+"</h3>";
	request = window.setTimeout(function(){
		document.getElementById("loading").style="visibility:visible;";
		window.setTimeout(setRecentLaws,500,item);
	},200,item);
}
function endHover(item){//end hoverover:reset state color, if the user has left the state, then the state name will disappear after 5 seconds, resets laws, ends the call to the api
	item.style.fill="#BFBFBF";
}
function setRecentLaws(item){//get laws for state from api
	var bill=getLaws(item.id);
	document.getElementById("date").innerHTML= item.id;
	document.getElementById("billTitle").innerHTML=bill[0];
	document.getElementById("billDescription").innerHTML=bill[1];
	document.getElementById("billLink").href=bill[2];
	document.getElementById("loading").style="visibility:hidden;";
	document.getElementById("laws").style.visibility="visible";
}
function getLaws(name){//gets api response
	var xhr = new XMLHttpRequest();
	xhr.open("GET", "http://simplelaw.org/api.php?stateName=CT", false);
	xhr.send();
	var billJSON=JSON.parse(xhr.responseText);
	return [billJSON.bill.title,billJSON.bill.description,billJSON.bill.state_link];
}