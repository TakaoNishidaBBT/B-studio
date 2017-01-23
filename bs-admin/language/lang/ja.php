<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/

	$texts = array(
		// Common
		'None'							=> 'なし',
		'(Test)'						=> '【テスト】',
		'Yes'							=> 'はい',
		'Extract all'					=> 'すべて展開',
		'Overwrite all'					=> 'すべて上書き',
		'No'							=> 'いいえ',
		'Cancel'						=> 'キャンセル',

		'1 line'						=> '1件',
		'10 lines'						=> '10件',
		'20 lines'						=> '20件',
		'50 lines'						=> '50件',
		'100 lines'						=> '100件',

		'Page:'							=> 'ページ ：',
		'User:'							=> 'ユーザー：',
		'Log out'						=> 'ログアウト',

		'Published version'				=> '公開バージョン',
		'Working version'				=> '作業中バージョン',
		'Compare versions'				=> 'バージョン比較',

		'Cut'							=> '切り取り',
		'Copy'							=> 'コピー',
		'Paste'							=> '貼り付け',
		'Delete'						=> '削除',
		'New'							=> '新規',
		'Rename'						=> '名前の変更',
		'Empty the trash'				=> 'ゴミ箱を空にする',
		'Folder'						=> 'フォルダ',
		'folder'						=> 'フォルダ',
		'File'							=> 'ファイル',
		'file'							=> 'ファイル',
		'Page'							=> 'ページ',

		'Are you sure you want to move %NODE_NAME% to the trash?'
										=> '%NODE_NAME%をゴミ箱に移動します。よろしいですか？',
		'Are you sure you want to move these %NODE_COUNT% objects to the trash?'
										=> 'これら%NODE_COUNT%をゴミ箱に移動します。よろしいですか？',
		'Are you sure you want to permanently remove the items in the trash?'
										=> '完全に削除します。よろしいですか？',
		"Another user has updated this record\nAre you sure you want to overwrite?"
										=> "他のユーザに更新されています。\n上書きしますか？",
		'Saved'							=> '保存しました',
		'Failed to save'				=> '保存に失敗しました',
		'Already exists'				=> '既に存在しています',
		'Please enter name'				=> '名前を入力してください。',
		'Please enter a name for the %ITEM%'
										=> '%ITEM%名を入力してください。',
		'Multi-byte characters cannot be used'
										=> '日本語は使用できません',
		'A %ITEM% with this name already exists. Please enter a different name.'
										=> '名前を変更できません。指定された%ITEM%名は既に存在します。別の名前を指定してください。',
		'Please enter the file extension'
										=> '拡張子が必要です。',
		'The following extensions cannot be used (css swf jpg jpeg gif png)'
										=> '次の拡張子は使用できません css swf jpg jpeg gif png',
		'The following charcters cannot be used in file or folder names (\ / : * ? " \' < > | space)'
										=> 'ファイル名／フォルダ名に次の文字は使えません \ / : * ? " \' < > | スペース',
		'Displaying %RECORD_FROM% to %RECORD_TO% of %TOTAL% items'
										=> ' 全%TOTAL%件中　%RECORD_FROM%～%RECORD_TO%件目',

		// Message
		'Your session has timed out. Please log in again'
										=> 'セッションが切れました。ログインしなおしてください。',

		// Browser check
		'Your browser is not supported. <br />Please use one of the browsers listed below.'
										=> 'お使いのブラウザはサポートされていません<br />以下のブラウザをご使用ください',
		// Login Error
		'Error'							=> 'エラー',
		'The Login ID or password you entered is invalid.'
										=> 'ログインIDとパスワードを正しく入力してください',

		// Error message for date and time
		' (out of range)'				=> '（範囲外）',
		' (invalid time)'				=> '（時刻が不正）',
		' (invalid date)'				=> '（日付が不正）',
		' (format error)'				=> '（フォーマットが不正）',

		// Error message for Node
		'DB error'						=> 'DBエラー',
		'The destination folder is a subfolder of the selected folder'
										=> '受け側のフォルダは送り側のフォルダのサブフォルダです',
		'The destination template is a subtemplate of the selecter template'
										=> '受け側のテンプレートは送り側のサブテンプレートです',
		'The number of nodes are differnt. Please sort in right pane.'
										=> 'ノードの数が違っています(右側のフォルダペインでソートしてください)',
		'The number of nodes are different'
										=> 'ノードの数が違っています',

		// Form
		'*'								=> '※',
		' Indicates required field'		=> '：必須項目',
		'was saved.'					=> 'を保存しました。',
		'was failed to saved.'			=> 'の保存に失敗しました。',
		'was registered.'				=> 'を登録しました。',
		'was failed to register.'		=> 'の登録に失敗しました。',
		'was updated.'					=> 'を更新しました。',
		'was failed to update.'			=> 'の更新に失敗しました。',
		'was set.'						=> 'を設定しました。',
		'was failed to set.'			=> 'の設定に失敗しました。',
		'were set.'						=> 'を設定しました。',
		'were failed to set.'			=> 'の設定に失敗しました。',
		'was deleted.'					=> 'を削除しました。',
		'was failed to delete.'			=> 'の削除に失敗しました。',

		'Another user has updated this record'
										=> '他のユーザに更新されています',
		// Menu
		'Open published page'			=> '公開画面を開きます',
		'Contents'						=> 'コンテンツ',
		'Templates'						=> 'テンプレート',
		'Resources'						=> 'リソース',
		'Resource Manager'				=> 'リソース管理',
		'Widgets'						=> 'ウィジェット',
		'widget'						=> 'ウィジェット',
		'Posts'							=> '投稿',
		'Article'						=> '記事',
		'Article2'						=> '記事2',
		'Article3'						=> '記事3',
		'File Manager'					=> 'ファイル管理',
		'Basic Settings'				=> '基本設定',
		'Versions'						=> 'バージョン管理',
		'Users'							=> 'ユーザ管理',
		'Site Admin'					=> 'サイト管理者設定',
		'Open another admin page'		=> '管理画面をもう一枚開きます',

		// Buttons
		'Confirm'						=> '確認',
		'Back'							=> '戻る',
		'Save'							=> '保存',

		// Contents
		'HTML'							=> 'HTML',
		'Visual'						=> 'ビジュアル',
		'CSS'							=> 'CSS',
		'PHP'							=> 'PHP',
		'Settings'						=> '設定',
		'Preview'						=> 'プレビュー',
		'Title'							=> 'タイトル',
		'Template'						=> 'テンプレート',
		'template'						=> 'テンプレート',
		'Breadcrumbs'					=> 'パンくず',
		'Keywords'						=> 'Keywords',
		'Description'					=> 'Description',
		'External css'					=> '外部 css',
		'External javascript'			=> '外部 javascript',
		'Header elements'				=> 'ヘッダー要素',

		// Resource
		'Upload'						=> 'アップロード',
		'File name'						=> 'ファイル名',
		'Modified'						=> '更新日時',
		'File size'						=> 'ファイルサイズ',
		'Image size'					=> 'イメージサイズ',
		'No folder selected'			=> 'フォルダが選択されていません',
		'The file size is too large. The maximun file upload size is %LIMIT%'
										=> 'ファイルサイズが大きすぎます。アップロードできるのは%LIMIT%までです',
		'Extract %FILE_NAME% ?'			=> '%FILE_NAME%を展開しますか？',
		'%FILE_NAME% already exists.<br />Are you sure you want to overwrite?'
										=> '%FILE_NAME%は既に存在します。<br />上書きしてもよろしいですか？',
		'Failed to create directory'	=> 'ディレクトリの作成に失敗しました',
		'Multi-byte characters cannot be used in file names. Please check contents of the zip file.'
										=> '日本語ファイル名は使用できません。（zipファイル中）',
		'The uploaded file exceeds the upload_max_filesize directive in php.ini.'
										=> 'アップロードされたファイルは、php.ini の upload_max_filesize ディレクティブの値を超えています。',
		'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.'
										=> 'アップロードされたファイルは、HTML フォームで指定された MAX_FILE_SIZE を超えています。',
		'The uploaded file was only partially uploaded.'
										=> 'アップロードされたファイルは一部のみしかアップロードされていません。',
		'No file was uploaded.'			=> 'ファイルはアップロードされませんでした。',
		'Missing a temporary folder. Introduced in PHP 5.0.3.'
										=> 'テンポラリフォルダがありません。PHP 5.0.3 で導入されました。',
		'Failed to write file to disk. Introduced in PHP 5.1.0.'
										=> 'ディスクへの書き込みに失敗しました。PHP 5.1.0 で導入されました。',
		'A PHP extension stopped the file upload.'
										=> 'ファイルのアップロードが拡張モジュールによって停止されました。',

		// Article
		'Category: '					=> 'カテゴリ：',
		'Category'						=> 'カテゴリ',
		'category'						=> 'カテゴリ',
		'Date'							=> '日付',
		'Date: '						=> '日付：',
		'Title: '						=> 'タイトル：',
		'Published/Preview/Closed'		=> '公開／プレビュー／非公開',
		'Published'						=> '公開',
		'Closed'						=> '非公開',
		'Publication date'				=> '掲載日',
		'Please enter publication date'	=> '掲載日を入力してください',
		'Please enter title'			=> 'タイトルを入力してください',
		'Title image'					=> 'タイトル画像',
		'Display detail'				=> '詳細表示',
		'On'							=> 'あり',
		'Off'							=> 'なし',
		'External link'					=> '外部リンク',
		'After turninng external link on, please enter URL'
										=> '「外部リンクあり」を選択した場合は、URLを入力してください',
		'Open link in new window'		=> '別ウインドウ',
		'Details'						=> '詳細',
		'Are you sure you want to delete?'
										=> '削除します。よろしいですか',
		'Properties'					=> 'プロパティー',
		'newCategory'					=> '新しいカテゴリ',
		'newFolder'						=> '新しいフォルダ',

		// Category
		'Double-click to set'			=> 'カテゴリ名をダブルクリックすると設定されます',
		'Text color'					=> '文字色',
		'Backgroud color'				=> '背景色',
		'Icon'							=> 'アイコン',
		'Image selection'				=> '画像選択',

		// Calendar
		'Calendar'						=> 'カレンダー',
		'%MONTH% %YEAR%'				=> '%YEAR%年 %MONTH%',
		'Jan'							=> '1月',
		'Feb'							=> '2月',
		'Mar'							=> '3月',
		'Apr'							=> '4月',
		'May'							=> '5月',
		'Jun'							=> '6月',
		'Jul'							=> '7月',
		'Aug'							=> '8月',
		'Sep'							=> '9月',
		'Oct'							=> '10月',
		'Nov'							=> '11月',
		'Dec'							=> '12月',

		'Sun'							=> '日',
		'Mon'							=> '月',
		'Tue'							=> '火',
		'Wed'							=> '水',
		'Thu'							=> '木',
		'Fri'							=> '金',
		'Sat'							=> '土',

		// File manager
		'Are you sure you want to delete %NODE_NAME%?'
										=> '%NODE_NAME%を削除します。よろしいですか？',
		'Are you sure you want to delete these %NODE_COUNT% objects?'
										=> 'これら%NODE_COUNT%個の項目を削除します。よろしいですか？',
		'Please use server browser'		=> 'サーバブラウザを使用してください',
		'An error has occurred'			=> 'エラーが発生しました',
		'The name could not be changed'	=> '名前を変更できません',

		// Version
		'Keyword'						=> 'キーワード',
		'Keyword: '						=> 'キーワード：',
		'Search conditions'				=> '検索条件',
		'No record found'				=> '該当レコードはありません',
		'Display'						=> '表示',
		'Search'						=> '検索',
		'Clear'							=> 'クリア',
		'New '							=> '新規作成',
		'Publish'						=> '公開',
		'Working'						=> '作業中',
		'ID'							=> 'ID',
		'Publish date/time'				=> '公開日時',
		'Status'						=> '状態',
		'Status ■:Published  ★:Scheduled to be published'
										=> '状態　■：公開　★：公開予約',
		'Notes'							=> 'メモ',
		'Change versions'				=> 'バージョン変更',
		'Edit'							=> '編集',
		'Compare'						=> '比較',
		'Delete'						=> '削除',
		'Submit'						=> '設定',
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
		'All the contents in this version will be completely deleted.\nThis operation cannot be undone.\n\nAre your sure you want to delete?'
										=> 'このバージョンで作成したコンテンツすべてを削除します。\nこの作業は元に戻せません\n\nよろしいですか？',
		'Version : '					=> 'バージョン ：',
		'Publish date and time : '		=> '公開日時 ：',
		'This version can not be deleted because it is not ths most recent version.'
										=> '最新バージョンではありませんので削除できません',
		'The working version cannot be deleted.'
										=> '作業中バージョンなので削除できません',
		'The published version cannot be deleted.'
										=> '公開バージョンなので削除できません',
		'Please select version.'		=> 'バージョンを選択してください',
		'<img src="images/common/caution.png" alt="#" />If you schedule this version to be published at a later date, you must set the current published version'
										=> '<img src="images/common/caution.png" alt="#" />このバージョンを予約登録するには、それまでに公開されるバージョンを設定してから再度、予約登録する必要があります。',
		'Published version:%PUBLISH_VERSION% &nbsp;Working version:%WROKING_VERSION%'
										=> '公開バージョン：%PUBLISH_VERSION% &nbsp;作業中バージョン：%WROKING_VERSION%',

		'will be set.'					=> 'に設定します。',
		'Changes will be reflected immediately'
										=> '即時反映',
		'Scheduled to be published'		=> '予約登録',

		// Compare
		'Compare Versions Left: %LEFT_VERSION% &nbsp;Right: %RIGHT_VERSION%'
										=> 'バージョン比較 Left: %LEFT_VERSION% Right: %RIGHT_VERSION%',

		// Users
		'Name'							=> '名前',
		'User ID'						=> 'ユーザID',
		'User ID: '						=> 'ユーザID：',
		'Password'						=> 'パスワード',
		'User type'						=> '権限',
		'Enabled'						=> '有効',
		'Disabled'						=> '無効',
		'Admin'							=> '管理者',
		'Editor'						=> '投稿者',
		'Please enter user ID'			=> 'ユーザIDを入力してください',
		'Please enter user ID using only alphanumeric, hyphen(-) and underbar(_)'
										=> 'ユーザIDは英数とハイフン(-)アンダーバー(_)で入力してください',
		'This ID is already in use'		=> '既に登録されています',
		'This ID cannot be used'		=> 'そのIDは登録できません',
		'Please enter password'			=> 'パスワードを入力してください',
		'Please select user type'		=> 'ユーザ権限を選択してください',
		'Please enter a name'			=> '名前を入力してください',
		'English'						=> '英語',
		'Japanese'						=> '日本語',
		'Chinese'						=> '中国語',

		// Site admin
		'Username'						=> 'ユーザ名',
		'Login ID'						=> 'ログインID',
		'Password (Re-entry)'			=> 'パスワード（再入力）',
		'(set password)'				=> '（設定されたパスワード）',
		'If you would like to change your password, please enter new password here. If not, please leave this field blank.'
										=> 'パスワードを変更する場合は新しいパスワードを入力し、変更しない場合は空のままにしておいてください',
		'For confirmation, please re-enter password'
										=> '確認のため、パスワードを再入力してください',
		'Please enter username'			=> 'ユーザ名を入力してください',
		'Please enter login ID'			=> 'ログインIDを入力してください',
		'Please enter login ID using only alphanumeric, hyphen(-) and underbar(_)'
										=> 'ログインIDは英数とハイフン(-)アンダーバー(_)で入力してください',
		'Password dose not match'		=> 'パスワードが一致していません',
		'Back to site admin settings'	=> '設定画面に戻る',
		'The site admin settings has been updated'
										=> 'サイト管理者の情報を更新しました',

		// Basic settings
		'Admin page title'				=> '管理画面タイトル',
		'Language'						=> '言語',
		'DB backup'						=> 'DBバックアップ',
		'Full backup'					=> 'FULLバックアップ',
		'Re-install backup'				=> '再インストール用バックアップ',
		'Download'						=> 'ダウンロード',
		'Back to basic settings'		=> '基本設定に戻る',
		'Basic settings: Saved'			=> '基本設定：保存しました。',
		'Basic settings: Failed'		=> '基本設定：保存に失敗しました。',

		// Install
		'Select language: '				=> '言語を選択してください：',
		'Hostname'						=> 'ホスト名',
		'Please enter hostname'			=> 'ホスト名を入力してください',
		'Please confirm the input content'
										=> '入力内容を確認してください',
		'Please enter username using only alphanumeric, hyphen(-) and underbar(_)'
										=> 'ユーザ名は英数とハイフン(-)アンダーバー(_)で入力してください',
		'Please enter password using only alphanumeric, hyphen(-) and underbar(_)'
										=> 'パスワードは英数とハイフン(-)アンダーバー(_)で入力してください',
		'Schema name'					=> 'スキーマ',
		'Please enter schema name'		=> 'スキーマを入力してください',
		'Table prefix'					=> 'テーブル・プリフィックス',
		'Please enter table prefix'		=> 'テーブル・プリフィックスを入力してください',
		'Usually changing this field is unnecessary. Please change This field when installing B-studio multiple times in one schema.'
										=> '※通常この項目を変更する必要はありません。ひとつのDBにB-studioを複数インストールする場合は変更してください。',
		'Please re-enter password'		=> 'パスワードを再入力してください',
		'Password does not match'		=> 'パスワードが一致していません',
		'(Set password)'				=> '（設定されたパスワード）',

		'Please enable mbstring module'	=> 'mbstringモジュールを有効にしてください。',
		'Please set session.save_path'	=> 'session.save_pathを設定してください。',
		'Please enable MySQL library'	=> 'MySQLライブラリを有効にしてください。',
		'Please enable GD library'		=> 'GDライブラリを有効にしてください。',
		'Please enable exif library'	=> 'exifライブラリを有効にしてください。',
		'ZipArchive is necessary'		=> 'ZipArchiveクラスが必要です。',

		' : write permission granted. '	=> ' の書き込み権限はOKです。',
		' : write permission not set. '	=> ' に書き込み権限がありません。',

		'Able to connect to DB but failed to select schema.'
										=> 'DBへ接続はできましたがスキーマの選択に失敗しました。',
		'Failed to connect to DB.'		=> 'DBへの接続に失敗しました。',
		'This is an error in your entry<br />Please check any error message and re-enter the necessary information'
										=> '入力内容に誤りがあります。<br />各欄のエラーメッセージをご覧の上、入力し直してください。',

		'Failed to create table.'		=> 'テーブルの作成に失敗しました。',
		'Failed to create view.'		=> 'ビューの作成に失敗しました。',
		'Failed to create version record.'
										=> 'バージョンレコードの作成に失敗しました。',
		'Failed to create current version record.'
										=> 'カレントバージョンレコードの作成に失敗しました。',
		'Failed to create configuration record.'
										=> '基本設定レコードの作成に失敗しました。',
	);
