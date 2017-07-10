//***********************************************
//* Simple Tree Menu- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
//* This notice MUST stay intact for legal use
//* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
//***********************************************/
//based loosely on Simple Tree Menu - Modified completely by Nathan Kopka

var linkText = " "  //this is the text to append to the end of the link at the various navigation levels
var defaultPage = "default.html"

//////////No need to edit beyond here///////////////////////////
var ddtreemenu=new Object()

ddtreemenu.createTree=function(treeid){
	var ultags=document.getElementById(treeid).getElementsByTagName("ul")
	for (var i=0; i<ultags.length; i++){
		ultags[i].parentNode.className="submenu"
	}
	var pageMatch = 0 
	var sPath = window.location.pathname;
	sPath = sPath.replace(/%21/,"!") //escape ! used in testing sites
	var sDirectoryPath = sPath.substring(1,sPath.lastIndexOf('/'));	
	var sPage = sPath.substring(sPath.lastIndexOf('/') + 1);
	if (sPage+' '==' '){
		sPath = sPath+defaultPage
	}
	//document.write("sPath: "+sPath+" sDirectoryPath: "+sDirectoryPath+" sPage: "+sPage+"<br/>");
	
	var atags=document.getElementById(treeid).getElementsByTagName("a")
	for (var i=0; i<atags.length; i++){
		var aPath = atags[i].href;
		var aPath = aPath.substring(aPath.indexOf("/",7));
		var aDirectoryPath = aPath.substring(1,aPath.lastIndexOf('/'));
		var aPage = aPath.substring(aPath.lastIndexOf('/') + 1);
		if (aPage+' '==' '){
			aPath = aPath+defaultPage
		}
		//document.write("Link Name: "+atags[i].innerHTML+" aPath: "+aPath+" aDirectoryPath: "+aDirectoryPath+" aPage: "+aPage+"<br/>");
		
		if (aPath.toLowerCase()==sPath.toLowerCase() && aPage.indexOf("#")<=0){
			//document.write("MATCH!!!<br/>")
			pageMatch = 1
			atags[i].className="URHere"
			atags[i].innerHTML=atags[i].innerHTML+linkText
			ddtreemenu.expandSubTree(treeid, atags[i]) //expand this UL plus all parent ULs (so the most inner UL is revealed!)
			var currentnode=atags[i].parentNode
			for (var j=0; j<currentnode.childNodes.length; j++){
				if (currentnode.childNodes[j].tagName=="UL"){
					currentnode.childNodes[j].style.display="block"
				}
			}
		}
	}
	if (pageMatch==0){//if page is not part of navigation, then set nav based on directory path of page
		//document.write("No page match; try match on directory<br/>")
		for (var i=0; i<atags.length; i++){
			var aPath = atags[i].href;
			var aPath = aPath.substring(aPath.indexOf("/",7));
			var aDirectoryPath = aPath.substring(1,aPath.lastIndexOf('/'));			
			var aPage = aPath.substring(aPath.lastIndexOf('/') + 1);
			if (aPage+' '==' '){
				aPath = aPath+defaultPage
			}
			//document.write("Link Name: "+atags[i].innerHTML+" aPath: "+aPath+" aDirectoryPath: "+aDirectoryPath+" aPage: "+aPage+"<br/>");
			
					
			if (aDirectoryPath.toLowerCase()==sDirectoryPath.toLowerCase() && aPage.indexOf("#")<=0){
				//document.write("MATCH!!!<br/>")

				pageMatch = 1
				atags[i].className="URHere"
				atags[i].innerHTML=atags[i].innerHTML+linkText
				ddtreemenu.expandSubTree(treeid, atags[i]) //expand this UL plus all parent ULs (so the most inner UL is revealed!)
				var currentnode=atags[i].parentNode
				for (var j=0; j<currentnode.childNodes.length; j++){
					if (currentnode.childNodes[j].tagName=="UL"){
						currentnode.childNodes[j].style.display="block"
					}
				}
			}
		}
		
	}
}

ddtreemenu.expandSubTree=function(treeid, ulelement){ //expand a UL element and any of its parent ULs
	var rootnode=document.getElementById(treeid)
	var currentnode=ulelement
	currentnode.style.display="block"
	while (currentnode!=rootnode){
		if (currentnode.tagName=="UL"){ //if parent node is a UL, expand it too
			currentnode.style.display="block"
			setURhere(currentnode.parentNode)
		}
		if (currentnode.tagName=="LI"){
			currentnode.className="URHere"
		}
		currentnode=currentnode.parentNode
	}
}

setURhere=function(element2){//set URHere flag
	var currentnode=element2
	var atags=currentnode.getElementsByTagName("a")
	atags[0].className="URHere"
	if (atags[0].innerHTML.indexOf("&gt;")==-1){
		atags[0].innerHTML=atags[0].innerHTML+linkText
	}
}

//function used only in testing

ddtreemenu.flatten=function(treeid, action){ //expand or contract all UL elements
	var ultags=document.getElementById(treeid).getElementsByTagName("ul")
	for (var i=0; i<ultags.length; i++){
		ultags[i].style.display=(action=="expand")? "block" : "none"
	}
}

