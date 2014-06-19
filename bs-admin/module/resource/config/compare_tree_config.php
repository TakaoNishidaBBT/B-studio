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
				'pain'			=>	true,
				'selectNode'	=>
				array(
					'url'		=> DISPATCH_URL . '&module=' . $this->module . '&page=compare_pain&method=select',
					'frame'		=> 'resource_form',
				),
			),
			'icon'		=>
			array(
				'plus'						=> array('src' => './images/folders/plus.gif'),
				'minus'						=> array('src' => './images/folders/minus.gif'),
				'blank'						=> array('src' => './images/folders/blank.gif'),
				'root'						=> array('src' => './images/folders/resource_root.png'),
				'trash'						=> array('src' => './images/folders/trash.png'),
				'forbidden'					=> array('src' => './images/folders/forbidden.png'),
				'forbidden_big'				=> array('src' => './images/folders/forbidden_big.png'),
				'line'						=> array('src' => './images/folders/line.gif'),
				'folder'					=> array('src' => './images/folders/folder.png', 'new' => 'newFolder'),
				'folder_open'				=> array('src' => './images/folders/folder_open.png', 'new' => 'newFolder'),
				'folder_diff'				=> array('src' => './images/folders/folder_red.png', 'new' => 'newFolder'),
				'folder_diff_child'			=> array('src' => './images/folders/folder_purple.png', 'new' => 'newFolder'),
				'file'						=> array('src' => './images/folders/file_icon.png', 'new' => 'newFile', 'ime' => 'true'),
				'folder_open_diff'			=> array('src' => './images/folders/folder_open_red.png', 'new' => 'newFolder'),
				'folder_open_diff_child'	=> array('src' => './images/folders/folder_open_purple.png', 'new' => 'newFolder'),
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
				'pain'			=>	true,
				'selectNode'	=>
				array(
					'url'		=> DISPATCH_URL . '&module=' . $this->module . '&page=compare_pain&method=select',
					'frame'		=> 'resource_form',
				),
			),
			'icon'		=>
			array(
				'plus'						=> array('src' => './images/folders/plus.gif'),
				'minus'						=> array('src' => './images/folders/minus.gif'),
				'blank'						=> array('src' => './images/folders/blank.gif'),
				'root'						=> array('src' => './images/folders/resource_root.png'),
				'trash'						=> array('src' => './images/folders/trash.png'),
				'forbidden'					=> array('src' => './images/folders/forbidden.png'),
				'forbidden_big'				=> array('src' => './images/folders/forbidden_big.png'),
				'line'						=> array('src' => './images/folders/line.gif'),
				'folder'					=> array('src' => './images/folders/folder.png', 'new' => 'newFolder'),
				'folder_open'				=> array('src' => './images/folders/folder_open.png', 'new' => 'newFolder'),
				'folder_diff'				=> array('src' => './images/folders/folder_blue.png', 'new' => 'newFolder'),
				'folder_diff_child'			=> array('src' => './images/folders/folder_purple.png', 'new' => 'newFolder'),
				'file'						=> array('src' => './images/folders/file_icon.png', 'new' => 'newFile', 'ime' => 'true'),
				'folder_open_diff'			=> array('src' => './images/folders/folder_open_blue.png', 'new' => 'newFolder'),
				'folder_open_diff_child'	=> array('src' => './images/folders/folder_open_purple.png', 'new' => 'newFolder'),
			),
		),
	),
);
