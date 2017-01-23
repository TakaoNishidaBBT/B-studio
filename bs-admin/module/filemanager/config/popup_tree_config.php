<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
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
			'sort'			=> 'auto',
			'root_name'		=> 'root',
			'trash_name'	=> 'trash box',
			'root_path'		=> B_UPLOAD_URL,
			'root_url'		=> B_UPLOAD_FILES,
			'thumb_path'	=> B_UPLOAD_URL,
			'thumb_prefix'	=> B_THUMB_PREFIX,
			'target'		=> $this->target,
			'target_id'		=> $this->target_id,
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
				'download'		=> 'download',
			),
			'relation'	=>
			array(
				'pane'			=>
				array(
					'id'		=> 'bframe_pane',
				),
				'insertFile'	=>
				array(
					'node_type'	=> 'file',
				),
				'download'		=>
				array(
					'url'		=> DISPATCH_URL . '&module=' . $this->module . '&page=tree&method=download',
				),
				'preview'		=>
				array(
					'url'		=> DISPATCH_URL . '&module=' . $this->module . '&page=tree&method=preview',
				),
			),
			'key'			=>
			array(
				'delete'	=> true,
			),
			'upload'		=>
			array(
				'button'		=> 'upload_button',
				'file'			=> 'upload_file',
				'module'		=> $this->module,
				'page'			=> 'upload',
			),
			'display_mode'	=>
			array(
				'thumbnail'	=>
				array(
					'id'		=> 'display_thumbnail',
				),
				'detail'		=>
				array(
					'id'		=> 'display_detail',
				),
				'default'		=> $this->session['display_mode'],
			),
			'detail'	=>
			array(
				'header'	=>
				array(
					array(
						'name'			=> 'file_name',
						'title'			=> __('File Name'),
						'className'		=> 'file-name',
						'sort_key'		=> 'file_name',
					),
					array(
						'name'			=> 'update_datetime_t',
						'title'			=> __('Modified'),
						'className'		=> 'update-time',
						'sort_key'		=> 'update_datetime',
					),
					array(
						'name'			=> 'human_file_size',
						'title'			=> __('File size'),
						'className'		=> 'file-size',
						'sort_key'		=> 'file_size',
					),
					array(
						'name'			=> 'human_image_size',
						'title'			=> __('Image size'),
						'className'		=> 'image-size',
						'sort_key'		=> 'image_size',
					),
				),
				'sort_key'	=> $this->session['sort_key'],
			),
			'icon'		=>
			array(
				'plus'			=> array('src' => './images/folders/plus.gif'),
				'minus'			=> array('src' => './images/folders/minus.gif'),
				'blank'			=> array('src' => './images/folders/blank.gif'),
				'root'			=> array('src' => './images/folders/file_root.png'),
				'forbidden'		=> array('src' => './images/folders/forbidden.png'),
				'forbidden_big'	=> array('src' => './images/folders/forbidden_big.png'),
				'line'			=> array('src' => './images/folders/line.gif'),
				'folder'		=> array('src' => './images/folders/folder.png'),
				'folder_open'	=> array('src' => './images/folders/folder_open.png'),
				'page'			=> array('src' => './images/folders/leaf.gif'),
				'node'			=> array('src' => './images/folders/book.gif'),
				'file'			=> array('src' => './images/folders/file_icon.png'),
				'pane'		=>
				array(
					'folder'	=> array('src' => './images/folders/folder_big.png'),
					'js'		=> array('src' => './images/folders/file_icon_big.png'),
					'swf'		=> array('src' => './images/folders/file_icon_big.png'),
					'pdf'		=> array('src' => './images/folders/file_icon_big.png'),
					'css'		=> array('src' => './images/folders/file_icon_big.png'),
					'misc'		=> array('src' => './images/folders/file_icon_big.png'),
				),
				'detail'	=>
				array(
					'folder'	=> array('src' => './images/folders/folder.png'),
					'pdf'		=> array('src' => './images/folders/file_icon.png'),
					'css'		=> array('src' => './images/folders/file_icon.png'),
					'js'		=> array('src' => './images/folders/file_icon.png'),
					'zip'		=> array('src' => './images/folders/file_icon.png'),
					'swf'		=> array('src' => './images/folders/file_icon.png'),
					'mov'		=> array('src' => './images/folders/file_icon.png'),
					'avi'		=> array('src' => './images/folders/file_icon.png'),
					'jpg'		=> array('src' => './images/folders/file_icon.png'),
					'gif'		=> array('src' => './images/folders/file_icon.png'),
					'png'		=> array('src' => './images/folders/file_icon.png'),
					'misc'		=> array('src' => './images/folders/file_icon.png'),
					'upload'	=> array('src' => './images/folders/file_upload_icon.png'),
				),
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
						'single'	=> __('Are you sure you want to delete %NODE_NAME%?'),
						'plural'	=> __('Are you sure you want to delete these %NODE_COUNT% objects?'),
					),
				),
				array(
					'menu'		=> __('New'),
					'func'		=> 'createNode',
					'submenu'	=>
					array(
						array(
							'menu'		=> __('Folder'),
							'func'		=> 'createNode',
							'icon'		=> './images/folders/folder.png',
							'param'		=> 'node_type=folder&node_class=folder',
						),
						array(
							'menu'		=> __('File'),
							'func'		=> 'createNode',
							'icon'		=> './images/folders/file_icon.png',
							'param'		=> 'node_type=file&node_class=leaf',
						),
					),
					'submenu_width'	=> '120',
				),
				array(
					'menu'		=> __('Rename'),
					'func'		=> 'editName',
				),
				array(
					'menu'		=> __('Download'),
					'func'		=> 'download',
				),
				array(
					'menu'		=> __('Preview'),
					'func'		=> 'preview',
				),
			),
			'progress_id'				=> 'progressDialog',
			'progress_icon'				=> 'images/common/process.png',
			'copy_progress_id'			=> 'copyDialog',
			'copy_progress_icon'		=> 'images/common/process.png',
			'download_progress_id'		=> 'truncateDialog',
			'download_progress_icon'	=> 'images/common/process.png',
			'complete_icon'				=> 'images/common/complete.png',
			'context_menu_width'		=> '138',
			'context_menu_frame'		=> 'top',
			'abbr'						=> '…',
		),
	),
);
