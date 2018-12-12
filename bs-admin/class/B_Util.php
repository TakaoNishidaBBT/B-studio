<?php
/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_Util
	// 
	// -------------------------------------------------------------------------
	class B_Util {
		public static function removeComma($value) {
			return str_replace(',', '', $value);
		}

		public static function addMonth($year, $month, $add) {
			$yearmonth = mktime(0, 0, 0, $month, 1, $year);
			$param = $add . ' month';
			$date['year'] = date('Y', strtotime($param, $yearmonth));
			$date['month'] = date('m', strtotime($param, $yearmonth));

			return $date;
		}

		public static function computeDate($year, $month, $day, $add) {
			$date['year'] = date('Y', mktime( 0,0,0,(int)$month, (int)($day+$add) , (int)$year ));
			$date['month'] = date('m', mktime( 0,0,0,(int)$month, (int)($day+$add) , (int)$year ));
			$date['day'] = date('d', mktime( 0,0,0,(int)$month, (int)$day+$add , (int)$year ));

			return $date;
		}

		public static function getLastDayofMonth($year, $month) {
			return date('t', mktime(0, 0, 0, (int)$month, 1, (int)$year));
		}

		public static function checkdate($date) {
			if(!$date) return true;

			$date_array = explode('/', $date);
			return @checkdate($date_array[1], $date_array[2], $date_array[0]);
		}

		public static function replaceLFcode($str) {
			$str = str_replace("\r\n", "\n", $str);
			$str = str_replace("\r", "\n", $str);

			return $str;
		}

		public static function stringToCode($val) {
			static $from = array('\\',  "\n", "\r", '"');
			static $to   = array('\\\\','\\n','\\r','\\"');
			static $cmap = array(0x80, 0xFFFF, 0, 0xFFFF);
			return '"'. preg_replace_callback(
				'/&#([0-9]+);/',
				create_function('$match','return sprintf("\\u%04x", $match[1]);'),
				mb_encode_numericentity(str_replace($from, $to, $val), $cmap, 'UTF-8')
			) . '"';
		}

		public static function mb_trim($str) {
			if(function_exists('mb_convert_kana')) {
				$s = mb_convert_kana(' ', 'S');
				$expression = '/^[\s' . $s . ']*(.*?)[\s' . $s . ']*$/u';
				$str = preg_replace($expression, '\1', $str);
			}
			return $str;
		}

		public static function mb_convert_encoding($str, $to_encoding, $from_encoding=NULL) {
			if(is_array($str)) {
				foreach($str as $key => $value) {
					$str[$key] = B_Util::mb_convert_encoding($value, $to_encoding, $from_encoding);
				}
			}
			else {
				$str = mb_convert_encoding($str, $to_encoding, $from_encoding);
			}
			return $str;
		}

		public static function isKeyExist($array) {
			$i=0;
			foreach($array as $key => $value) {
				if($key !== $i) {
					return true;
				}
				$i++;
			}
			return false;
		}

		public static function pathinfo($path) {
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
			if($i === false) {
				$info['filename'] = $info['basename'];
			}
			else {
				$info['filename'] = substr($info['basename'], 0, $i);
				$info['extension'] = substr($info['basename'], $i+1);
			}

			return $info;
		}

		public static function changeExtension($path, $extension) {
			$i = strrpos($path, '.');
			if($i) $new_path = substr($path, 0, $i) . '.' . $extension;

			return $new_path;
		}

		public static function getPath($dir, $file_name) {
			$dir = str_replace('\\', '/', $dir);
			if(substr($dir, -1) == '/') {
				$dir = substr($dir, 0, -1);
			}
			if(substr($file_name, 0, 1) == '/') {
				$file_name = substr($file_name, 1);
			}

			return $dir . '/' . $file_name;
		}

		public static function encodeNumericEntity($str) {
			$convmap = array(0, 0x2FFFF, 0, 0xFFFF);

			for($i=0; $i<mb_strlen($str); $i++) {
				if(rand(0, 5)) {
					$ret.= mb_encode_numericentity(mb_substr($str, $i, 1), $convmap, mb_internal_encoding());
				}
				else {
					$ret.= mb_substr($str, $i, 1);
				}
			}

			return $ret;
		}

		public static function human_filesize($bytes, $scale=0) {
			$factor_array = array('B' => 0, 'K' => 1, 'M' => 2, 'G' => 3, 'T' => 4, 'P' => 5);
			if($scale) {
				$unit = $factor_array[$scale];
			}
			else {
				for($unit=0, $size=$bytes; 1024 < $size; $size=($size / 1024), $unit++);
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

		public static function decode_human_filesize($filesize) {
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

		public static function get_mb_detect_order() {
			$order = mb_detect_order();
			foreach($order as $key => $value) {
				$array[$value] = $value;
			}
			return $array;
		}

		public static function is_binary($file) {
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

		public static function checkImageFileType($src) {
			switch(exif_imagetype($src)) {
				case IMAGETYPE_GIF:		return 'gif';
				case IMAGETYPE_JPEG:	return 'jpg';
				case IMAGETYPE_PNG:		return 'png';
				case IMAGETYPE_BMP:		return 'bmp';
				default:				return;
			}
		}

		public static function getimagesize($filepath, $file_name='') {
			if($file_name) {
				$file_info = B_Util::pathinfo($file_name);
			}
			else {
				$file_info = B_Util::pathinfo($filepath);
			}
			$file_extension = strtolower($file_info['extension']);

			// check the type of an image
			$file_type = B_Util::checkImageFileType($filepath);
			if(($file_extension == 'svg') && !$file_type) {
				libxml_use_internal_errors(true);
				$xml = simplexml_load_file($filepath);
				if($xml === false) return false;
				$attr = $xml->attributes();
				$size[0] = str_replace('px', '', $attr->width);
				$size[1] = str_replace('px', '', $attr->height);
				if($size[0] && $size[1]) {
					return $size;
				}
			}
			else {
				return getimagesize($filepath);
			}
		}

		public static function createthumbnail($src, &$dest, $max_size) {
			ini_set('memory_limit', '256M');

			$file_info = B_Util::pathinfo($src);
			$file_extension = strtolower($file_info['extension']);

			// check the type of an image
			$file_type = B_Util::checkImageFileType($src);
			if($file_type && $file_type != strtolower($file_info['extension'])) {
				$file_extension = $file_type;
			}

			switch($file_extension) {
			case 'jpg':
			case 'jpeg':
				if(!function_exists('imagecreatefromjpeg')) return;
				$image = imagecreatefromjpeg($src);
				// check rotate
				$exif = @exif_read_data($src);
				break;

			case 'gif':
				if(!function_exists('imagecreatefromgif')) return;
				$image = imagecreatefromgif($src);
				break;

			case 'png':
				if(!function_exists('imagecreatefrompng')) return;
				$image = imagecreatefrompng($src);
				break;

			case 'bmp':
				$image = B_Util::imagecreatefrombmp($src);
				break;

			case 'avi':
			case 'flv':
			case 'mov':
			case 'mp4':
			case 'mpg':
			case 'mpeg':
			case 'wmv':
				$src = B_Util::createMovieThumbnail($src);
				if(!function_exists('imagecreatefromjpeg')) return;
				$image = imagecreatefromjpeg($src);
				break;

			default:
				return;
			}

			// get image size
			$image_size = getimagesize($src);
			$width = $image_size[0];
			$height = $image_size[1];

			// check rotate
			if($exif && isset($exif['Orientation'])) {
				switch($exif['Orientation']) {
				case 3:
					$image = imagerotate($image, 180, 0);
					break;

				case 6:
					$image = imagerotate($image, 270, 0);
					$width = $image_size[1];
					$height = $image_size[0];
					$rotate = true;
					break;

				case 8:
					$image = imagerotate($image, 90, 0);
					$width = $image_size[1];
					$height = $image_size[0];
					$rotate = true;
					break;
				}
			}

			// scale down
			if($width > $max_size) {
				if($width > $height) {
					$height = round($height * $max_size / $width);
					$width = $max_size;
				}
				else {
					$width = round($width * $max_size / $height);
					$height = $max_size;
				}
			}
			else if($height > $max_size) {
				$width = round($width * $max_size / $height);
				$height = $max_size;
			}
			if(!$width) $width=1;
			if(!$height) $height=1;

			// create new image
			$new_image = imagecreatetruecolor($width, $height);

			if($rotate) {
				imagecopyresampled($new_image, $image, 0, 0, 0, 0, $width, $height, $image_size[1], $image_size[0]);
			}
			else {
				imagecopyresampled($new_image, $image, 0, 0, 0, 0, $width, $height, $image_size[0], $image_size[1]);
			}

			if(($image_size[2] == IMAGETYPE_GIF) || ($image_size[2] == IMAGETYPE_PNG) ) {
				// set transparency
				$trnprt_indx = imagecolortransparent($image);
				if($trnprt_indx >= 0) {
					$trnprt_color = imagecolorsforindex($image, $trnprt_indx);
					$trnprt_indx = imagecolorallocate($new_image, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
					imagefill($new_image, 0, 0, $trnprt_indx);
					imagecolortransparent($new_image, $trnprt_indx);
				} 
				else if($image_size[2] == IMAGETYPE_PNG) {
					imagealphablending($new_image, false);
					$color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
					imagefill($new_image, 0, 0, $color);
					imagesavealpha($new_image, true);
				}
				imagecopyresampled($new_image, $image, 0, 0, 0, 0, $width, $height, $image_size[0], $image_size[1]);
			}

			switch($file_extension) {
			case 'jpg':
			case 'jpeg':
			case 'bmp':
				imagejpeg($new_image, $dest, 100);
				break;

			case 'gif':
				imagegif($new_image, $dest);
				break;

			case 'png':
				imagepng($new_image, $dest);
				break;

			case 'avi':
			case 'flv':
			case 'mov':
			case 'mp4':
			case 'mpg':
			case 'mpeg':
			case 'wmv':
				$dest = B_Util::changeExtension($dest, 'jpg');
				imagejpeg($new_image, $dest, 100);
				unlink($src);
				break;
			}

			return true;
		}

		public static function createMovieThumbnail($src) {
			$ffmpeg = FFMPEG;
			$output = B_RESOURCE_WORK_DIR . time() . 'tmp.jpg';
			$cmdline = "$ffmpeg -ss 3 -i $src -f image2 -vframes 1 $output";
			B_Util::fork($cmdline, false);

			return $output;
		}

		public static function imagecreatefrombmp($src) {
			if(!$fp = fopen($src, 'rb')) return false;

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
				break;

			default:
				break;
			}
			rewind($fp);
			$img = fread($fp, $file['file_size']);
			$vide = chr(0);

			$res = imagecreatetruecolor($bmp['width'], $bmp['height']);
			$p = $file['bitmap_offset'];
			$y = $bmp['height']-1;
			while($y >= 0) {
				$x=0;
				while ($x < $bmp['width']) {
					switch($bmp['bits_per_pixel']) {
					case '32':
					case '24':
						$color = unpack('V', substr($img, $p, 3) . $vide);
						break;

					case '16':
						$color = unpack('v', substr($img, $p, 2));
						$bin = str_pad(decbin($color[1]), 16, '0', STR_PAD_LEFT);
						$r = bindec(substr($bin, 1, 5) . substr($bin, 1, 3));
						$g = bindec(substr($bin, 6, 5) . substr($bin, 6, 3));
						$b = bindec(substr($bin, 11, 5) . substr($bin, 11, 3));
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

		public static function getDevice() {
			$ua = $_SERVER['HTTP_USER_AGENT'];
			if(preg_match('/iPhone/', $ua) || preg_match('/iPod/', $ua) || preg_match('/Android/', $ua) &&
				preg_match('/Mobile/', $ua)) {
				return 'sp';
			}
			else if(preg_match('/iPad/', $ua) || preg_match('/Android/', $ua)) {
				return 'tab';
			}
			else {
				return 'pc';
			}
		}

		 public static function fork($cmd, $async=true) {
			try {
				if(substr(PHP_OS, 0, 3) === 'WIN') {
					if($async) {
						$cmdline = "start $cmd 2>&1";
					}
					else {
						$cmdline = "$cmd 2>&1";
					}
					$p = popen($cmdline, 'r');
					if($p) {
						pclose($p);
					}
					else {
						trigger_error('popen error', E_USER_ERROR);
					}
				}
				else {
					if($async) $sync = '&';
					$cmdline = "$cmd > /dev/null $sync";
					putenv('PATH=' . getenv('PATH'));
					exec("$cmdline");
				}
			}
			catch(Exception $e) {
				return false;
			}
		}

		 public static function fgetcsv(&$handle, $length=null, $d=',', $e='"') {
			$d = preg_quote($d);
			$e = preg_quote($e);
			$_line = '';

			while($eof != true) {
				$_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
				$itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
				if($itemcnt % 2 == 0) $eof = true;
			}

			$_csv_line = preg_replace('/(?:\\r\\n|[\\r\\n])?$/', $d, trim($_line));
			$_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
			preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
			$_csv_data = $_csv_matches[1];
			for($_csv_i=0; $_csv_i < count($_csv_data); $_csv_i++) {
				$_csv_data[$_csv_i] = preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);
				$_csv_data[$_csv_i] = str_replace($e.$e, $e, $_csv_data[$_csv_i]);
			}
			return empty($_line) ? false : $_csv_data;
		}
	}
