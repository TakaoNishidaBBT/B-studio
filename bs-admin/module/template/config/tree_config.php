<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$tree_config = array(
	'id'			=> 'tree',
	'start_html'	=> '<div id="tree" class="bframe_tree" unselectable="on">',
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
					'menu'		=> _('Cut'),
					'func'		=> 'cutNode',
				),
				array(
					'menu'		=> _('Copy'),
					'func'		=> 'copyNode',
				),
				array(
					'menu'		=> _('Paste'),
					'func'		=> 'pasteNode',
				),
				array(
					'menu'		=> _('Remove'),
					'func'		=> 'deleteNode',
					'confirm'	=>
					array(
						'single'	=> _('Are you sure you want %NODE_NAME% to move to the trash?'),
						'plural'	=> _('Are you sure you want these %NODE_COUNT% objects to move to the trash?'),
					),
				),
				array(
					'menu'		=> _('New'),
					'func'		=> 'createNode',
					'submenu'	=>
					array(
						array(
							'menu'		=> _('Template'),
							'func'		=> 'createNode',
							'icon'		=> './images/folders/template.png',
							'param'		=> 'node_type=template&node_class=template',
						),
					),
					'submenu_width'	=> '120',
				),
				array(
					'menu'		=> _('Edit name'),
					'func'		=> 'editName',
				),
			),
			'trash_context_menu'		=>
			array(
				array(
					'menu'		=> _('Empty the trash'),
					'func'		=> 'truncateNode',
					'confirm'	=> _('Are you sure you completely remove files in the trah?'),
				),
			),
			'context_menu_width'	=> '138',
			'context_menu_frame'	=> 'top',
			'session_timeout'		=> _('Your session has timed out, Please log in again'),
		),
	),
);
