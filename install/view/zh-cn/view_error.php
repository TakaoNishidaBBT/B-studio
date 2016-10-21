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
<title>安装 B-studio</title>
</head>
<body>
	<h1>安装 B-studio</h1>

	<p class="error"><?php echo $this->error_message; ?></p>
	<fieldset>
		<legend>错误信息</legend>
		<?php echo $this->db_error_message; ?>
	</fieldset>

	<ul class="control">
		<li><input type="button" class="button" name="button" value="返回" onclick="history.back();"  /></li>
	</ul>

</body>
</html>