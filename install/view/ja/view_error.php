<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<link rel="stylesheet" href="css/install.css" type="text/css" media="all" />
<title>B-studioのインストール</title>
</head>
<body>
	<h1>B-studioのインストール</h1>

	<p class="error"><?php echo $this->error_message; ?></p>
	<fieldset>
		<legend>Error Message</legend>
		<?php echo $this->db_error_message; ?>
	</fieldset>

	<ul class="control">
		<li><input type="button" class="button" name="button" value="戻る" onclick="history.back();"  /></li>
	</ul>

</body>
</html>