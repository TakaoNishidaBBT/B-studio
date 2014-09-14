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

		function human_filesize($bytes, $scale=0) {
			$factor_array = array('B' => 0, 'K' => 1, 'M' => 2, 'G' => 3, 'T' => 4, 'P' => 5);
			if($scale) {
				$unit = $factor_array[$scale];
			}
			else {
				for($unit=0, $size=$bytes; $size > 1024 ; $size=($size / 1024), $unit++);
				$factor_flip = array_flip($factor_array);
				$scale = $factor_flip[$unit];
			}
			$value = $bytes;
			if($unit) {
				$value = $bytes / pow(1024, $unit);
				$value = ceil($value * 10) / 10;
			}
			if($scale == 'B') {
				return sprintf("%d", $value) . $scale;
			}
			else {
				return sprintf("%.1f", $value) . $scale . 'B';
			}
		}

		function decode_human_filesize($filesize) {
			$factor_array = array('B' => 0, 'K' => 1, 'M' => 2, 'G' => 3, 'T' => 4, 'P' => 5);
			$factor = strtoupper(substr(trim($filesize), -1));
			$size = substr(trim($filesize), 0, -1);
			if(!is_numeric($size)) return;

			$unit = $factor_array[$factor];
			if($unit) {
				$size = round($size * pow(1024, $unit));
			}
			return $size;
		}

		function mb_convert_encoding_array($array, $to_encoding, $from_encoding) {
			if(!is_array($array)) return;

			foreach($array as $key => $value) {
				$ret[$key] = mb_convert_encoding($value, $to_encoding, $from_encoding);
			}
			return $ret;
		}

		function is_binary($file) {
			$fp = fopen($file, 'r');
			while($line = fgets($fp)) {
				if(strpos($line, "\0") !== false) {
					fclose($fp);
					return true;
				}
			}

			fclose($fp);
			return false;
		}

		function imagecreatefrombmp($filename) {
			if(!$fp = fopen($filename, 'rb')) return false;

			$file = unpack('vfile_type/Vfile_size/Vreserved/Vbitmap_offset', fread($fp, 14));
			if($file['file_type'] != 19778) return false;

			$bmp = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
					'/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
					'/Vvert_resolution/Vcolors_used/Vcolors_important', fread($fp, 40));
			$bmp['colors'] = pow(2, $bmp['bits_per_pixel']);

			if($bmp['size_bitmap'] == 0) $bmp['size_bitmap'] = $file['file_size'] - $file['bitmap_offset'];

			$bmp['bytes_per_pixel'] = $bmp['bits_per_pixel']/8;
			$bmp['bytes_per_pixel2'] = ceil($bmp['bytes_per_pixel']);
			$bmp['decal'] = ($bmp['width']*$bmp['bytes_per_pixel']/4);
			$bmp['decal'] -= floor($bmp['width']*$bmp['bytes_per_pixel']/4);
			$bmp['decal'] = 4-(4*$bmp['decal']);
			if($bmp['decal'] == 4) $bmp['decal'] = 0;

			$palette = array();
			switch($bmp['bits_per_pixel']) {
			case '1':
			case '4':
			case '8':
				$palette = unpack('V'.$bmp['colors'], fread($fp, $bmp['colors']*4));
				$p = $file['bitmap_offset'] + $bmp['colors']*4;
				break;

			default:
				$p = $file['bitmap_offset'];
				break;
			}
			rewind($fp);
			$img = fread($fp, $file['file_size']);
			$vide = chr(0);

			$res = imagecreatetruecolor($bmp['width'], $bmp['height']);
			$y = $bmp['height']-1;
			while($y >= 0) {
				$x=0;
				while ($x < $bmp['width']) {
					switch($bmp['bits_per_pixel']) {
					case '32':
					case '24':
						$color = unpack('V', substr($img, $p, 3).$vide);
						break;

					case '16':
						$color = unpack('v', substr($img, $p, 2));
						$bin = str_pad(decbin($color[1]), 16, '0', STR_PAD_LEFT);
						$r = bindec(substr($bin, 1, 5)) * 8;
						$g = bindec(substr($bin, 6, 5)) * 8;
						$b = bindec(substr($bin, 11, 5)) * 8;
						$color[1] = imagecolorallocate($res, $r, $g, $b);
						break;

					case '8':
						$color = unpack('n', $vide.substr($img, $p, 1));
						$color[1] = $palette[$color[1]+1];
						break;

					case '4':
						$color = unpack('n',$vide.substr($img, floor($p), 1));
						if(($p*2)%2 == 0) {
							$color[1] = ($color[1] >> 4);
						}
						else {
							$color[1] = ($color[1] & 0x0F);
						}
						$color[1] = $palette[$color[1]+1];
						break;

					case '1':
						$color = unpack('n', $vide.substr($img, floor($p), 1));
						if(($p*8)%8 == 0) $color[1] = $color[1] >>7;
						else if(($p*8)%8 == 1) $color[1] = ($color[1] & 0x40)>>6;
						else if(($p*8)%8 == 2) $color[1] = ($color[1] & 0x20)>>5;
						else if(($p*8)%8 == 3) $color[1] = ($color[1] & 0x10)>>4;
						else if(($p*8)%8 == 4) $color[1] = ($color[1] & 0x8)>>3;
						else if(($p*8)%8 == 5) $color[1] = ($color[1] & 0x4)>>2;
						else if(($p*8)%8 == 6) $color[1] = ($color[1] & 0x2)>>1;
						else if(($p*8)%8 == 7) $color[1] = ($color[1] & 0x1);
						$color[1] = $palette[$color[1]+1];
						break;

					default:
						return false;
					}

					imagesetpixel($res, $x, $y, $color[1]);
					$x++;
					$p += $bmp['bytes_per_pixel'];
				}
				$y--;
				$p+=$bmp['decal'];
			}
			fclose($fp);
			return $res;
		}
	}
