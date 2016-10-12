<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja">
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Expires" content="Thu, 01 Dec 1994 16:00:00 GMT"> 
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="css/install.css" type="text/css" media="all" />
<link rel="stylesheet" href="../bs-admin/css/selectbox_white.css" type="text/css" media="all" />
<script src="../bs-admin/js/bframe.js" type="text/javascript"></script>
<script src="../bs-admin/js/bframe_context_menu.js" type="text/javascript"></script>
<script src="../bs-admin/js/bframe_popup.js" type="text/javascript"></script>
<script src="../bs-admin/js/bframe_selectbox.js" type="text/javascript"></script>
<title>B-studioのインストール</title>
</head>
<body>

	<h1>B-studioのインストール</h1>

	<?php
		if($error_message) {
			echo '<p class="error-message-top">' . $error_message . '</p>';
		}
	?>

	<form method="post" action="index.php">
		<div id="select-language">
			<?php echo $select_language->getHtml(); ?>
			<input name="action" value="select-language" type="hidden" />
		</div>
	</form>

	<form method="post" action="index.php">

		<p>MySQLテーブルのセットアップと、サイト管理者の情報を設定します。</p>

		<h2>MySQLのログイン情報</h2>

		<p>データベースのログイン情報を入力してください。</p>
		<fieldset>
			<legend>データベース設定</legend>
			<?php echo $db_install_form->getHtml(); ?>
		</fieldset>

		<h2>管理画面ベーシック認証</h2>

		<p>管理画面ディレクトリにベーシック認証を設定します。不正なアクセスからサイトを守るために必要です。</p>
		<fieldset>
			<legend>管理画面ベーシック認証</legend>
			<?php echo $admin_basic_auth_form->getHtml(); ?>
		</fieldset>

		<h2>サイト管理ユーザ</h2>

		<p>サイト管理ユーザを作成するための情報を入力してください。</p>
		<fieldset>
			<legend>サイト管理ユーザ</legend>
			<?php echo $admin_user_form->getHtml(); ?>
		</fieldset>

		<h2>htaccess</h2>

		<p>Bstudioのルートディレクトリに作成されるhtaccessファイル</p>
		<fieldset>
			<legend>htaccess</legend>
			<?php echo $root_htaccess->getHtml(); ?>
		</fieldset>

		<h2>ディレクトリパーミッションの確認</h2>
			<?php echo $perm_message; ?>

		<h2>設定内容の確認</h2>

		<div class="confirm">
			<input name="action" value="confirm" type="hidden" />
			<input type="submit" class="button" value="　確　認　" />
		</div>

	</form>
</body>
</html>