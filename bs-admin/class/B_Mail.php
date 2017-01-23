<?php
/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_Mail
	// 
	// -------------------------------------------------------------------------
	class B_Mail {
		function __construct($config) {
			$this->config = $config;
			$this->elements = $config;
		}

		function setValue($values) {
			$this->elements = $this->config;
			$this->replaceText($values);
			$this->setHeader();
		}

		function replaceText($values) {
			foreach(array_keys($this->elements) as $key) {
				foreach($values as $key2 => $value2) {
					$replace_string = '%' . $key2 . '%';
					$this->elements[$key] = str_replace($replace_string, $value2, $this->elements[$key]);
				}
				if($key == 'subject') {
					$this->elements[$key] = B_TITLE_PREFIX . $this->elements[$key];
				}
			}
		}

		function getElementValue($name) {
			return $this->elements[$name];
		}

		function preview($config) {
			$mail_form = new B_Element($config);
			$mail_form->setValue($this->elements);
			return $mail_form->getHtml();
		}

		function setHeader() {
			$from = mb_encode_mimeheader($this->elements['from_name']) . '<' . $this->elements['from_addr']. '>';
			$this->err_mail = '-f ' . $this->elements['from_addr'];

			$header = 'Return-path:' . $from . "\r\n";
			if($this->elements['cc']) {
				$header.= 'Cc:' . $this->elements['cc'] . "\r\n";
			}
			if($this->elements['bcc']) {
				$header.= 'Bcc:' . $this->elements['bcc'] . "\r\n";
			}
			$header.= 'From:' . $from . "\r\n";
			$header.= 'Reply-To:' . $from . "\r\n";
			$header.= 'X-Mailer:system' . "\r\n";

			$this->header = $header;
		}

		function send() {
			$ret = true;

			if(!preg_match('/localhost/', B_HTTP_HOST)) {
				$ret =  mb_send_mail($this->elements['to_addr']
									,$this->elements['subject']
									,$this->replaceLFcode($this->elements['body'])
									,$this->replaceLFcode($this->header)
									,$this->err_mail);
			}
			$this->mail_log = new B_Log(B_MAIL_LOG_FILE);
			$this->mail_log->write(
				'to_addr:' . $this->elements['to_addr'] . "\n" .
				'from_addr:' . $this->elements['from_addr'] . "\n" .
				'subject:' . $this->elements['subject'] . "\n" .
				'body:' . $this->replaceLFcode($this->elements['body']) . "\n" .
				'mail header:' . $this->replaceLFcode($this->header) . "\n" .
				'mail header(decode):' . mb_decode_mimeheader($this->header) . "\n" .
				'err_mail:' . $this->err_mail . "\n"
			);

			return $ret;
		}

		function replaceLFcode($str) {
			$str = str_replace("\r\n", "\n", $str);
			$str = str_replace("\r", "\n", $str);

			return $str;
		}
	}
