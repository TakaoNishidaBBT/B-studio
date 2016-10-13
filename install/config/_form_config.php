<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$language = array(
	'en'	=> _('English'),
	'ja'	=> _('Japanese'),
);

$select_language_config = array(
	array('class' => 'B_Hidden', 'name' => 'select_language', 'value' => 1),
	array(
		'start_html'	=> '<label for="language">',
		'end_html'		=> '</label>',
		'value'			=> _('Select language: '),
	),
	array(
		'name'			=> 'language',
		'class'			=> 'B_Selectbox',
		'data_set'		=> $language,
		'local'			=> true,
		'value'			=> 'en',
		'special_html'	=> 'class="bframe_selectbox" onchange=submit()',
	),
);

$db_install_form_config = array(
	array(
		// Table
		'start_html'	=> '<table class="form"><tbody>',
		'end_html'		=> '</tbody></table>',

		// Host name
		array(
			'error_group'	=> 'true',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> _('Host name'),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'db_srv',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'status'				=> true,
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> _('Please enter host name'),
						),
						array(
							'type' 			=> 'status',
							'error_message'	=> _('Please confirm the input content'),
						),
					),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<span class="error-message">',
					'end_html'				=> '</span>',
				),
			),
		),

		// User name
		array(
			'error_group'	=> 'true',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> _('User name'),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'db_usr',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'status'				=> true,
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> _('Please enter user name'),
						),
						array(
							'type' 			=> 'status',
							'error_message'	=> _('Please confirm the input content'),
						),
					),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<span class="error-message">',
					'end_html'				=> '</span>',
				),
			),
		),

		// Password
		array(
			'error_group'	=> 'true',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> _('Password'),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'db_pwd',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'status'				=> true,
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> _('Please enter password'),
						),
						array(
							'type' 			=> 'status',
							'error_message'	=> _('Please confirm the input content'),
						),
					),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<span class="error-message">',
					'end_html'				=> '</span>',
				),
			),
		),

		// Schema name
		array(
			'error_group'	=> 'true',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> _('Schema name'),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'db_nme',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'status'				=> true,
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> _('Please enter schema name'),
						),
						array(
							'type' 			=> 'status',
							'error_message'	=> _('Please confirm the input content'),
						),
					),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<span class="error-message">',
					'end_html'				=> '</span>',
				),
			),
		),

		// Table prefix
		array(
			'error_group'	=> 'true',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th class="prefix">',
				'invalid_start_html'	=> '<th class="prefix error">',
				'end_html'				=> '</th>',
				'value'					=> _('Table prefix'),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'db_prefix',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox prefix ime-off" maxlength="100" ',
					'value'					=> 'bs_',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> _('Please enter table prefix'),
						),
					),
				),
				array(
					'start_html'			=> '<span class="notice">',
					'end_html'				=> '</span>',
					'class'					=> 'B_Guidance',
					'value'					=> _('Usually changing this field is unnecessary. This field could be changed when B-studio will be installed in one schema.'),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<p class="error-message">',
					'end_html'				=> '</p>',
				),
			),
		),
	),
);
$admin_basic_auth_config = array(
	array(
		// Table
		'start_html'	=> '<table class="form"><tbody>',
		'end_html'		=> '</tbody></table>',

		// User name
		array(
			'error_group'	=> 'true',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> _('User name'),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'basic_auth_id',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> _('Please enter user name'),
						),
						array(
							'type' 			=> 'pattern',
							'pattern'		=> '^[a-zA-Z0-9\_\-]+$',
							'error_message'	=> _('Please enter user name using alphanumeric, hyphen(-) and underbar(_)'),
						),
					),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<span class="error-message">',
					'end_html'				=> '</span>',
				),
			),
		),

		// Password
		array(
			'error_group'	=> 'true',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> _('Password'),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'basic_auth_pwd',
					'class'					=> 'B_Password',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'confirm_message'		=> _('(Entered password)'),
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> _('Please enter password'),
						),
						array(
							'type' 			=> 'pattern',
							'pattern'		=> '^[a-zA-Z0-9\_\-]+$',
							'error_message'	=> _('Please enter password using alphanumeric, hyphen(-) and underbar(_)'),
						),
					),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<span class="error-message">',
					'end_html'				=> '</span>',
				),
			),
		),

		// Password (Re-entry)
		array(
			'error_group'	=> 'true',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			'confirm_mode'	=> 'none',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> _('Password (Re-entry)'),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'basic_auth_pwd2',
					'class'					=> 'B_Password',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> _('Please re-enter password'),
						),
						array(
							'type' 			=> 'match',
							'target'		=> 'basic_auth_pwd',
							'error_message'	=> _('Password is not matched'),
						),
					),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<span class="error-message">',
					'end_html'				=> '</span>',
				),
			),
		),
	),
);

$admin_user_form_config = array(
	array(
		// Table
		'start_html'	=> '<table class="form"><tbody>',
		'end_html'		=> '</tbody></table>',

		// User name
		array(
			'error_group'	=> 'true',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> _('User name'),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'admin_user_name',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-on" size="40" maxlength="100" ',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> _('Please enter user name'),
						),
					),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<span class="error-message">',
					'end_html'				=> '</span>',
				),
			),
		),

		// Login ID
		array(
			'error_group'	=> 'true',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> _('Login ID'),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'admin_user_id',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> _('Please enter login ID'),
						),
						array(
							'type' 			=> 'pattern',
							'pattern'		=> '^[a-zA-Z0-9\_\-]+$',
							'error_message'	=> _('Please enter login ID using alphanumeric, hyphen(-) and underbar(_)'),
						),
					),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<span class="error-message">',
					'end_html'				=> '</span>',
				),
			),
		),

		// Password
		array(
			'error_group'	=> 'true',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> _('Password'),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'admin_user_pwd',
					'class'					=> 'B_Password',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'confirm_message'		=> _('(Entered password)'),
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> _('Please enter password'),
						),
						array(
							'type' 			=> 'pattern',
							'pattern'		=> '^[a-zA-Z0-9\_\-]+$',
							'error_message'	=> _('Please enter password using alphanumeric, hyphen(-) and underbar(_)'),
						),
					),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<span class="error-message">',
					'end_html'				=> '</span>',
				),
			),
		),

		// Password (Re-entry)
		array(
			'error_group'	=> 'true',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			'confirm_mode'	=> 'none',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> _('Password (Re-entry)'),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'admin_user_pwd2',
					'class'					=> 'B_Password',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> _('Please re-enter password'),
						),
						array(
							'type' 			=> 'match',
							'target'		=> 'admin_user_pwd',
							'error_message'	=> _('Password is not matched'),
						),
					),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<span class="error-message">',
					'end_html'				=> '</span>',
				),
			),
		),
	),
);

$root_htaccess_config = array(
	array(
		'name'			=> 'htaccess',
		'class'			=> 'B_TextArea',
		'special_html'	=> 'class="htaccess"',
	),
);
