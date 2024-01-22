<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class index extends B_CommonModule {
		public $admin_mode;
		public $internal_redirect;
		public $templates;
		public $node_view;
		public $contents_view;
		public $template_node_view;
		public $template_view;
		public $widget_node_view;
		public $widget_view;
		public $view_mode;
		public $contents;
		public $contents_node;
		public $path;
		public $url;
		public $breadcrumbs = array();
		public $start_html;
		public $end_html;
		public $innerHTML;
		public $view_file;
		public $widget_cache;
		public $file_name;
		public $file_extension;
		public $http_status;

		function __construct() {
			parent::__construct(__FILE__);
			global $admin_mode, $internal_redirect;

			$this->admin_mode = $admin_mode;
			$this->internal_redirect = $internal_redirect;
			$this->templates = array();

			if($this->admin_mode) {
				$this->node_view = B_WORKING_CONTENTS_NODE_VIEW;
				$this->contents_view = B_WORKING_CONTENTS_VIEW;
				$this->template_node_view = B_WORKING_TEMPLATE_NODE_VIEW;
				$this->template_view = B_WORKING_TEMPLATE_VIEW;
				$this->widget_node_view = B_WORKING_WIDGET_NODE_VIEW;
				$this->widget_view = B_WORKING_WIDGET_VIEW;

				if(isset($this->post['method']) && ($this->post['method'] == 'preview' || $this->post['method'] == 'template_preview' || $this->post['method'] == 'widget_preview')) {
					$this->view_mode = 'preview';
					return;
				}
				if(isset($this->post['method']) && $this->post['method'] == 'inline') {
					$this->view_mode = 'inline';
					return;
				}
			}
			else {
				$this->node_view = B_CURRENT_CONTENTS_NODE_VIEW;
				$this->contents_view = B_CURRENT_CONTENTS_VIEW;
				$this->template_node_view = B_CURRENT_TEMPLATE_NODE_VIEW;
				$this->template_view = B_CURRENT_TEMPLATE_VIEW;
				$this->widget_node_view = B_CURRENT_WIDGET_NODE_VIEW;
				$this->widget_view = B_CURRENT_WIDGET_VIEW;
			}

			$this->getFileInfo($this->file_name, $this->file_extension);

			switch($this->file_extension) {
			case'css':
				$this->getCSS();
				break;

			default:
				$this->getHtml();
				break;
			}
		}

		function getFileInfo(&$file_name, &$file_extension) {
			$file_name = basename($this->request['url']);
			$i = strrpos($file_name, '.');
			if($i) {
				$file_extension = substr($file_name, $i+1);
				$file_name = substr($file_name, 0, $i);
			}
		}

		function getHtml() {
			$this->url = preg_replace('?/{2,}?', '/', $this->request['url']);	// Remove slashes in succession
			$this->url = preg_replace('?^/?', '', $this->url);					// Remove first slash

			$url_array = explode('/', $this->url);
			$this->contents_node = $this->getContentsNode($url_array);

			if(!$this->contents_node) {
				unset($this->breadcrumbs);		// Unset bread crumbs
				$url_array = explode('/', '404.html');
				$this->contents_node = $this->getContentsNode($url_array);
				$this->http_status = '404';
			}
			if($this->url == '404.html') {
				$this->http_status = '404';
			}

			if($this->contents_node) {
				$this->html = $this->createHTML();
				$this->setTemplateExternalCss();
				$this->setTemplateExternalScript();
				$this->setTemplateHeaderElement();
				$this->setTemplateCssLink();
				$this->setCss($this->contents['external_css']);
				$this->setScript($this->contents['external_js']);
				$this->setHeaderElement($this->contents['header_element']);

				if($this->contents['css']) {
					$this->html_header->appendProperty('css',
						'<link rel="stylesheet" href="C' . $this->contents_node['contents_id'] . '.css">');
				}

				$this->setTitle(htmlspecialchars($this->contents['title'], ENT_QUOTES, B_CHARSET));

				if($this->contents['keywords']) {
					$this->html_header->appendMeta('keywords', $this->contents['keywords']);
				}

				if($this->contents['description']) {
					$this->html_header->appendMeta('description', $this->contents['description']);
				}

				$this->view_file = './view/view_index.php';
			}
			else {
				// Not Found
				$this->notFound();
				exit;
			}
		}

		function getContentsNode($url, $node='', $level=0) {

			// Create bread crumbs
			$this->createBreadCrumbs($node, $level);

			if(count($url)) {
				if($node && $node['node_type'] == 'page') {
					// Permalink
					$param = implode('/', $url);
					$param_array = explode('?', $param);
					$_REQUEST['id'] = $param_array[0];
					return $node;
				}
			}
			else {
				if($node['node_type'] != 'folder') {
					// The page found
					return $node;
				}
				else {
					// Directory and index.html is not exists
					$path = B_CURRENT_ROOT . $this->url . '/';
					header("Location:$path");
					exit;
				}
			}

			$sql = "select * from %VIEW% where parent_node='%PARENT_NODE%' %NODE_NAME% %NODE_STATUS% order by node_name";
			$sql = str_replace('%VIEW%', B_DB_PREFIX . $this->node_view, $sql);
			if($node) {
				$sql = str_replace('%PARENT_NODE%', $this->db->real_escape_string($node['node_id']), $sql);
			}
			else {
				$sql = str_replace('%PARENT_NODE%', 'root', $sql);
			}

			$node_name = array_shift($url);

			if($node_name) {
				$sql = str_replace('%NODE_NAME%', "and node_name='" . $this->db->real_escape_string($node_name) . "'", $sql);
			}
			else {
				$sql = str_replace('%NODE_NAME%', "and node_name in ('index.html', 'index.php') ", $sql);
			}

			if($this->admin_mode || $this->internal_redirect) {
				$sql = str_replace('%NODE_STATUS%', "", $sql);
			}
			else {
				$sql = str_replace('%NODE_STATUS%', "and node_status !='9'", $sql);
			}

			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			if(!$row) {
				return;
			}

			return $this->getContentsNode($url, $row, $level+1);
		}

		function createBreadCrumbs($node, $level) {
			$url_suffix = '';

			if(is_array($node) && ($node['node_name'] == 'index.html' || $node['node_name'] == 'index.htm')) {
				return;
			}

			$sql = "select b.*
					from %CONTENTS_NODE_VIEW% a
					left join %CONTENTS_VIEW% b
					on a.contents_id = b.contents_id
					where %NODE_CONDITION%
					%PAGE_CONDITION%";

			$sql = str_replace('%CONTENTS_NODE_VIEW%', B_DB_PREFIX . $this->node_view, $sql);
			$sql = str_replace('%CONTENTS_VIEW%', B_DB_PREFIX . $this->contents_view, $sql);

			if($node) {
				if($node['node_type'] == 'folder') {
					$sql = str_replace('%NODE_CONDITION%', "a.parent_node = '" . $this->db->real_escape_string($node['node_id']) . "'", $sql);
					$sql = str_replace('%PAGE_CONDITION%', " and a.node_name in ('index.html', 'index.htm')", $sql);
					$url_suffix = '/';
				}
				else {
					$sql = str_replace('%NODE_CONDITION%', "a.node_id = '" . $this->db->real_escape_string($node['node_id']) . "'", $sql);
					$sql = str_replace('%PAGE_CONDITION%', "", $sql);
				}
			}
			else {
				$sql = str_replace('%NODE_CONDITION%', "a.parent_node = 'root'", $sql);
				$sql = str_replace('%PAGE_CONDITION%', " and a.node_name in ('index.html', 'index.htm')", $sql);
				$url_suffix = '/';
			}

			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			if($level == 0) {
				$this->breadcrumbs[$level]['url'] = B_CURRENT_ROOT;
			}
			else {
				$this->breadcrumbs[$level]['url'] = $this->breadcrumbs[$level-1]['url'] . $node['node_name'] . $url_suffix;
			}
		}

		function createHTML() {
			// Get contents from DB
			$this->contents = $this->getContents($this->contents_node['contents_id'], $this->contents_view);
			$this->innerHTML = isset($this->contents['html1']) ? $this->contents['html1'] : '';

			// Get templates array
			if(isset($this->contents['template_id']) && $this->contents['template_id']) {
				$this->getTemplates($this->contents['template_id']);
			}

			if(is_array($this->templates)) {
				for($i=0; $i < count($this->templates); $i++) {
					$this->start_html.= $this->templates[$i]['start_html'];
					$this->end_html = $this->templates[$i]['end_html'] . $this->end_html;
				}
			}

			return $this->start_html . $this->innerHTML . $this->end_html;
		}

		function getTemplates($template_node_id) {
			$sql_org = "select a.node_id
							  ,a.parent_node
							  ,a.node_type
							  ,a.node_class
							  ,a.contents_id
							  ,b.contents_date
							  ,b.start_html
							  ,b.end_html
							  ,b.css
							  ,b.php
							  ,b.external_css
							  ,b.external_js
							  ,b.header_element
						from %TEMPLATE_NODE_VIEW% a
						left join %TEMPLATE_VIEW%  b
						on a.contents_id = b.contents_id
						where a.node_id='%NODE_ID%'";

			$sql_org = str_replace('%TEMPLATE_NODE_VIEW%', B_DB_PREFIX . $this->template_node_view, $sql_org);
			$sql_org = str_replace('%TEMPLATE_VIEW%', B_DB_PREFIX . $this->template_view, $sql_org);

			for($i=0, $node_id = $template_node_id; $node_id && $node_id != 'root'; $node_id = $row['parent_node'], $i++) {
				$sql = str_replace('%NODE_ID%', $node_id, $sql_org);

				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);

				$this->templates[] = $row;
			}
			if(is_array($this->templates)) $this->templates = array_reverse($this->templates);
		}

		function setTemplateCssLink() {
			if(is_array($this->templates)) {
				for($i=0; $i < count($this->templates); $i++) {
					if($this->templates[$i]['css']) {
						$this->html_header->appendProperty('css',
							'<link rel="stylesheet" href="T' . $this->templates[$i]['contents_id'] . '.css">');
					}
				}
			}
		}

		function setTemplateExternalCss() {
			if(is_array($this->templates)) {
				for($i=0; $i < count($this->templates); $i++) {
					$this->setCss($this->templates[$i]['external_css']);
				}
			}
		}

		function setCss($css) {
			if(!$css) return;
			$this->html_header->appendProperty('css', $css);
		}

		function setTemplateExternalScript() {
			if(is_array($this->templates)) {
				for($i=0; $i < count($this->templates); $i++) {
					$this->setScript($this->templates[$i]['external_js']);
				}
			}
		}

		function setScript($script) {
			if(!$script) return;
			$this->html_header->appendProperty('script', $script);
		}

		function setTemplateHeaderElement() {
			if(is_array($this->templates)) {
				for($i=0; $i < count($this->templates); $i++) {
					$this->setHeaderElement($this->templates[$i]['header_element']);
				}
			}
		}

		function setHeaderElement($element) {
			$element_array = explode("\n", $element);
			if(is_array($element_array)) {
				foreach($element_array as $value) {
					$value = rtrim($value);
					if($value != '') {
						$this->html_header->appendProperty('misc', $value);
					}
				}
			}
		}

		function getContents($contents_id, $table) {
			$sql = "select * from " . B_DB_PREFIX . $table . " where contents_id='" . $this->db->real_escape_string($contents_id) . "'";
			$rs = $this->db->query($sql);
			$contents = $this->db->fetch_assoc($rs);

			return $contents;
		}

		function getCSS() {
			$prefix = substr($this->file_name, 0, 1);
			$file_name = substr($this->file_name, 1);

			switch($prefix) {
			case 'C':
				$row = $this->getContents($file_name, $this->contents_view);
				break;

			case 'T':
				$row = $this->getContents($file_name, $this->template_view);
				break;

			case 'W':
				$row = $this->getContents($file_name, $this->widget_view);
				break;

			default:
				header('HTTP/1.0 404 Not Found');
				break;
			}

			if($row['css']) {
				header('Cache-Control: no-cache, no-store, must-revalidate');
				header('Content-Type: text/css; charset=' . B_CHARSET);
				echo $row['css'];
			}
			exit;
		}

		function inline() {
			global $admin_language;
			if(!$this->admin_mode) return;

			$this->contents = $this->post;

			$this->getContentsNodeForPreview($this->contents['node_id']);

			for($i=count($this->contents_node) - 1, $level=0; $i >= 0; $i--, $level++) {
				$this->createBreadCrumbs($this->contents_node[$i], $level);
			}

			$this->getTemplates($this->contents['template_id']);

			if(is_array($this->templates)) {
				for($i=0; $i < count($this->templates); $i++) {
					$this->start_html.= $this->templates[$i]['start_html'];
					$this->end_html = $this->templates[$i]['end_html'] . $this->end_html;
				}
			}
			$inline_param = 'filebrowser:bs-admin/index.php?terminal_id=' . $this->post['terminal_id'] . '&amp;module=resource&amp;page=popup';
			$inline_param.= ',language:' . $admin_language;
			$inline_param.= ',templates:' . B_CURRENT_ROOT . 'inlineeditor/templates/default.js';

			$this->innerHTML = '<div id="inline_editor" class="bframe_inlineeditor" data-param="' . $inline_param . '"';
			$this->innerHTML.= ' style="outline:none">' . $this->contents['html1'] . '</div>';
			$this->setTemplateExternalCss();
			$this->setTemplateCssLink();
			$this->setCss($this->contents['external_css']);

			if($this->contents['css']) {
				$this->html_header->appendProperty('css', '<style>' . $this->contents['css'] . '</style>');
			}

			$this->html_header->appendProperty('script', '<script src="' . B_ADMIN_ROOT . 'js/bframe.js"></script>');
			$this->html_header->appendProperty('script', '<script src="' . B_ADMIN_ROOT . 'js/ckeditor/ckeditor.js"></script>');
			$this->html_header->appendProperty('script', '<script src="' . B_ADMIN_ROOT . 'js/bframe_inlineeditor.js"></script>');

			$this->setTitle(htmlspecialchars($this->contents['title'], ENT_QUOTES, B_CHARSET));

			if($this->contents['keywords']) {
				$this->html_header->appendMeta('keywords', $this->contents['keywords']);
			}

			if($this->contents['description']) {
				$this->html_header->appendMeta('description', $this->contents['description']);
			}

			$this->view_file = './view/view_inline.php';

			ini_set('display_errors','On');
		}

		function preview() {
			$level = 0;

			if(!$this->admin_mode) return;

			$this->contents = $this->post;

			$this->getContentsNodeForPreview($this->contents['node_id']);

			for($i=count($this->contents_node), $level=0; $i >= 0; $i--, $level++) {
				$contents_node = isset($this->contents_node[$i]) ? $this->contents_node[$i] : '';
				$this->createBreadCrumbs($contents_node, $level);
			}

			$this->getTemplates($this->contents['template_id']);

			if(is_array($this->templates)) {
				for($i=0; $i < count($this->templates); $i++) {
					$this->start_html.= $this->templates[$i]['start_html'];
					$this->end_html = $this->templates[$i]['end_html'] . $this->end_html;
				}
			}

			$this->innerHTML = $this->contents['html1'];
			$this->setTemplateExternalCss();
			$this->setTemplateExternalScript();
			$this->setTemplateHeaderElement();
			$this->setTemplateCssLink();
			$this->setCss($this->contents['external_css']);
			$this->setScript($this->contents['external_js']);
			$this->setHeaderElement($this->contents['header_element']);

			if($this->contents['css']) {
				$this->html_header->appendProperty('css', '<style>' . $this->contents['css'] . '</style>');
			}

			$this->setTitle(htmlspecialchars($this->contents['title'], ENT_QUOTES, B_CHARSET));

			if($this->contents['keywords']) {
				$this->html_header->appendMeta('keywords', $this->contents['keywords']);
			}

			if($this->contents['description']) {
				$this->html_header->appendMeta('description', $this->contents['description']);
			}

			$this->html_header->appendProperty('script', '<script src="bs-admin/js/bframe.js"></script>');
			$this->html_header->appendProperty('script', '<script src="bs-admin/js/bframe_device_emulator.js"></script>');
			$this->view_file = './view/view_index.php';

			ini_set('display_errors','On');
		}

		function getContentsNodeForPreview($contents_node_id) {
			$sql_org = "select a.node_id
							  ,a.parent_node
							  ,a.node_name
							  ,a.node_type
							  ,a.node_class
							  ,a.contents_id
						from %CONETNTS_NODE_VIEW% a
						where a.node_id='%NODE_ID%'";

			$sql_org = str_replace('%CONETNTS_NODE_VIEW%', B_DB_PREFIX . $this->node_view, $sql_org);

			for($i=0, $node_id = $this->db->real_escape_string($contents_node_id); $node_id && $node_id != 'root'; $node_id = $row['parent_node'], $i++) {
				$sql = str_replace('%NODE_ID%', $node_id, $sql_org);

				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);

				$this->contents_node[] = $row;
				if($row['node_type'] == 'folder') {
					$this->path = $row['node_name'] . '/' . $this->path;
				}
				else {
					$this->path = $row['node_name'];
				}
			}
			$this->url = $this->path;
		}

		function template_preview() {
			$start_html = '';
			$end_html = '';

			if(!$this->admin_mode) return;

			$this->contents = $this->post;

			$sql = "select parent_node from %TEMPLATE_NODE_VIEW% where node_id = '%NODE_ID%'";
			$sql = str_replace('%TEMPLATE_NODE_VIEW%', B_DB_PREFIX . $this->template_node_view, $sql);
			$sql = str_replace('%NODE_ID%', $this->db->real_escape_string($this->post['node_id']), $sql);
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			$this->getTemplates($row['parent_node']);

			for($i=0; $i < count($this->templates); $i++) {
				$start_html.= $this->templates[$i]['start_html'];
				$end_html = $this->templates[$i]['end_html'] . $end_html;
			}

			$this->innerHTML = $start_html . $this->post['start_html'] . $this->post['end_html'] . $end_html;
			$this->setTemplateExternalScript();
			$this->setTemplateExternalCss();
			$this->setTemplateCssLink();
			$this->setScript($this->post['external_js']);
			$this->setCss($this->post['external_css']);

			if($this->post['css']) {
				$this->html_header->appendProperty('css', '<style>' . $this->post['css'] . '</style>');
			}

			$this->view_file = './view/view_index.php';

			ini_set('display_errors','On');
		}

		function widget_preview() {
			if(!$this->admin_mode) return;

			$this->contents = $this->post;
			$this->innerHTML = $this->post['html'];

			if($this->post['css']) {
				$this->html_header->appendProperty('css', '<style>' . $this->post['css'] . '</style>');
			}

			$this->view_file = './view/view_index.php';

			ini_set('display_errors','On');
		}

		function widget($node_id, $args) {
			if(isset($this->widget_cache[$node_id]) && $this->widget_cache[$node_id]) {
				// retrun result from buffer cache
				echo $this->widget_cache[$node_id];
				return;
			}

			$node_id = $this->db->real_escape_string($node_id);

			$sql = "select a.node_id
						  ,a.contents_id
						  ,b.html
						  ,b.css
						  ,b.php
					from %WIDGET_NODE_VIEW% a
						,%WIDGET_VIEW%  b
					where a.contents_id = b.contents_id and a.node_id='$node_id'";

			$sql = str_replace('%WIDGET_NODE_VIEW%', B_DB_PREFIX . $this->widget_node_view, $sql);
			$sql = str_replace('%WIDGET_VIEW%', B_DB_PREFIX . $this->widget_view, $sql);

			$rs = $this->db->query($sql);
			$__row = $this->db->fetch_assoc($rs);

			if($__row['css']) {
				$this->html_header->appendProperty('css',
					'<link rel="stylesheet" href="W' . $__row['contents_id'] . '.css">');
			}

			// Get buffer
			$contents_before = ob_get_clean();

			// Start buffering
			ob_start();

			widgetExec($this->view_mode, './view/view_widget.php', $__row, $this->url, $this->breadcrumbs);

			// Get buffer
			$contents_widget = ob_get_clean();

			// save result on buffer cache
			$this->widget_cache[$node_id] = $contents_widget;

			// Start buffering again
			ob_start();
			echo $contents_before;
			echo $contents_widget;
		}

		function view() {
			// Start buffering
			ob_start();

			view($this->view_mode
				,$this->view_file
				,$this->templates
				,$this->contents
				,$this->start_html
				,$this->innerHTML
				,$this->end_html
				,$this->breadcrumbs
				,$this->url
				,$this->html_header);

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			header('Cache-Control: no-cache, no-store, must-revalidate');
			header('Content-Type: text/html; charset=' . B_CHARSET);

			if($this->http_status == '404') {
				header('HTTP/1.1 404 Not Found');
			}
			else if($this->admin_mode && ($this->view_mode == 'preview' || $this->view_mode == 'inline')) {
				// for preview and visual editor (google map in chrome)
				header('X-XSS-Protection: 0');
			}
			else {
				header('X-XSS-Protection: 1; mode=block');
			}

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}

		function notFound() {
			// Send HTTP header
			header('Cache-Control: no-cache, no-store, must-revalidate');
			header('Content-Type: text/html; charset=' . B_CHARSET);
			header('HTTP/1.1 404 Not Found');

			$this->showHtmlHeader();
			include('./view/view_not_found.php');
		}
	}

	function view($__view_mode
				 ,$__view_file
				 ,$__templates
				 ,$__contents
				 ,$__start_html
				 ,$__innerHTML
				 ,$__end_html
				 ,&$bs_breadcrumbs
				 ,&$bs_url
				 ,&$bs_html_header) {
		global $admin_mode;

		$bs_admin_mode = $admin_mode;
		$bs_view_mode = $__view_mode;
		$__archive = new B_Log(B_ARCHIVE_LOG_FILE);
		$bs_db = new B_DBaccess($__archive);
		$bs_db->connect(B_DB_SRV, B_DB_USR, B_DB_PWD, B_DB_CHARSET);
		$bs_db->select_db(B_DB_NME);

		include($__view_file);
		if($bs_view_mode == 'preview' && $bs_db->getErrorNo() != '0') {
			echo $bs_db->getErrorMsg();
		}
	}

	function widgetExec($__view_mode
					   ,$__view_file
					   ,$__row
					   ,&$bs_url
					   ,&$bs_breadcrumbs) {
		global $admin_mode;

		$bs_admin_mode = $admin_mode;
		$bs_view_mode = $__view_mode;
		$__archive = new B_Log(B_ARCHIVE_LOG_FILE);
		$bs_db = new B_DBaccess($__archive);
		$bs_db->connect(B_DB_SRV, B_DB_USR, B_DB_PWD, B_DB_CHARSET);
		$bs_db->select_db(B_DB_NME);

		include($__view_file);
		if($__view_mode == 'preview' && $bs_db->getErrorNo() != '0') {
			echo $bs_db->getErrorMsg();
		}
	}

	function widget($node_id) {
		$args = func_get_args();
		$obj = $GLOBALS['current_obj'];
		$obj->widget($node_id, $args);
	}

	function notfound() {
		redirectTo('404.html');
	}

	function redirectTo($url) {
		global $internal_redirect;

		$internal_redirect = true;

		$_REQUEST['url'] = $url;
		unset($_POST['method']);
		chdir(B_DOC_ROOT . B_CURRENT_ROOT);
		require('./bs-controller/controller.php');
		exit;
	}
