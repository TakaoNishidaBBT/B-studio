<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	$ses = new B_Session;
	$ses->end();

	$path = B_SITE_BASE . "bs-admin/";
	header("Location:$path");
	exit;
