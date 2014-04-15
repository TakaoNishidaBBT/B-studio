<?php
/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_Calendar
	// 
	// -------------------------------------------------------------------------
	class B_Calendar {
		function __construct($db, $config) {
			$this->db = $db;
			$this->config = $config;

			$this->cal = new B_DataGrid($this->db, $this->config['grid']);
			$this->caption = new B_Element($this->config['caption']);
		}

		function setProperty($year, $month, $start_day_of_the_week=0) {
			$this->year = $year;
			$this->month = str_pad($month, 2, '0', STR_PAD_LEFT);
			$this->start_day_of_the_week = $start_day_of_the_week;

			$this->data = $this->setWeekArray();

			$param['year'] = $year;
			$param['month'] = $month;
			$this->caption->setValue($param);
		}

		function setWeekArray() {
			$date = $this->year . $this->month . '01';
			$week_index = date('w', strtotime($date));

			for($i=0, $d=1, $w=$week_index; checkdate($this->month, $d, $this->year) ; $i++) {
				for($j=0; $j<7 ;$j++) {
					if($j == $w && checkdate($this->month, $d, $this->year)) {
						$week_array[$i][$j+1] = $d++;
						$w++;
					}
					else {
						$week_array[$i][$j+1] = '';
					}
				}
				$w=0;
			}

			return $week_array;
		}

		function setHoliday($row) {
			$this->holiday[] = $row;
		}

		function getHtml() {
			// callbackパラメータ設定
			$this->callback_param = array(
				'holiday' 		=> $this->holiday,
				'year'			=> $this->year,
				'month'			=> $this->month,
			);
			// コールバック設定
			$this->cal->setTrCallBack($this,'_holiday_callback', array('param' => $this->callback_param));

			$this->cal->bind($this->data);
			$this->cal->setCaption($this->caption->getHtml());

			return $this->cal->getHtml();
		}

		function _holiday_callback($array) {
			$row = &$array['row'];
			$param = &$array['param'];
			$year = $param['year'];
			$month = $param['month'];
			$holiday = $param['holiday'];

			if(is_array($holiday)) {
				foreach($holiday as $value) {
					if($value['holiday_year'] == $year && $value['holiday_month'] == $month) {
						for($i=0; $i<7; $i++) {
							$obj = $row->getElementByName($i+1);
							if($obj->value == $value['holiday_day']) {
								$obj->start_html = '<td class="holiday day">';
							}
						}
					}
				}
			}
			for($i=0; $i<7; $i++) {
				$obj = $row->getElementByName($i+1);
				if(!$obj->value) {
					$obj->start_html = $obj->start_html_empty;
				}
			}
		}
	}
