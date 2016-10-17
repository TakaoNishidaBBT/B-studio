<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<link href="css/common.css" type="text/css" rel="stylesheet" media="all" />
<link href="css/login.css" type="text/css" rel="stylesheet" media="all" />
<title><?php echo $this->title ?></title></head>
<body>
	<div id="title-header"><h1><?php echo $this->site_title ?></h1></div>
	<div class="message">
		<dl class="error">
			<dt><img src="images/login/warning.png" alt="Error" /><?php echo __('Error'); ?></dt>
			<dd><p class="error-message"><strong><?php echo __('Please enter your login ID and password correctly.'); ?></strong></p></dd>
		</dl>

		<div class="icon">
			<img src="images/login/lock.png" alt="lock" />
		</div>

		<ul class="transition">
			<li><input class="back" type="button" onclick="history.back()" value="back" /></li>
		</ul>
	</div>
</body>
