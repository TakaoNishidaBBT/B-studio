/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.

 For licensing, see LICENSE.html or http://ckeditor.com/license

*/
CKEDITOR.config.wsc_removeGlobalVariable=!0;
CKEDITOR.plugins.add("wsc",{requires:"dialog",lang:"en,ja",icons:"spellchecker",hidpi:!0,parseApi:function(a){a.config.wsc_onFinish="function"===typeof a.config.wsc_onFinish?a.config.wsc_onFinish:function(){};a.config.wsc_onClose="function"===typeof a.config.wsc_onClose?a.config.wsc_onClose:function(){}},parseConfig:function(a){a.config.wsc_customerId=a.config.wsc_customerId||CKEDITOR.config.wsc_customerId||"1:ua3xw1-2XyGJ3-GWruD3-6OFNT1-oXcuB1-nR6Bp4-hgQHc-EcYng3-sdRXG3-NOfFk";a.config.wsc_customDictionaryIds=
a.config.wsc_customDictionaryIds||CKEDITOR.config.wsc_customDictionaryIds||"";a.config.wsc_userDictionaryName=a.config.wsc_userDictionaryName||CKEDITOR.config.wsc_userDictionaryName||"";a.config.wsc_customLoaderScript=a.config.wsc_customLoaderScript||CKEDITOR.config.wsc_customLoaderScript;CKEDITOR.config.wsc_cmd=a.config.wsc_cmd||CKEDITOR.config.wsc_cmd||"spell";CKEDITOR.config.wsc_version="v4.3.0-1e748a6"},init:function(a){this.parseConfig(a);this.parseApi(a);a.addCommand("checkspell",new CKEDITOR.dialogCommand("checkspell")).modes=
{wysiwyg:!CKEDITOR.env.opera&&!CKEDITOR.env.air&&document.domain==window.location.hostname};"undefined"==typeof a.plugins.scayt&&a.ui.addButton&&a.ui.addButton("SpellChecker",{label:a.lang.wsc.toolbar,command:"checkspell",toolbar:"spellchecker,10"});CKEDITOR.dialog.add("checkspell",this.path+(CKEDITOR.env.ie&&8>=CKEDITOR.env.version?"dialogs/wsc_ie.js":window.postMessage?"dialogs/wsc.js":"dialogs/wsc_ie.js"))}});