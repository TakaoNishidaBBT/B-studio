<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$form_config = array(
	array(
		// Table
		'start_html'	=> '<table class="form" border="0" cellspacing="0" cellpadding="0"><tbody>',
		'end_html'		=> '</tbody></table>',
		'db_table'		=> 'contents',

		// Admin page title
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'		=> '<th>',
				'end_html'			=> '</th>',
				'value'				=> _('Admin page title'),
			),
			array(
				'class'				=> 'B_InputText',
				'name'				=> 'admin_site_title',
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				'special_html'		=> 'class="textbox ime_on" size="100" maxlength="100" ',
			),
	    ),

		// Language
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'		=> '<th>',
				'end_html'			=> '</th>',
				'value'				=> _('Language'),
			),
			array(
				'class'				=> 'B_SelectBox',
				'name'				=> 'language',
				'data_set'			=> 'language',
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				'value'				=> LANG,
				'special_html'		=> 'class="bframe_selectbox"',
			),
	    ),

		// DB backup
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'		=> '<th>',
				'end_html'			=> '</th>',
				'value'				=> _('DB backup'),
			),
			array(
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				array(
					'name'				=> 'backup',
					'start_html'		=> '<span class="download-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'backupDB\', \'\', true)" >',
					'end_html'			=> '</span>',
					'value'				=> '<img src="images/common/download.png" alt="' . _('Download') . '" />' . _('Download'),
				),
			),
	    ),

		// FULL backup
		array(
			'name'			=> 'full_backup',
			'display'		=> 'none',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'		=> '<th>',
				'end_html'			=> '</th>',
				'value'				=> _('Full backup'),
			),
			array(
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				array(
					'name'				=> 'backup',
					'start_html'		=> '<span class="download-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'backupAll\', \'\', true)" >',
					'end_html'			=> '</span>',
					'value'				=> '<img src="images/common/download.png" alt="' . _('Download') . '" />' . _('Download'),
				),
			),
	    ),

		// Full backup for re-install
		array(
			'name'			=> 'full_backup2',
			'display'		=> 'none',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'		=> '<th>',
				'end_html'			=> '</th>',
				'value'				=> _('Re-install backup'),
			),
			array(
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				array(
					'name'				=> 'backup',
					'start_html'		=> '<span class="download-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'backupAll\', \'install\', true)" >',
					'end_html'			=> '</span>',
					'value'				=> '<img src="images/common/download.png" alt="' . _('Download') . '" />' . _('Download'),
				),
			),
	    ),
	),
);

//config
$result_config = array(
	array(
		'start_html'	=> '<form name="F1" method="post" action="index.php">',
		'end_html'		=> '</form>',
		array(
			'name'			=> 'action_message',
		),
	),
);

//control
$input_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'confirm',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="right-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'confirm\', \'\', true)">',
			'end_html'		=> '</span>',
			'value'			=> _('Confirm'),
		),
	),
);

//confirm control
$confirm_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'back',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="left-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'back\', \'\')">',
			'end_html'		=> '</span>',
			'value'			=> _('Back'),
		),
	),
	array(
		'name'			=> 'register',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="right-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'register\', \'\')">',
			'end_html'		=> '</span>',
			'value'			=> _('Save'),
		),
	),

);

//result
$result_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'backToList',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="left-button" style="width:180px" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'\', \'\')">',
			'end_html'		=> '</span>',
			'value'			=> _('Back to configuration form'),
		),
	),
);
