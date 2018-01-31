<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
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
			// Check request date
			$day = $this->request['day'] ? $this->request['day'] : '01';
			$request_date = $this->request['year'] . '/' . $this->request['month'] . '/' . $day;
			$ret = B_Util::checkdate($request_date);

			if($ret && $this->request['year'] && $this->request['month']) {
				$this->year = $this->request['year'];
				$this->month = $this->request['month'];
				if($this->request['day']) {
					$this->day = $this->request['day'];
				}
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
			$this->cal->setProperty($this->year, (int)$this->month, (int)$this->day);

			$this->response();
			exit;
		}

		function response() {
			$response['year'] = $this->year;
			$response['month'] = $this->month;
			$response['innerHTML'] = '<div class="wrapper">' . $this->cal->getHtml() . '</div>';

			header('Content-Type: application/x-javascript charset=utf-8');
			echo json_encode($response);
		}
	}
