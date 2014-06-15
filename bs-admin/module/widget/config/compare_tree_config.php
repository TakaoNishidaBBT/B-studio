<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$left_tree_config = array(
	'id'			=> 'left_tree',
	'start_html'	=> '<div id="left_tree" class="bframe_compare_tree" unselectable="on">',
	'end_html'		=> '</div>',
	'script'		=>
	array(
		'bframe_tree'	=>
		array(
			'module'		=> $this->module,
			'file'			=> 'compare_tree',
			'root_name'		=> 'root',
			'method'		=>
			array(
				'getNodeList'	=> 'getNodeList',
				'openNode'		=> 'openNode',
				'closeNode'		=> 'closeNode',
			),
			'relation'	=>
			array(
				'selectNode'	=>
				array(
					'url'		=> DISPATCH_URL . '&module=' . $this->module . '&page=compare_form&method=select',
					'frame'		=> 'widget_form',
				),
			),
			'icon'		=>
			array(
				'plus'						=> array('src' => './images/folders/plus.gif'),
				'minus'						=> array('src' => './images/folders/minus.gif'),
				'blank'						=> array('src' => './images/folders/blank.gif'),
				'root'						=> array('src' => './images/folders/root.png'),
				'trash'						=> array('src' => './images/folders/trash.png'),
				'forbidden'					=> array('src' => './images/folders/forbidden.png'),
				'forbidden_big'				=> array('src' => './images/folders/forbidden-big.png'),
				'line'						=> array('src' => './images/folders/line.gif'),
				'folder'					=> array('src' => './images/folders/folder.png', 'new' => 'newFolder'),
				'folder_open'				=> array('src' => './images/folders/folder-open.png', 'new' => 'newFolder'),
				'widget'					=> array('src' => './images/folders/widget.png', 'new' => 'newPage'),
				'folder_diff_child'			=> array('src' => './images/folders/folder-purple.png', 'new' => 'newFolder'),
				'folder_diff'				=> array('src' => './images/folders/folder-red.png', 'new' => 'newFolder'),
				'widget_diff'				=> array('src' => './images/folders/widget-red.png', 'new' => 'newPage'),
				'folder_open_diff_child'	=> array('src' => './images/folders/folder-open-purple.png', 'new' => 'newFolder'),
				'folder_open_diff'			=> array('src' => './images/folders/folder-open-red.png', 'new' => 'newFolder'),
			),
		),
	),
);
$right_tree_config = array(
	'id'			=> 'right_tree',
	'start_html'	=> '<div id="right_tree" class="bframe_compare_tree" unselectable="on">',
	'end_html'		=> '</div>',
	'script'		=>
	array(
		'bframe_tree'	=>
		array(
			'module'		=> $this->module,
			'file'			=> 'compare_tree',
			'root_name'		=> 'root',
			'method'		=>
			array(
				'getNodeList'	=> 'getNodeList',
				'openNode'		=> 'openNode',
				'closeNode'		=> 'closeNode',
			),
			'relation'	=>
			array(
				'selectNode'	=>
				array(
					'url'		=> DISPATCH_URL . '&module=' . $this->module . '&page=compare_form&method=select',
					'frame'		=> 'widget_form',
				),
			),
			'icon'		=>
			array(
				'plus'						=> array('src' => './images/folders/plus.gif'),
				'minus'						=> array('src' => './images/folders/minus.gif'),
				'blank'						=> array('src' => './images/folders/blank.gif'),
				'root'						=> array('src' => './images/folders/root.png'),
				'trash'						=> array('src' => './images/folders/trash.png'),
				'forbidden'					=> array('src' => './images/folders/forbidden.png'),
				'forbidden_big'				=> array('src' => './images/folders/forbidden-big.png'),
				'line'						=> array('src' => './images/folders/line.gif'),
				'folder'					=> array('src' => './images/folders/folder.png', 'new' => 'newFolder'),
				'folder_open'				=> array('src' => './images/folders/folder.png', 'new' => 'newFolder'),
				'widget'					=> array('src' => './images/folders/widget.png', 'new' => 'newPage'),
				'folder_diff_child'			=> array('src' => './images/folders/folder-purple.png', 'new' => 'newFolder'),
				'folder_diff'				=> array('src' => './images/folders/folder-blue.png', 'new' => 'newFolder'),
				'widget_diff'				=> array('src' => './images/folders/widget-blue.png', 'new' => 'newPage'),
				'folder_open_diff_child'	=> array('src' => './images/folders/folder-open-purple.png', 'new' => 'newFolder'),
				'folder_open_diff'			=> array('src' => './images/folders/folder-open-blue.png', 'new' => 'newFolder'),
			),
		),
	),
);