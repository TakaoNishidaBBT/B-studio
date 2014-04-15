<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class calendar_ajax extends B_Module {
		function __construct() {
			parent::__construct(__FILE__);

			require_once('./config/calendar_config.php');
			$this->cal = new B_Calendar($this->db, $calendar_config);
		}

		function getCalendar() {
			if($this->request['year'] && $this->request['month']) {
				$this->year = $this->request['year'];
				$this->month = $this->request['month'];
			}
			else {
				$this->year = date('Y');
				$this->month = date('m');
			}

			switch($this->request['mode']) {
			case 'prev':
				$date = $this->util->addMonth($this->year, $this->month, -1);
				$this->year = $date['year'];
				$this->month = $date['month'];
				break;

			case 'next':
				$date = $this->util->addMonth($this->year, $this->month, 1);
				$this->year = $date['year'];
				$this->month = $date['month'];
				break;
			}

			$this->cal->setProperty($this->year, (int)$this->month);

			$this->response();
			exit;
		}

		function response() {
			header('Content-Type: application/xml');
			$html = '<div class="wrapper">' . $this->cal->getHtml() . '</div>';

			echo '<?xml version="1.0" encoding="' . B_CHARSET_XML_HEADER . '"?>';

			echo '<response>' . "\n";
			echo '	<year><![CDATA[' . $this->year . ']]></year>' . "\n";
			echo '	<month><![CDATA[' . $this->month . ']]></month>' . "\n";
			echo '	<innerHTML><![CDATA[' . $html . ']]></innerHTML>' . "\n";
			echo '</response>';
		}
	}
