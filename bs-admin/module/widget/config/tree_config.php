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
					'frame'		=> 'widget_form',
				),
				'truncateNode'	=>
				array(
					'url'		=> DISPATCH_URL . '&module=' . $this->module . '&page=form&method=truncate',
					'frame'		=> 'widget_form',
				),
			),
			'icon'		=>
			array(
				'plus'			=> array('src' => './images/folders/plus.gif'),
				'minus'			=> array('src' => './images/folders/minus.gif'),
				'blank'			=> array('src' => './images/folders/blank.gif'),
				'root'			=> array('src' => './images/folders/widget_root.png'),
				'trash'			=> array('src' => './images/folders/trash.png'),
				'forbidden'		=> array('src' => './images/folders/forbidden.png'),
				'line'			=> array('src' => './images/folders/line.gif'),
				'folder'		=> array('src' => './images/folders/folder.png', 'new' => 'newFolder'),
				'folder_open'	=> array('src' => './images/folders/folder_open.png'),
				'widget'		=> array('src' => './images/folders/widget.png', 'new' => 'newWidget'),
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
							'menu'		=> _('Foler'),
							'func'		=> 'createNode',
							'icon'		=> './images/folders/folder.png',
							'param'		=> 'node_type=folder&node_class=folder',
						),
						array(
							'menu'		=> _('Widget'),
							'func'		=> 'createNode',
							'icon'		=> './images/folders/widget.png',
							'param'		=> 'node_type=widget&node_class=leaf',
						),
					),
					'submenu_width'	=> '120',
				),
				array(
					'menu'		=> _('Edit name'),
					'func'		=> 'editName',
				),
			),
			'trash_context_menu'	=>
			array(
				array(
					'menu'		=> _('Empty the trash'),
					'func'		=> 'truncateNode',
					'confirm'	=> _('Are you sure you completely remove files in the trah?'),
				),
			),
			'context_menu_width'	=> '138',
			'context_menu_frame'	=> 'top.main',
			'session_timeout'		=> _('Your session has timed out, Please log in again'),
		),
	),
);
