<!DOCTYPE html>
<html lang="<?php echo $_SESSION['language']; ?>">
<head>
<meta charset="utf-8" />
<link rel="stylesheet" href="css/install.css" type="text/css" media="all" />
<title>Install B-studio</title>
</head>
<body>

	<form method="post" action="">

		<h1>Replace DB VIEW</h1>

		<?php if($error_message) { ?>
			<div class="error">
				<fieldset>
					<legend>Error</legend>
					<?php echo $error_message; ?>
				</fieldset>
			</div>
		<?php } ?>

		<p class="center">Click "Replace" button to start replace DB VIEW.</p>

		<div class="confirm">
			<input name="action" value="replace" type="hidden" />
			<input type="submit" class="button" value="Replace" />
		</div>

	</form>
</body>
</html>