<!DOCTYPE html>
<html lang="<?php echo $_SESSION['language']; ?>">
<head>
<meta charset="utf-8" />
<link rel="stylesheet" href="css/install.css" type="text/css" media="all" />
<link rel="stylesheet" href="../bs-admin/css/selectbox.css" type="text/css" media="all" />
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

	<form method="post" action=".">
		<div id="select-language">
			<?php echo $select_language->getHtml(); ?>
			<input name="action" value="select-language" type="hidden" />
		</div>
	</form>

	<form method="post" action=".">

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

		<p>B-studioのルートディレクトリに作成されるhtaccessファイル</p>
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