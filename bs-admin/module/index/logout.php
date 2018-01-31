<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	$ses = new B_Session;
	$ses->end();

	$path = B_SITE_BASE . 'bs-admin/';
	header("Location:$path");
	exit;
