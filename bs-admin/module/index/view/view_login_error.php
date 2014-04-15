<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<link href="css/common.css" type="text/css" rel="stylesheet" media="all" />
<link href="css/login.css" type="text/css" rel="stylesheet" media="all" />
<title><?php echo $this->title ?></title></head>
<body>
	<div id="HEADER"><h1><?php echo $this->site_title ?></h1></div>
	<div class="message">
		<dl class="error">
			<dt><img src="images/common/warning.png" alt="エラー" />エラー</dt>
			<dd><p class="error-message"><strong>ログインIDとパスワードを正しく入力してください</strong></p></dd>
		</dl>

		<div class="icon">
			<img src="images/common/lock_icon.png" alt="login" width="27" height="27" />
		</div>

		<ul class="transition">
			<li><input class="back" type="button" onclick="history.back()" value="back" /></li>
		</ul>
	</div>
</body>
