<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$tree_config = array(
	'id'			=> 'tree',
	'start_html'	=> '<div class="bframe_tree bframe_adjustparent select_widget" param="margin:32" id="tree" unselectable="on">',
	'end_html'		=> '</div>',
	'script'		=>
	array(
		'bframe_tree'	=>
		array(
			'opener'		=> $this->opener,
			'module'		=> $this->module,
			'file'			=> 'select_tree',
			'editable'		=> 'false',
			'root_name'		=> 'root',
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
			'onclick'	=>
			array(
				'script'	=> 'setWidget',
			),

			'icon'		=>
			array(
				'plus'			=> array('src' => './images/folders/plus.gif', 'new' => ''),
				'minus'			=> array('src' => './images/folders/minus.gif', 'new' => ''),
				'blank'			=> array('src' => './images/folders/blank.gif', 'new' => ''),
				'root'			=> array('src' => './images/folders/widget_root.png', 'new' => ''),
				'trash'			=> array('src' => './images/folders/trash.png', 'new' => ''),
				'forbidden'		=> array('src' => './images/folders/forbidden.png'),
				'forbidden_big'	=> array('src' => './images/folders/forbidden_big.png'),
				'line'			=> array('src' => './images/folders/line.gif', 'new' => ''),
				'folder'		=> array('src' => './images/folders/folder.png', 'new' => 'newFolder'),
				'folder_open'	=> array('src' => './images/folders/folder_open.png', 'new' => 'newFolder'),
				'widget'		=> array('src' => './images/folders/widget.png', 'new' => 'newWidget'),
			),
			'session_timeout'		=> '�Z�b�V�������؂�܂����B���O�C�����Ȃ����Ă��������B',
		),
	),
);
