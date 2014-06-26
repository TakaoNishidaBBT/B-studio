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
<title>B-studioのインストール</title>
</head>
<body>

	<form method="post" action="">

		<h1>B-studioのインストール</h1>

		<?php if($error_message) { ?>
			<div class="error">
				<fieldset>
					<legend>エラー</legend>
					<?php echo $error_message; ?>
				</fieldset>
			</div>
		<?php } ?>

		<h2>MySQLのログイン情報</h2>

		<fieldset>
			<legend>データベース設定</legend>
			<?php echo $db_install_form->getHtml('confirm'); ?>
		</fieldset>

		<h2>管理画面ベーシック認証</h2>

		<fieldset>
			<legend>管理画面ベーシック認証</legend>
			<?php echo $admin_basic_auth_form->getHtml('confirm'); ?>
		</fieldset>

		<h2>サイト管理ユーザ</h2>

		<fieldset>
			<legend>サイトの管理者</legend>
			<?php echo $admin_user_form->getHtml('confirm'); ?>
		</fieldset>

		<h2>htaccess</h2>

		<fieldset>
			<legend>htaccess</legend>
			<?php echo $root_htaccess->getHtml('confirm'); ?>
		</fieldset>

		<h2>インストール</h2>


		<fieldset>
			<legend>インストールによって作成されるファイル</legend>
			<ul>
				<li>インストールディレクトリ/.htaccess</li>
				<li>インストールディレクトリ/bs-admin/user/users.php</li>
				<li>インストールディレクトリ/bs-admin/config/core_config.php</li>
				<li>インストールディレクトリ/bs-admin/db/db_connect.php</li>
			</ul>
			<p><span class="caution">※</span>ファイルが存在する場合は上書きされます。</p>
		</fieldset>

		<p>「インストール」ボタンをクリックすると、この内容でインストールを開始します。</p>

		<div class="confirm">
			<input name="action" value="install" type="hidden" />
			<input type="button" class="button" value="　戻　る　" onclick="location.href='index.php'"/>
			<input type="submit" class="button" value="インストール" />
		</div>

	</form>
</body>
</html>