<?php
/*
 * B-studio : Content Management System
 * Copyright (c) BigBeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$tree_config = array(
	'id'			=> 'tree',
	'start_html'	=> '<div id="tree" class="bframe_tree bframe_adjustparent" param="margin:20" unselectable="on">',
	'end_html'		=> '</div>',
	'script'		=>
	array(
		'bframe_tree'	=>
		array(
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
				'script'	=> 'setTemplate',
			),

			'icon'		=>
			array(
				'plus'			=> array('src' => './images/folders/plus.gif'),
				'minus'			=> array('src' => './images/folders/minus.gif'),
				'blank'			=> array('src' => './images/folders/blank.gif'),
				'root'			=> array('src' => './images/folders/template_root.png'),
				'trash'			=> array('src' => './images/folders/trash.png'),
				'forbidden'		=> array('src' => './images/folders/forbidden.png'),
				'forbidden_big'	=> array('src' => './images/folders/forbidden_big.png'),
				'line'			=> array('src' => './images/folders/line.gif'),
				'folder'		=> array('src' => './images/folders/folder.png'),
				'folder_open'	=> array('src' => './images/folders/folder_open.png'),
				'template'		=> array('src' => './images/folders/template.png'),
			),
			'context_menu'		=>
			array(
				array(
					'menu'		=> '切り取り',
					'func'		=> 'cutNode',
				),
				array(
					'menu'		=> 'コピー',
					'func'		=> 'copyNode',
				),
				array(
					'menu'		=> '貼り付け',
					'func'		=> 'pasteNode',
				),
				array(
					'menu'		=> 'エイリアスとして貼り付け',
					'func'		=> 'pasteAriasNode',
				),
				array(
					'menu'		=> '削除',
					'func'		=> 'deleteNode',
					'confirm'	=>
					array(
						'normal'	=> '削除します。よろしいですか？',
						'complete'	=> '完全に削除します。よろしいですか？',
					),
				),
				array(
					'menu'		=> '新規',
					'func'		=> 'createNode',
					'submenu'	=>
					array(
						array(
							'menu'		=> 'テンプレート',
							'func'		=> 'createNode',
							'icon'		=> './images/folders/template.png',
							'param'		=> 'node_type=template&node_class=template',
						),
					),
					'submenu_width'	=> '120',
				),
				array(
					'menu'		=> '名前の変更',
					'func'		=> 'editName',
				),
			),
			'context_menu_width'	=> '138',
			'context_menu_frame'	=> 'top',
			'session_timeout'		=> 'セッションが切れました。ログインしなおしてください。',
		),
	),
);
