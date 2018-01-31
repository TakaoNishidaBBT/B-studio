<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/login.css">
<title><?php echo $this->title ?></title></head>
<body>
	<div id="title-header"><h1><?php echo $this->site_title ?></h1></div>
	<div class="message">
		<dl class="error">
			<dt><img src="images/login/warning.png" alt="Error" /><?php echo __('Error'); ?></dt>
			<dd><p class="error-message"><strong><?php echo __('The Login ID or password you entered is invalid.'); ?></strong></p></dd>
		</dl>

		<div class="icon">
			<img src="images/login/lock.svg" alt="lock" />
		</div>

		<ul class="transition">
			<li><input class="back" type="button" onclick="location.href='.'" value="back" /></li>
		</ul>
	</div>
</body>
