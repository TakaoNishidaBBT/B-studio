<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$tree_config = array(
	'id'			=> 'tree',
	'start_html'	=> '<div id="tree" class="bframe_tree bframe_adjustparent select_category" param="margin:24" unselectable="on">',
	'end_html'		=> '</div>',
	'script'		=>
	array(
		'bframe_tree'	=>
		array(
			'module'		=> $this->module,
			'file'			=> 'tree',
			'editable'		=> 'true',
			'sort'			=> 'manual',
			'root_name'		=> _('Category'),
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
				'property'		=> 'property',
			),
			'ondblclick'	=>
			array(
				'script'	=> 'setCategory',
			),
			'relation'	=>
			array(
				'open_property'	=>
				array(
					'url'		=> DISPATCH_URL . '&module=' . $this->module . '&page=property&method=select',
					'params'	=> 'width:360,height:330',
					'title'		=> _('Property'),
					'func'		=> 'reloadTree',
				),
			),

			'icon'		=>
			array(
				'plus'			=> array('src' => './images/folders/plus.gif', 'new' => ''),
				'minus'			=> array('src' => './images/folders/minus.gif', 'new' => ''),
				'blank'			=> array('src' => './images/folders/blank.gif', 'new' => ''),
				'root'			=> array('src' => './images/folders/category_root.png', 'new' => ''),
				'trash'			=> array('src' => './images/folders/trash.png', 'new' => ''),
				'forbidden'		=> array('src' => './images/folders/forbidden.png'),
				'line'			=> array('src' => './images/folders/line.gif', 'new' => ''),
				'folder'		=> array('src' => './images/folders/folder.png', 'new' => _('newFolder')),
				'category'		=> array('src' => './images/folders/category.png', 'new' => _('newCategory'), 'ime' => 'true'),
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
					'menu'		=> _('Delete'),
					'func'		=> 'deleteNode',
					'confirm'	=> _('Are you sure to delete?'),
				),
				array(
					'menu'		=> _('New'),
					'func'		=> 'createNode',
					'submenu'	=>
					array(
						array(
							'menu'		=> _('Category'),
							'func'		=> 'createNode',
							'icon'		=> 'images/folders/category.png',
							'param'		=> 'node_type=category&node_class=category',
						),
					),
					'submenu_width'	=> '120',
				),
				array(
					'menu'		=> _('Edit name'),
					'func'		=> 'editName',
				),
				array(
					'menu'		=> _('Property'),
					'func'		=> 'open_property',
				),
			),
			'context_menu_width'	=> '138',
			'context_menu_frame'	=> 'top',
			'session_timeout'		=> _('Your session has timed out, Please log in again'),
		),
	),
);
