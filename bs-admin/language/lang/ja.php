<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/

	$texts = array(
		// Common
		'(Test)'						=> '【テスト】',

		'1 line'						=> '1件',
		'10 lines'						=> '10件',
		'20 lines'						=> '20件',
		'50 lines'						=> '50件',
		'100 lines'						=> '100件',

		'Pages:'						=> 'ページ ：',
		'Total'							=> '全',
		'records'						=> '件中',
		'from'							=> '',
		'to'							=> '〜',

		'User'							=> 'ユーザー',
		'Log out'						=> 'ログアウト',

		'Publish version'				=> '公開バージョン',
		'Working version'				=> '作業中バージョン',
		'Compare versions'				=> 'バージョン比較',

		'Cut'							=> '切り取り',
		'Copy'							=> 'コピー',
		'Paste'							=> '貼り付け',
		'Remove'						=> '削除',
		'New'							=> '新規',
		'Edit name'						=> '名前の変更',
		'Empty the trash'				=> 'ゴミ箱を空にする',
		'Folder'						=> 'フォルダ',
		'Page'							=> 'ページ',

		'Are you sure you want %NODE_NAME% to move to the trash?'	
										=> '%NODE_NAME%をゴミ箱に移動します。よろしいですか？',
		'Are you sure you want these %NODE_COUNT% objects to move to the trash?'
										=> 'これら%NODE_COUNT%をゴミ箱に移動します。よろしいですか？',
		'Are you sure you completely remove files in the trah?'
										=> '完全に削除します。よろしいですか？',
		'Your session has timed out, Please log in again'
										=> 'セッションが切れました。ログインしなおしてください。',
		"Other user updated this record\nAre you sure to overwrite?"
										=> "他のユーザに更新されています。\n上書きしますか？",
		'Saved'							=> '保存しました',
		'It failed to save'				=> '保存に失敗しました',
		'Already exists'				=> '既に存在しています',
		'Please enter name'				=> '名前を入力してください。',
		'Multi byte character can not be used'
										=> '日本語は使用できません',
		'This name can not be used. Because this name already exists. Please enter the other name.'
										=> '名前を変更できません。指定されたファイル名は既に存在します。別の名前を指定してください。',
		'Extension is necessary'		=> '拡張子が必要です。',
		'Followed extensions can not be used (css swf jpg jpeg gif png)'
										=> '次の拡張子は使用できません css swf jpg jpeg gif png',
		'Followed charcters can not be used for file name and folder name (\ / : * ? " < > | space)'
										=> 'ファイル名／フォルダ名に次の文字は使えません \ / : * ? " < > | スペース',
		'Displaying %RECORD_FROM% to %RECORD_TO% of %TOTAL% items'
										=> ' 全%TOTAL%件中　%RECORD_FROM%～%RECORD_TO%件目',
		// Error message for date and time 
		' (out of range)'				=> '（範囲外）',
		' (invalid time)'				=> '（時刻が不正）',
		' (invalid date)'				=> '（日付が不正）',
		' (format error)'				=> '（フォーマットが不正）',

		// Error message for Node 
		'DB error'						=> 'DBエラー',
		'The folder you copy to or move to is subfloder'
										=> '受け側のフォルダは送り側のフォルダのサブフォルダです',
		'The template you copy to or move to is subtemplate'
										=> '受け側のテンプレートは送り側のサブテンプレートです',
		'The number of nodes are differnt. Please sort in right pane.'
										=> 'ノードの数が違っています(右側のフォルダペインでソートしてください)',
		'The number of nodes are different'
										=> 'ノードの数が違っています',

		// Form
		'*'								=> '※',
		' is required field'			=> '：必須項目',
		'was saved.'					=> 'を保存しました。',
		'was faild to saved.'			=> 'の保存に失敗しました。',
		'was registered.'				=> 'を登録しました。',
		'was faild to register.'		=> 'の登録に失敗しました。',
		'was updated.'					=> 'を更新しました。',
		'was failed to update.'			=> 'の更新に失敗しました。',
		'was set.'						=> 'を設定しました。',
		'was faild to set.'				=> 'の設定に失敗しました。',
		'was deleted.'					=> 'を削除しました。',
		'was faild to delete.'			=> 'の削除に失敗しました。',

		'Other user updated this record'
										=> "他のユーザに更新されています",
		// Menu
		'Open publish page'				=> '公開画面を開きます',
		'Contents'						=> 'コンテンツ',
		'Templates'						=> 'テンプレート',
		'Resources'						=> 'リソース',
		'Resource Manager'				=> 'リソース管理',
		'Widget'						=> 'ウィジェット',
		'Posts'							=> '投稿',
		'Article'						=> '記事',
		'Article2'						=> '記事2',
		'Article3'						=> '記事3',
		'File manager'					=> 'ファイル管理',
		'Settings'						=> '各種設定',
		'Configuration'					=> '基本設定',
		'Versions'						=> 'バージョン管理',
		'Users'							=> 'ユーザ管理',
		'Site admin'					=> 'サイト管理者設定',
		'Open another admin page'		=> '管理画面をもう一枚開きます',

		// Buttons
		'Confirm'						=> '確認',
		'Back'							=> '戻る',
		'Save'							=> '保存',

		// Contents
		'HTML'							=> 'HTML',
		'WYSWYG'						=> 'ビジュアル',
		'CSS'							=> 'CSS',
		'PHP'							=> 'PHP',
		'Settings'						=> '設定',
		'Preview'						=> 'プレビュー',
		'Title'							=> 'タイトル',
		'Template'						=> 'テンプレート',
		'Breadcrumbs'					=> 'パンくず',
		'Keywords'						=> 'Keywords',
		'Description'					=> 'Description',
		'External css'					=> '外部css',
		'External javascript'			=> '外部javascript',
		'Header elements'				=> 'ヘッダー要素',

		// Resource
		'Upload'						=> 'アップロード',
		'File name'						=> 'ファイル名',
		'Modified'						=> '更新日時',
		'File size'						=> 'ファイルサイズ',
		'Resolution'					=> 'イメージサイズ',

		// Article
		'Category: '					=> 'カテゴリ：',
		'Category'						=> 'カテゴリ',
		'Date'							=> '日付',
		'Date: '						=> '日付：',
		'Title'							=> 'タイトル',
		'Title: '						=> 'タイトル：',
		'Open/Preview/Close'			=> '公開／プレビュー／非公開',
		'Published'						=> '公開',
		'Preview'						=> 'プレビュー',
		'Closed'						=> '非公開',
		'Publication date'				=> '掲載日',
		'Please enter publication date'	=> '掲載日を入力してください',
		'Please enter title'			=> 'タイトルを入力してください',
		'Title image'					=> 'タイトル画像',
		'Display detail'				=> '詳細表示',
		'On'							=> 'あり',
		'Off'							=> 'なし',
		'External link'					=> '外部リンク',
		'If you choose external link on, please enter URL'
										=> '「外部リンクあり」を選択した場合は、URLを入力してください',
		'Another window'				=> '別ウインドウ',
		'Detail'						=> '詳細',
		'Are you sure to delete?'		=> '削除します。\n\nよろしいですか',
		'Property'						=> 'プロパティー',
		'newCategory'					=> '新しいカテゴリ',
		'newFolder'						=> '新しいフォルダ',
		'Property'						=> 'プロパティー',
		'Are you sure to delete?'		=> '削除します。よろしいですか？',

		// file manager
		'Are you sure you want %NODE_NAME% to delete?'
										=> '%NODE_NAME%を削除します。よろしいですか？',
		'Are you sure you want these %NODE_COUNT% objects to delete?'
										=> 'これら%NODE_COUNT%個の項目を削除します。よろしいですか？',
		'Please use server browser'		=> 'サーバブラウザを使用してください',

		// Version
		'Keyword'						=> 'キーワード',
		'Keyword: '						=> 'キーワード：',
		'Search condition'				=> '検索条件',
		'No record was found'			=> '該当レコードはありません',
		'Display'						=> '表示',
		'Search'						=> '検索',
		'Clear'							=> 'クリア',
		'Create'						=> '新規作成',
		'lines display'					=> '件目',
		'Publish'						=> '公開',
		'Working'						=> '作業中',
		'ID'							=> 'ID',
		'Publish date time'				=> '公開日時',
		'Status'						=> '状態',
		'Status ■:Published  ★:Scheduled to be published'
										=> '状態　■：公開　★：公開予約',
		'Notes'							=> 'メモ',
		'Change versions'				=> 'バージョン変更',
		'Edit'							=> '編集',
		'Diff'							=> '比較',
		'Delete'						=> '削除',
		'Register'						=> '設定',
		'Back to list'					=> '一覧に戻る',
		'Publish date and time'			=> '公開日時',
		'Please enter correct date and time'
										=> '正しい日時を入力してください',
		'Please enter publish date and time'
										=> '公開日時を入力してください。',
		'Format: YYYY/MM/DD hh:mm'		=> 'YYYY/MM/DD hh:mmの形式',
		'ex) 2020/01/01 12:00'			=> '例）2020/01/01 12:00',
		'Version name'					=> 'バージョン（名称）',
		'Please enter version name'		=> 'バージョン（名称）を入力してください',
		'All the contents you made on this version will be completely deleted.\nThis operation can not be undone.\n\nAre your sure to delete?'		
										=> 'このバージョンで作成したコンテンツすべてを削除します。\nこの作業は元に戻せません\n\nよろしいですか？',
		'Version: '						=> 'バージョン：',
		'Publish date and time: '		=> '　公開日時：',
		'Version: '						=> 'バージョン：',
		'This version can not be deleted. Because it\'s not ths latest version.'
										=> '最新バージョンではありませんので削除できません',
		'Working version can not be deleted.'
										=> '作業中バージョンなので削除できません',
		'Published version can not be deleted.'
										=> '公開バージョンなので削除できません',
		'Please set versions.'			=> 'バージョンを選択してください',
		'If you set scheduled to be published this version, you must set current published version'
										=> '（このバージョンを予約登録するにはそれまでに公開されるバージョンを設定してから再度、予約登録する必要があります）',
		'Publish version: '				=> '公開バージョン：',
		'Working version: '				=> '作業中バージョン：',
		'will be registerd.'			=> 'に設定します。',
		'published immediately'			=> '即時反映',
		'Scheduled to be published'		=> '予約登録',
		'will be registerd.'			=> 'に設定します。',

		// Users
		'Name'							=> '名前',
		'User ID'						=> 'ユーザID',
		'User ID: '						=> 'ユーザID：',
		'Password'						=> 'パスワード',
		'Authority'						=> '権限',
		'Enabled'						=> '有効',
		'Disabled'						=> '無効',
		'Admin'							=> '管理者',
		'Posts'							=> '投稿者',
		'Please enter User ID'			=> 'ユーザIDを入力してください',
		'Please enter User ID using alphanumeric, hyphen(-) and underbar(_)'
										=> 'ユーザIDは英数とハイフン(-)アンダーバー(_)で入力してください',
		'This ID is already exists'		=> '既に登録されています',
		'This ID can not be used'		=> 'そのIDは登録できません',
		'Please enter password'			=> 'パスワードを入力してください',
		'Please set user privilege'		=> 'ユーザ権限を選択してください',
		'Please enter a name'			=> '氏名を入力してください',
		'English'						=> '英語',
		'Japanese'						=> '日本語',
		'Chinese'						=> '中国語',

		// Site admin
		'User name'						=> 'ユーザ名',
		'Login ID'						=> 'ログインID',
		'Password (Re-entry)'			=> 'パスワード（再入力）',
		'(Password you set)'			=> '（設定されたパスワード）',
		'If you change password, please enter password. If you don\'t, keep this field empty'			
										=> 'パスワードを変更する場合は新しいパスワードを入力し、変更しない場合は空のままにしておいてください',
		'For confirmation, please re-enter password'
										=> '確認のため、パスワードを再入力してください',
		'Please enter user name'		=> 'ユーザ名を入力してください',
		'Please enter login ID'			=> 'ログインIDを入力してください',
		'Please enter login ID using alphanumeric, hyphen(-) and underbar(_)'
										=> 'ログインIDは英数とハイフン(-)アンダーバー(_)で入力してください',
		'Password is not matched'		=> 'パスワードが一致していません',
		'Back to site admin form'		=> '設定画面に戻る',
		'Configuration of site admin was updated'
									=> 'サイト管理者の情報を更新しました',

		// Configuration
		'Admin page title'				=> '管理画面タイトル',
		'Language'						=> '言語',
		'DB backup'						=> 'DBバックアップ',
		'Full backup'					=> 'FULLバックアップ',
		'Re-install backup'				=> '再インストール用バックアップ',
		'Download'						=> 'ダウンロード',
		'Back to configuration form'	=> '基本設定に戻る',
		'Settings: Saved'				=> '基本設定：保存しました。',
		'Settings: Failed'				=> '基本設定：保存に失敗しました。',

		// install
		'Select language: '				=> '言語を選択してください：',
		'Host name'						=> 'ホスト名',
		'Please enter host name'		=> 'ホスト名を入力してください',
		'Please confirm the input content'
										=> '入力内容を確認してください',
		'User name'						=> 'ユーザ名',
		'Please enter user name'		=> 'ユーザー名を入力してください',
		'Please enter user name using alphanumeric, hyphen(-) and underbar(_)'
										=> 'ユーザ名は英数とハイフン(-)アンダーバー(_)で入力してください',
		'Please enter password'			=> 'パスワードを入力してください',
		'Please enter password using alphanumeric, hyphen(-) and underbar(_)'
										=> 'パスワードは英数とハイフン(-)アンダーバー(_)で入力してください',
		'Password'						=> 'パスワード',
		'Schema name'					=> 'データベース名',
		'Please enter schema name'		=> 'データベース名を入力してください',
		'Table prefix'					=> 'テーブル・プリフィックス',
		'Please enter table prefix'		=> 'テーブル・プリフィックスを入力してください',
		'Usually changing this field is unnecessary. This field could be changed when B-studio will be installed in one schema.' 		
										=> '※通常この項目を変更する必要はありません。ひとつのDBにB-studioを複数インストールする場合は変更してください。',
		'Please re-enter password'		=> 'パスワードを再入力してください',
		'Password is not matched'		=> 'パスワードが一致していません',
		'(Entered password)'			=> '（設定されたパスワード）',

		'Please enable mbstring module'	=> 'mbstringモジュールを有効にしてください。',
		'Please set session.save_path'	=> 'session.save_pathを設定してください。',
		'Please enable MySQL library'	=> 'MySQLライブラリを有効にしてください。',
		'Please enable GD library'		=> 'GDライブラリを有効にしてください。',
		'ZipArchive is necessary'		=> 'ZipArchiveクラスが必要です。',
		'Please enable GD library'		=> 'GDライブラリを有効にしてください。',

		' : write permission is OK. '	=> ' の書き込み権限はOKです。',
		' : write permission is not set. '
										=> ' に書き込み権限がありません。',

		'Connecting to the DB is OK. But failed to select the schema'
										=> 'DBへ接続はできましたがスキーマの選択に失敗しました。',
		'Faild to connect DB.'			=> 'DBへの接続に失敗しました。',
		'This is an error in your entry<br />Please check any error message and re-enter the necessary information'
										=> '入力内容に誤りがあります。<br />各欄のエラーメッセージをご覧の上、入力し直してください。',

		'Faild to create a table.'		=> 'テーブルの作成に失敗しました。',
		'Faild to create a view.'			=> 'ビューの作成に失敗しました。',
		'Faild to insert a version record.'
										=> 'バージョンレコードの作成に失敗しました。',
		'Faild to insert current version record.'
										=> 'カレントバージョンレコードの作成に失敗しました。',
		'Faild to insert a configuration record.'
										=> '基本設定レコードの作成に失敗しました。',
	);
