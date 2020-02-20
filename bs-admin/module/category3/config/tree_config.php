<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$tree_config = array(
	'id'			=> 'tree',
	'start_html'	=> '<div id="tree" class="bframe_tree bframe_adjustparent select_category bframe_scroll" data-param="margin:6" unselectable="on">',
	'end_html'		=> '</div>',
	'script'		=>
	array(
		'bframe_tree'	=>
		array(
			'module'		=> $this->module,
			'file'			=> 'tree',
			'editable'		=> 'true',
			'selectable'	=> 'true',
			'sort'			=> 'manual',
			'root_name'		=> __('Category'),
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
					'params'	=> 'width:360,height:380',
					'title'		=> __('Properties'),
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
				'folder'		=> array('src' => './images/folders/folder.png', 'new' => __('newFolder')),
				'category'		=> array('src' => './images/folders/category.png', 'new' => __('newCategory'), 'ime' => 'true'),
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
					'confirm'	=> __('Are you sure you want to delete?'),
				),
				array(
					'menu'		=> __('New'),
					'func'		=> 'createNode',
					'submenu'	=>
					array(
						array(
							'menu'		=> __('Category'),
							'func'		=> 'createNode',
							'icon'		=> 'images/folders/category.png',
							'param'		=> 'node_type=category&node_class=category',
						),
					),
					'submenu_width'	=> '120',
				),
				array(
					'menu'		=> __('Rename'),
					'func'		=> 'editName',
				),
				array(
					'menu'		=> __('Properties'),
					'func'		=> 'open_property',
				),
			),
			'context_menu_width'	=> '138',
			'context_menu_frame'	=> 'top',
		),
	),
);
