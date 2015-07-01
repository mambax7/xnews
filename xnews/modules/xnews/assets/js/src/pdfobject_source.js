/* 
	PDFObject, copyright (C) 2008 Philip Hutchison (pipwerks.com)
	Documentation and examples are at www.pdfobject.com 
	Version 1.0, September 2008
	MIT style license
*/

/* 
	PDFObject, copyright (C) 2008 Philip Hutchison (pipwerks.com)
	Documentation and examples are at www.pdfobject.com 
	Version 1.0, September 2008
	MIT style license
*/

/*global ActiveXObject */

var pipwerks = pipwerks || {};

/* ----------------------------------------------------
   Supporting functions
   ---------------------------------------------------- */

pipwerks.pdfUTILS = {


	termFound: function (strToSearch, term){
		return (strToSearch.indexOf(term) !== -1);
	},
	

	detect: {
		
		
		hasReaderActiveX: function (){

			var axObj = null;
			
			if (window.ActiveXObject) {
				
				axObj = new ActiveXObject("AcroPDF.PDF");
					
				if(!axObj){
					
					axObj = new ActiveXObject("PDF.PdfCtrl");
			
				}
				
				if (axObj !== null) { 
					
					return true;
					
				}
		
			}
			
			return false;
			
		},


		hasReader: function (){
		
			var termFound = pipwerks.pdfUTILS.termFound;
			var n = navigator.plugins;
			
			for(var i=0; i < n.length; i++){
				
				var name = n[i].name;
				
				if(termFound(name, "Adobe Reader") || termFound(name, "Adobe PDF") || termFound(name, "Acrobat")){
					
					return true;
					
				}
		
			}
			
			return false;
		
		},


		hasGeneric: function (){				//Detects PDF plugin, regardless of manufacturer
			
			var plugin = navigator.mimeTypes["application/pdf"];
			return (plugin && plugin.enabledPlugin);
		
		},
		

		pluginFound: function (){
		
			var type = null;
			var detect = pipwerks.pdfUTILS.detect;
			
			if(detect.hasReader() || detect.hasReaderActiveX()){
				
				type = "Adobe";
			
			} else if(detect.hasGeneric()) {
			
				type = "generic";
			
			}
			
			return type;
		
		}

	}, // END detect


	setCssForFullWindowPdf: function (){
		
		if(!document.getElementsByTagName){ return false; }
		
		var html = document.getElementsByTagName("html");
		if(!html){ return false; }
		
		var el = html[0];
		el.style.height = "100%";
		el.style.overflow = "hidden";
	
		var b = document.body;	
		b.style.margin = "0";
		b.style.padding = "0";
		b.style.height = "100%";
		b.style.overflow = "hidden";
		
	},


	buildQueryString: function(pdfOpenParams){
		
		var string = "";
		
		if(!pdfOpenParams){ return string; }
		
		for (var prop in pdfOpenParams) {
			
			if (pdfOpenParams.hasOwnProperty(prop)) {
				
				string += prop + "=";
				
				if(prop === "search") {
					
					string += encodeURI(pdfOpenParams[prop]);
				
				} else {
					
					string += pdfOpenParams[prop];
					
				}	
				
				string += "&";
				
			}
			
		}
		
		//Remove last ampersand
		return string.slice(0, string.length - 1);
	
	}
	
};



/* ----------------------------------------------------
   PDF Embedding functions
   ---------------------------------------------------- */

pipwerks.pdfObject = function (obj){
	
	if(!document.getElementById || !obj.url){ return false; }

	this.url = encodeURI(obj.url) + "#";									//The hash (#) prevents odd behavior in Windows
	this.id = obj.id || false;	
	this.width = obj.width   || "100%";
	this.height = obj.height || "100%";
	this.pdfOpenParams = obj.pdfOpenParams;
	this.url += pipwerks.pdfUTILS.buildQueryString(this.pdfOpenParams);	//Append optional Adobe params for opening document
	this.pluginTypeFound = pipwerks.pdfUTILS.detect.pluginFound();
	this.pdfobjectversion = "1.0";
	return this;															//"return this" allows chaining
	
};


pipwerks.pdfObject.prototype.get = function(prop){
	
	var value = null;
	
	switch(prop){
		case "url" : value = this.url; break;
		case "id" : value = this.id; break;
		case "width" : value = this.width; break;
		case "height" : value = this.height; break;
		case "pdfOpenParams" : value = this.pdfOpenParams; break;
		case "pluginTypeFound" : value = this.pluginTypeFound; break;
		case "pdfobjectversion" : value = this.pdfobjectversion; break;
	}
	
	return value;
	
};


pipwerks.pdfObject.prototype.embed = function(targetID){
		
	if(!this.pluginTypeFound){ return false; }
	if(!document.createElement || !document.getElementById){ return false; }
	
	var isIE =/*@cc_on!@*/false;
	var targetNode = null;

	if(targetID){
		
		targetNode = document.getElementById(targetID);
		//Ensure target element is found in document before continuing
		if(!targetNode){ return false; }
	
	} else {
	
		targetNode = document.body; 
		pipwerks.pdfUTILS.setCssForFullWindowPdf();
		this.width = "100%";
		this.height = "100%";
		
	}
	

	var objEl;
	
	if(isIE){
		
		//Special handling for our special friend IE6
		objEl = document.createElement("<object classid='CLSID:CA8A9780-280D-11CF-A24D-444553540000'>");	
	
	} else {
		
		objEl = document.createElement("object");
	
	}
	
	objEl.setAttribute("type", "application/pdf");
	objEl.setAttribute("data", this.url);
	objEl.setAttribute("width", this.width);
	objEl.setAttribute("height", this.height);
	if(this.id){ objEl.setAttribute("id", this.id); }

	

	//Remove child nodes if necessary
	if(targetNode.hasChildNodes){
		
		while(targetNode.childNodes.length > 0){
			
			targetNode.removeChild(targetNode.firstChild);
			
		}
		
	}
	
	targetNode.appendChild(objEl);
	
	return objEl;
	
};

var PDFObject = pipwerks.pdfObject;