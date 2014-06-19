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
					'frame'		=> 'template_form',
				),
			),
			'icon'		=>
			array(
				'plus'						=> array('src' => './images/folders/plus.gif', 'new' => ''),
				'minus'						=> array('src' => './images/folders/minus.gif', 'new' => ''),
				'blank'						=> array('src' => './images/folders/blank.gif', 'new' => ''),
				'root'						=> array('src' => './images/folders/template_root.png', 'new' => ''),
				'trash'						=> array('src' => './images/folders/trash.png', 'new' => ''),
				'forbidden'					=> array('src' => './images/folders/forbidden.png'),
				'forbidden_big'				=> array('src' => './images/folders/forbidden_big.png'),
				'line'						=> array('src' => './images/folders/line.gif', 'new' => ''),
				'folder'					=> array('src' => './images/folders/folder.png', 'new' => 'newFolder'),
				'folder_open'				=> array('src' => './images/folders/folder_open.png', 'new' => 'newFolder'),
				'template'					=> array('src' => './images/folders/template.png', 'new' => 'newTemplate'),
				'template_diff'				=> array('src' => './images/folders/template_red.png', 'new' => 'newFolder'),
				'template_diff_child'		=> array('src' => './images/folders/template_purple.png', 'new' => 'newFolder'),
				'template_open_diff'		=> array('src' => './images/folders/template_open_red.png', 'new' => 'newFolder'),
				'template_open_diff_child'	=> array('src' => './images/folders/template_open_purple.png', 'new' => 'newFolder'),
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
					'frame'		=> 'template_form',
				),
			),
			'icon'		=>
			array(
				'plus'						=> array('src' => './images/folders/plus.gif'),
				'minus'						=> array('src' => './images/folders/minus.gif'),
				'blank'						=> array('src' => './images/folders/blank.gif'),
				'root'						=> array('src' => './images/folders/template_root.png'),
				'trash'						=> array('src' => './images/folders/trash.png'),
				'forbidden'					=> array('src' => './images/folders/forbidden.png'),
				'forbidden_big'				=> array('src' => './images/folders/forbidden_big.png'),
				'line'						=> array('src' => './images/folders/line.gif'),
				'folder'					=> array('src' => './images/folders/folder.png', 'new' => 'newFolder'),
				'folder_open'				=> array('src' => './images/folders/folder_open.png', 'new' => 'newFolder'),
				'template'					=> array('src' => './images/folders/template.png', 'new' => 'newTemplate'),
				'template_diff'				=> array('src' => './images/folders/template_blue.png', 'new' => 'newFolder'),
				'template_diff_child'		=> array('src' => './images/folders/template_purple.png', 'new' => 'newFolder'),
				'template_open_diff'		=> array('src' => './images/folders/template_open_blue.png', 'new' => 'newFolder'),
				'template_open_diff_child'	=> array('src' => './images/folders/template_open_purple.png', 'new' => 'newFolder'),
			),
		),
	),
);