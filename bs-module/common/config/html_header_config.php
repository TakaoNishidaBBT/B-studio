<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$html_header_config = array(
	'doc_type'	=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
	'html'		=> '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja">',
	'meta'		=>
	array(
		'<meta http-equiv="Pragma" content="no-cache" />',
		'<meta http-equiv="Cache-Control" content="no-cache" />',
		'<meta http-equiv="Expires" content="Thu, 01 Dec 1994 16:00:00 GMT" />',
		'<meta http-equiv="Content-Style-Type" content="text/css" />',
		'<meta http-equiv="Content-Script-Type" content="text/javascript" />',
	),
	'misc'		=> array(),
	'base'		=>
	array(
		'<base href="' . B_SITE_BASE . '" />',
	),
	'script'	=> array(),
	'css'		=> array(),
);
