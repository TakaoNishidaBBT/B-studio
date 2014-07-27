<?php
/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_Util
	// 
	// -------------------------------------------------------------------------
	class B_Util {
		function getRandomText($length){
			$base = 'abcdefghijkmnprstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ2345678';
			for($i=0 ; $i<$length ; $i++){
				$pwd.= $base{mt_rand(0, strlen($base)-1)};
			}
			return $pwd;
		}

		function removeComma($value) {
			return str_replace(',', '', $value);
		}

		function addMonth($year, $month, $add) {
			$yearmonth = mktime(0, 0, 0, $month, 1, $year);
			$param = $add . ' month';
			$date['year'] = date('Y', strtotime($param, $yearmonth));
			$date['month'] = date('m', strtotime($param, $yearmonth));

			return $date;
		}

		function computeDate($year, $month, $day, $add) {
			$date['year'] = date('Y', mktime( 0,0,0,(int)$month, (int)($day+$add) , (int)$year ));
			$date['month'] = date('m', mktime( 0,0,0,(int)$month, (int)($day+$add) , (int)$year ));
			$date['day'] = date('d', mktime( 0,0,0,(int)$month, (int)$day+$add , (int)$year ));

			return $date;
		}

		function getLastDayofMonth($year, $month) {
			return date('t', mktime(0, 0, 0, (int)$month, 1, (int)$year));
		}

		function print_r_xml($tag, $arr) {
			if(is_numeric($tag)) {
				$tag = 'array';
			}
			$output = '<' . $tag . '>';
			foreach($arr as $key => $val) {
				if(is_array($val)) {
					$output.= $this->print_r_xml($key, $val);
				}
				else {
					$output.= '<' . htmlspecialchars($key) . '>';
					$output.= htmlspecialchars($val);
					$output.= '</' . htmlspecialchars($key) . ">\n";
				}
			}
			$output.= '</' . $tag . '>';
			return $output;
		}

		function mb_convert_encoding($str, $to_encoding, $from_encoding=NULL) {
			if(is_array($str)) {
				foreach($str as $key => $value) {
					$str[$key] = $this->mb_convert_encoding($value, $to_encoding, $from_encoding);
				}
			}
			else {
				$str = mb_convert_encoding($str, $to_encoding, $from_encoding);
			}
			return $str;
		}

		function json_encode($data) {
			foreach($data as $key => $value) {
				if($json) $json .= ',';
				if(!is_numeric($key)) {
					$json.= $this->stringToCode($key) . ':';
				}
				if(is_array($value)) {
					$json.= $this->json_encode($value);
				}
				else {
					$json.= $this->stringToCode($value);
				}
			}
			if($this->isKeyExist($data)) {
				$json = '{' . $json . '}';
			}
			else {
				$json = '[' . $json . ']';
			}
			return $json;
		}

		function stringToCode($val) {
			static $from = array('\\',  "\n", "\r", '"');
			static $to   = array('\\\\','\\n','\\r','\\"');
			static $cmap = array(0x80, 0xFFFF, 0, 0xFFFF);
			return '"'. preg_replace_callback(
				'/&#([0-9]+);/',
				create_function('$match','return sprintf("\\u%04x", $match[1]);'),
				mb_encode_numericentity(str_replace($from, $to, $val), $cmap, 'UTF-8')
			) . '"';
		}

		function isKeyExist($array) {
			$i=0;
			foreach($array as $key => $value) {
				if($key !== $i) {
					return true;
				}
				$i++;
			}
			return false;
		}

		function pathinfo($path) {
			$info['path'] = $path;
			$info['dirname'] = '';
			$info['basename'] = '';
			$info['filename'] = '';
			$info['extension'] = '';

			$i = strrpos($path, '/');
			if($i !== false ){
				if($i) $info['dirname'] = substr($path, 0, $i);
				else $info['dirname'] = substr($path, 0, $i+1);
				$info['basename'] = substr($path, $i+1);
			}
			else {
				$info['basename'] = $path;
			}

			$i = strrpos($info['basename'], '.');
			if($i) {
				$info['filename'] = substr($info['basename'], 0, $i);
				$info['extension'] = substr($info['basename'], $i+1);
			}
			else {
				$info['filename'] = $info['basename'];
			}

			return $info;
		}

		function getPath($dir, $file_name) {
			$dir = str_replace('\\', '/', $dir);
			if(substr($dir, -1) == '/') {
				$dir = substr($dir, 0, -1);
			}
			if(substr($file_name, 0, 1) == '/') {
				$file_name = substr($file_name, 1);
			}

			return $dir . '/' . $file_name;
		}

		function encodeNumericEntity($str) {
			$convmap = array(0, 0x2FFFF, 0, 0xFFFF);

			for($i=0 ; $i<mb_strlen($str) ; $i++) {
				if(rand(0, 5)) {
					$ret.= mb_encode_numericentity(mb_substr($str, $i, 1), $convmap, mb_internal_encoding());
				}
				else {
					$ret.= mb_substr($str, $i, 1);
				}
			}

			return $ret;
		}

		function human_filesize($bytes, $scale) {
			$factor = array('B' => 0, 'K' => 1, 'M' => 2, 'G' => 3, 'T' => 4, 'P' => 5);
			if(!$unit = $factor[$scale]) return $bytes;

			$value = $bytes / pow(1024, $unit);
			$value = ceil($value * 100) / 100;
			return sprintf("%.2f", $value) . $scale . 'B';
		}

		function decode_human_filesize($filesize) {
			$factor = strtoupper(substr(trim($filesize), -1));
			$size = substr(trim($filesize), 0, -1);
			if(!is_numeric($size)) return;

			switch($factor) {
			case 'K':
				return round($size * 1024);

			case 'M':
				return round($size * pow(1024, 2));

			case 'G':
				return round($size * pow(1024, 3));

			default:
				return $filesize;
			}
		}
	}
