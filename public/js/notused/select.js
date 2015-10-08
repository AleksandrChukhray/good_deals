// JavaScript Document

var checkboxHeight = "28";
var radioHeight = "20";
/*var selectWidth = "232";*/


/* No need to change anything after this */


document.write('<style type="text/css">input.styled { display: none; } select.styled { position: relative; width: 233px; height:43px; z-index: 5; background-color: rgba(255,255,255, 0.6);color:#3a0144; -webkit-border-radius: 7px; -moz-border-radius: 7px; border-radius: 7px; border-left:1px solid #e5e1e5; border-bottom:1px solid #e5e1e5; border-right:1px solid #e5e1e5; border-top:2px solid #fff; background-image: url("/public/images/select_arrow.png"); background-position: right center; background-repeat: no-repeat;} select.styled:hover, select.styled:active{background-color: rgba(255,255,255, 1); cursor:pointer;}.disabled { opacity: 0.5; filter: alpha(opacity=50); } select.styled_services { position: relative; width: 300px; height:46px; z-index: 5; background-color: rgba(255,255,255, 0.6);color:#3a0144; -webkit-border-radius: 7px; -moz-border-radius: 7px; border-radius: 7px; border-left:1px solid #e5e1e5; border-bottom:1px solid #e5e1e5; border-right:1px solid #e5e1e5; border-top:2px solid #fff; background-image: url("/public/images/select_arrow.png"); background-position: right center; background-repeat: no-repeat; font-size:27px;} select.styled_services:hover, select.styled_services:active{background-color: rgba(255,255,255, 1); cursor:pointer; } .disabled { opacity: 0.5; filter: alpha(opacity=50); }</style>');

var Custom = {
	init: function() {
		var inputs = document.getElementsByTagName("input"), span = Array(), textnode, option, active;
		for(a = 0; a < inputs.length; a++) {
			if((inputs[a].type == "checkbox" || inputs[a].type == "radio") && inputs[a].className.indexOf("styled") > -1) {
				span[a] = document.createElement("span");
				span[a].className = inputs[a].type;

				if(inputs[a].checked == true) {
					if(inputs[a].type == "checkbox") {
						position = "0 -" + (checkboxHeight*2) + "px";
						span[a].style.backgroundPosition = position;
					} else {
						position = "0 -" + (radioHeight*2+2) + "px";
						span[a].style.backgroundPosition = position;
					}
				}
				inputs[a].parentNode.insertBefore(span[a], inputs[a]);
				inputs[a].onchange = Custom.clear;
				if(!inputs[a].getAttribute("disabled")) {
					span[a].onmousedown = Custom.pushed;
					span[a].onmouseup = Custom.check;
				} else {
					span[a].className = span[a].className += " disabled";
				}
				textnode = document.createTextNode(active);
			}
		}
	
		/*inputs = document.getElementById("select1");		
		for(a = 0; a < inputs.length; a++) {
			if(inputs[a].className.indexOf("styled") > -1) {
				option = inputs[a].getElementsByTagName("option");
				active = option[0].childNodes[0].nodeValue;
				textnode = document.createTextNode(option[b].childNodes[0].nodeValue);
			}
		}*/
		
		/*inputs = document.getElementById("select2");		
		for(a = 0; a < inputs.length; a++) {
			if(inputs[a].className.indexOf("styled_services") > -1) {
				option = inputs[a].getElementsByTagName("option");
				active = option[0].childNodes[0].nodeValue;
				textnode = document.createTextNode(active);
				for(b = 0; b < option.length; b++) {
					if(option[b].selected == true) {
						textnode = document.createTextNode(option[b].childNodes[0].nodeValue);
					}
				}
			}
		}*/
		
		
		document.onmouseup = Custom.clear;
	},
	pushed: function() {
		element = this.nextSibling;
		if(element.checked == true && element.type == "checkbox") {
			this.style.backgroundPosition = "0 -" + checkboxHeight*3 + "px";
		} else if(element.checked == true && element.type == "radio") {
			this.style.backgroundPosition = "0 -" + radioHeight*3 + "px";
		} else if(element.checked != true && element.type == "checkbox") {
			this.style.backgroundPosition = "0 -" + checkboxHeight + "px";
		} else {
			this.style.backgroundPosition = "0 -" + radioHeight + "px";
		}
	},
	check: function() {
		element = this.nextSibling;
		if(element.checked == true && element.type == "checkbox") {
			this.style.backgroundPosition = "0 0";
			element.checked = false;
		} else {
			if(element.type == "checkbox") {
				this.style.backgroundPosition = "0 -" + checkboxHeight*2 + "px";
			} else {
				this.style.backgroundPosition = "0 -" + radioHeight*2 + "px";
				group = this.nextSibling.name;
				inputs = document.getElementsByTagName("input");
				for(a = 0; a < inputs.length; a++) {
					if(inputs[a].name == group && inputs[a] != this.nextSibling) {
						inputs[a].previousSibling.style.backgroundPosition = "0 0";
					}
				}
			}
			element.checked = true;
		}
	},
	clear: function() {
		inputs = document.getElementsByTagName("input");
		for(var b = 0; b < inputs.length; b++) {
			if(inputs[b].type == "checkbox" && inputs[b].checked == true && inputs[b].className.indexOf("styled") > -1) {
				inputs[b].previousSibling.style.backgroundPosition = "0 -" + checkboxHeight*2 + "px";
			} else if(inputs[b].type == "checkbox" && inputs[b].className.indexOf("styled") > -1) {
				inputs[b].previousSibling.style.backgroundPosition = "0 0";
			} else if(inputs[b].type == "radio" && inputs[b].checked == true && inputs[b].className.indexOf("styled") > -1) {
				inputs[b].previousSibling.style.backgroundPosition = "0 -" + radioHeight*2 + "px";
			} else if(inputs[b].type == "radio" && inputs[b].className.indexOf("styled") > -1) {
				inputs[b].previousSibling.style.backgroundPosition = "0 0";
			}
		}
	},
	choose: function() {
		option = this.getElementsByTagName("option");
		for(d = 0; d < option.length; d++) {
			if(option[d].selected == true) {
				document.getElementById("select" + this.name).childNodes[0].nodeValue = option[d].childNodes[0].nodeValue;
			}
		}
	}
}
window.onload = Custom.init;