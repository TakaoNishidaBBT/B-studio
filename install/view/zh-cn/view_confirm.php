<!DOCTYPE html>
<html lang="<?php echo $_SESSION['language']; ?>">
<head>
<meta charset="utf-8" />
<link rel="stylesheet" href="css/install.css" type="text/css" media="all" />
<title>安装 B-studio</title>
</head>
<body>

	<form method="post" action="">

		<h1>安装 B-studio</h1>

		<?php if($error_message) { ?>
			<div class="error">
				<fieldset>
					<legend>错误</legend>
					<?php echo $error_message; ?>
				</fieldset>
			</div>
		<?php } ?>

		<h2>数据库连接信息</h2>

		<fieldset>
			<legend>数据库设定</legend>
			<?php echo $db_install_form->getHtml('confirm'); ?>
		</fieldset>

		<h2>管理页面 Basic 认证</h2>

		<fieldset>
			<legend>管理页面 Basic 认证</legend>
			<?php echo $admin_basic_auth_form->getHtml('confirm'); ?>
		</fieldset>

		<h2>网站管理员</h2>

		<fieldset>
			<legend>网站管理员</legend>
			<?php echo $admin_user_form->getHtml('confirm'); ?>
		</fieldset>

		<h2>htaccess</h2>

		<fieldset>
			<legend>htaccess</legend>
			<?php echo $root_htaccess->getHtml('confirm'); ?>
		</fieldset>

		<h2>安装</h2>


		<fieldset>
			<legend>安装时生成文本</legend>
			<ul>
				<li>安装路径/.htaccess</li>
				<li>安装路径/bs-admin/user/users.php</li>
				<li>安装路径/bs-admin/config/core_config.php</li>
				<li>安装路径/bs-admin/db/db_connect.php</li>
			</ul>
			<p><span class="caution">※</span>如果文本已存在，新生成的文本将覆盖原文本。</p>
		</fieldset>

		<p>点击「安装」按钮开始安装。</p>

		<div class="confirm">
			<input name="action" value="install" type="hidden" />
			<input type="button" class="button" value="　返　回　" onclick="location.href='index.php'"/>
			<input type="submit" class="button" value="　安　装　" />
		</div>

	</form>
</body>
</html>