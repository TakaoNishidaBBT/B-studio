<!DOCTYPE html>
<html lang="<?php echo $_SESSION['language']; ?>">
<head>
<meta charset="utf-8" />
<link rel="stylesheet" href="css/install.css" type="text/css" media="all" />
<title>Install B-studio</title>
</head>
<body>

	<form method="post" action="">

		<h1>Install B-studio</h1>

		<?php if($error_message) { ?>
			<div class="error">
				<fieldset>
					<legend>Error</legend>
					<?php echo $error_message; ?>
				</fieldset>
			</div>
		<?php } ?>

		<h2>MySQL</h2>

		<fieldset>
			<legend>Configuration DataBase</legend>
			<?php echo $db_install_form->getHtml('confirm'); ?>
		</fieldset>

		<h2>Basic authentication of admin page</h2>

		<fieldset>
			<legend>Basic authentication of admin page</legend>
			<?php echo $admin_basic_auth_form->getHtml('confirm'); ?>
		</fieldset>

		<h2>Site admin</h2>

		<fieldset>
			<legend>Site admin</legend>
			<?php echo $admin_user_form->getHtml('confirm'); ?>
		</fieldset>

		<h2>htaccess</h2>

		<fieldset>
			<legend>htaccess</legend>
			<?php echo $root_htaccess->getHtml('confirm'); ?>
		</fieldset>

		<h2>Install</h2>


		<fieldset>
			<legend>The following files will be created after install.</legend>
			<ul>
				<li>(install-directory)/.htaccess</li>
				<li>(install-directory)/bs-admin/.htaccess</li>
				<li>(install-directory)/bs-admin/db/db_connect.php</li>
				<li>(install-directory)/bs-admin/user/users.php</li>
			</ul>
			<p><span class="caution">※</span>The files will be overwriten when files are already exist.</p>
		</fieldset>

		<p class="center">Click "Install" button to start install in the contents above.</p>

		<div class="confirm">
			<input name="action" value="install" type="hidden" />
			<input type="button" class="button" value="Back" onclick="location.href='index.php'"/>
			<input type="submit" class="button" value="Install" />
		</div>

	</form>
</body>
</html>