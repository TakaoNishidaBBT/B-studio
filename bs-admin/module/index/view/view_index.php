<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="terminal_id" content="<?php echo TERMINAL_ID ?>">
<meta name="source_module" content="index">
<meta name="source_page" content="index">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/menu.css">
<link rel="stylesheet" href="css/context_menu.css">
<link rel="stylesheet" href="css/modal_window.css">
<link rel="stylesheet" href="css/progress_bar.css">
<script src="js/bframe.js"></script>
<script src="js/bframe_ajax.js"></script>
<script src="js/bframe_message.js"></script>
<script src="js/bframe_popup.js"></script>
<script src="js/bframe_context_menu.js"></script>
<script src="js/bframe_menu.js"></script>
<script src="js/bframe_adjustwindow.js"></script>
<script src="js/bframe_modal_window.js"></script>
<title><?php echo $this->title ?></title></head>
<body>
	<script>if(window != top) top.location.href='.'</script>
	<div id="title-header">
		<h1><?php echo $this->site_title ?></h1>
		<div class="login-user">
			<ul>
				<li>
					<dl class="login-user">
						<dt><img src="images/menu/user.png" alt="User" /></dt>
						<dd><strong><?php echo $this->user_name ?></strong></dd>
					</dl>
				</li>
				<li><a href="<?php echo DISPATCH_URL ?>&amp;module=index&amp;page=logout" target="_top" ><?php echo __('Log out'); ?></a></li>
			</ul>
		</div>
	</div>
	<?php echo $this->menu->gethtml(); ?>
	<iframe id="main" name="main" class="bframe_adjustwindow" src="<?php echo $this->initial_page; ?>"></iframe>
</body>
