<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$tree_config = array(
	'id'			=> 'tree',
	'start_html'	=> '<div id="tree" class="bframe_tree">',
	'end_html'		=> '</div>',
	'script'		=>
	array(
		'bframe_tree'	=>
		array(
			'module'		=> $this->module,
			'file'			=> 'tree',
			'editable'		=> 'true',
			'sort'			=> 'manual',
			'root_name'		=> 'root',
			'trash_name'	=> 'trash box',
			'method'		=>
			array(
				'getNodeList'	=> 'getNodeList',
				'pasteNode'		=> 'pasteNode',
				'pasteAriasNode'=> 'pasteNode',
				'deleteNode'	=> 'deleteNode',
				'truncateNode'	=> 'truncateNode',
				'closeNode'		=> 'closeNode',
				'createNode'	=> 'createNode',
				'updateDispSeq'	=> 'updateDispSeq',
				'saveName'		=> 'saveName',
			),
			'relation'	=>
			array(
				'selectNode'	=>
				array(
					'url'		=> DISPATCH_URL . '&module=' . $this->module . '&page=form&method=select',
					'frame'		=> 'template_form',
				),
				'truncateNode'	=>
				array(
					'url'		=> DISPATCH_URL . '&module=' . $this->module . '&page=form&method=truncate',
					'frame'		=> 'template_form',
				),
			),
			'icon'		=>
			array(
				'plus'			=> array('src' => './images/folders/plus.gif'),
				'minus'			=> array('src' => './images/folders/minus.gif'),
				'blank'			=> array('src' => './images/folders/blank.gif'),
				'root'			=> array('src' => './images/folders/template_root.png'),
				'trash'			=> array('src' => './images/folders/trash.png'),
				'forbidden'		=> array('src' => './images/folders/forbidden.png'),
				'line'			=> array('src' => './images/folders/line.gif'),
				'template'		=> array('src' => './images/folders/template.png', 'new' => 'newTemplate'),
			),
			'context_menu'		=>
			array(
				array(
					'menu'		=> __('Cut'),
					'func'		=> 'cutNode',
				),
				array(
					'menu'		=> __('Copy'),
					'func'		=> 'copyNode',
				),
				array(
					'menu'		=> __('Paste'),
					'func'		=> 'pasteNode',
				),
				array(
					'menu'		=> __('Delete'),
					'func'		=> 'deleteNode',
					'confirm'	=>
					array(
						'single'	=> __('Are you sure you want to move %NODE_NAME% to the trash?'),
						'plural'	=> __('Are you sure you want to move these %NODE_COUNT% objects to the trash?'),
					),
				),
				array(
					'menu'		=> __('New'),
					'func'		=> 'createNode',
					'submenu'	=>
					array(
						array(
							'menu'		=> __('Template'),
							'func'		=> 'createNode',
							'icon'		=> './images/folders/template.png',
							'param'		=> 'node_type=template&node_class=template',
						),
					),
					'submenu_width'	=> '120',
				),
				array(
					'menu'		=> __('Rename'),
					'func'		=> 'editName',
				),
			),
			'trash_context_menu'		=>
			array(
				array(
					'menu'		=> __('Empty the trash'),
					'func'		=> 'truncateNode',
					'confirm'	=> __('Are you sure you want to permanently remove the items in the trash?'),

				),
			),
			'context_menu_width'	=> '138',
			'context_menu_frame'	=> 'top',
			'session_timeout'		=> __('Your session has timed out, Please log in again'),
		),
	),
);
