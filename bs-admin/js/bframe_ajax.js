/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	function createXMLHttpRequest(cbFunc) {
		var XMLhttpObject = null;
		try {
			XMLhttpObject = new XMLHttpRequest();
		}
		catch(e) {
			try {
				XMLhttpObject = new ActiveXObject('Msxml2.XMLHTTP');
			}
			catch(e) {
				try {
					XMLhttpObject = new ActiveXObject('Microsoft.XMLHTTP');
				}
				catch(e) {
					return null;
				}
			}
		}
		if(XMLhttpObject) XMLhttpObject.onreadystatechange = cbFunc;
		return XMLhttpObject;
	}

	function eventHandler(httpObj, module, page, m, method, param) {
		if(httpObj) {
			if(method == 'POST') {
				var url = 'index.php';
				var p = param+
						'&module='+module+
						'&page='+page+
						'&method='+m;
				httpObj.open(method, url, true);
				httpObj.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
				httpObj.send(p);
			}
			else {
				var url = 'index.php?'+param+
						'&module='+module+
						'&page='+page+
						'&method='+m;
				httpObj.open(method, url, true);
				httpObj.setRequestHeader('If-Modified-Since', 'Thu, 01 Jun 1970 00:00:00 GMT');
				httpObj.send(null);
			}
		}
		return;
	}
