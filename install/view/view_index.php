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
<title>Install B-studio</title>
</head>
<body>
	<h1>Install B-studio</h1>

	<form method="post" action=".">
		<div id="select-language">
			<?php echo $select_language->getHtml(); ?>
			<input name="action" value="select-language" type="hidden" />
		</div>
	</form>

	<?php
		if(isset($error_message)) {
			echo '<p class="error-message-top">' . $error_message . '</p>';
		}
	?>

	<form method="post" action=".">
		<p>Setting up MySQL DataBase and Site admin configurations.</p>

		<h2>MySQL</h2>

		<p>Please enter the following field to set up connecting to MySQL DataBase.</p>
		<fieldset>
			<legend>Configuration DataBase</legend>
			<?php echo $db_install_form->getHtml(); ?>
		</fieldset>

		<h2>Basic authentication of admin page</h2>

		<p>Setting the basic authentication to the admin page. This is neccessary for preventing from unauthorized access.</p>
		<fieldset>
			<legend>Basic authentication of admin page</legend>
			<?php echo $admin_basic_auth_form->getHtml(); ?>
		</fieldset>

		<h2>Site admin</h2>

		<p>Please enter the following field to set up configuration of the site admin</p>
		<fieldset>
			<legend>Site admin</legend>
			<?php echo $admin_user_form->getHtml(); ?>
		</fieldset>

		<h2>htaccess</h2>

		<p>The htaccess file will be set at B-stuio's root directory.</p>
		<fieldset>
			<legend>htaccess</legend>
			<?php echo $root_htaccess->getHtml(); ?>
		</fieldset>

		<h2>Cofirmation of permission</h2>
			<?php echo $perm_message; ?>

		<h2>Confirm the contents</h2>

		<div class="confirm">
			<input name="action" value="confirm" type="hidden" />
			<input type="submit" class="button" value="Confirm" />
		</div>

	</form>
</body>
</html>