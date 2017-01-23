<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class db_install extends B_Module {
		function __construct() {
			parent::__construct(__FILE__);
		}

		function install() {
			try {
				$this->createTables();
				$this->createViews();
				$this->insertVersion();
				$this->insertCurrentVersion();
				$this->insertSettings();
			}
			catch (Exception $e) {
				$this->error_message = '<p class="error-message">' . $e->getMessage() . '</p>';
				$this->error_message.= '<p class="error-message">' . $this->db->getErrorMsg() . '</p>';
				return false;
			}

			return true;
		}

		function createTables() {
			$this->createTable('contents_node');
			$this->createTable('contents');
			$this->createTable('template_node');
			$this->createTable('template');
			$this->createTable('widget_node');
			$this->createTable('widget');
			$this->createTable('resource_node');
			$this->createTable('settings');
			$this->createTable('version');
			$this->createTable('current_version');
			$this->createTable('compare_version');
			$this->createTable('article');
			$this->createTable('article2');
			$this->createTable('article3');
			$this->createTable('category');
			$this->createTable('category2');
			$this->createTable('category3');
			$this->createTable('user');
		}

		function createTable($table_name) {
			$table = new B_Table($this->db, $table_name);
			$status = $table->create();
			if(!$status) {
				throw new Exception(__('Faild to create table.'));
			}
		}

		function createViews() {
			$this->createView('cre_v_current_version.sql');

			$this->createView('cre_v_c_contents.sql');
			$this->createView('cre_v_c_contents_node.sql');
			$this->createView('cre_v_c_resource_node.sql');
			$this->createView('cre_v_c_template.sql');
			$this->createView('cre_v_c_template_node.sql');
			$this->createView('cre_v_c_widget.sql');
			$this->createView('cre_v_c_widget_node.sql');

			$this->createView('cre_v_w_contents.sql');
			$this->createView('cre_v_w_contents_node.sql');
			$this->createView('cre_v_w_resource_node.sql');
			$this->createView('cre_v_w_template.sql');
			$this->createView('cre_v_w_template_node.sql');
			$this->createView('cre_v_w_widget.sql');
			$this->createView('cre_v_w_widget_node.sql');

			$this->createView('cre_v_compare_contents_node.sql');
			$this->createView('cre_v_compare_resource_node.sql');
			$this->createView('cre_v_compare_template_node.sql');
			$this->createView('cre_v_compare_widget_node.sql');

			$this->createView('cre_v_category.sql');
			$this->createView('cre_v_category2.sql');
			$this->createView('cre_v_category3.sql');
			$this->createView('cre_v_article.sql');
			$this->createView('cre_v_article2.sql');
			$this->createView('cre_v_article3.sql');
			$this->createView('cre_v_preview_article.sql');
			$this->createView('cre_v_preview_article2.sql');
			$this->createView('cre_v_preview_article3.sql');
			$this->createView('cre_v_admin_article.sql');
			$this->createView('cre_v_admin_article2.sql');
			$this->createView('cre_v_admin_article3.sql');
		}

		function createView($view_name) {
			$sql = file_get_contents('./sql/' . $view_name);
			$sql = str_replace('%DB_PREFIX%', B_DB_PREFIX, $sql);
			$status = $this->db->query($sql);
			if(!$status) {
				throw new Exception(__('Faild to create view.'));
			}
		}

		function insertVersion() {
			$version = new B_Table($this->db, 'version');

			$param['version_id'] = '00001';
			$param['version'] = 'V1.0';
			$param['memo'] = '初期バージョン';
			$param['private_revision_id'] = '00';
			$param['publication_datetime_u'] = time();
			$param['publication_datetime_t'] = date('Y/m/d H:i', $param['publication_datetime_u']);
			$param['create_user'] = 'installer';
			$param['create_datetime'] = time();
			$param['del_flag'] = '0';

			$status = $version->insert($param);
			if(!$status) {
				throw new Exception(__('Faild to create version record.'));
			}
		}

		function insertCurrentVersion() {
			$current_version = new B_Table($this->db, 'current_version');

			$param['id'] = '0000000001';
			$param['current_version_id'] = '00001';
			$param['reserved_version_id'] = '00001';
			$param['working_version_id'] = '00001';
			$param['create_datetime'] = time();
			$param['create_user'] = 'installer';

			$status = $current_version->insert($param);
			if(!$status) {
				throw new Exception(__('Faild to create current version record.'));
			}
		}

		function insertSettings() {
			$settings = new B_Table($this->db, 'settings');

			$param['id'] = '00001';
			$param['admin_site_title'] = 'B-studio Contents Management System';

			$status = $settings->insert($param);
			if(!$status) {
				throw new Exception(__('Faild to create configuration record.'));
			}
		}

		function getErrorMessage() {
			return $this->error_message;
		}
	}
