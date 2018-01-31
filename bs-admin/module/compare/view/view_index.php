<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="terminal_id" content="<?php echo TERMINAL_ID ?>">
<meta name="source_module" content="compare">
<meta name="source_page" content="index">
<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/menu.css">
<link rel="stylesheet" href="css/context_menu.css">
<link rel="stylesheet" href="css/modal_window.css">
<script src="js/bframe.js"></script>
<script src="js/bframe_ajax.js"></script>
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
						<dt><?php echo __('User:'); ?></dt>
						<dd><strong><?php echo $this->user_name ?></strong></dd>
					</dl>
				</li>
			</ul>
		</div>
	</div>
	<?php echo $this->menu->gethtml(); ?>
	<iframe id="main" class="bframe_adjustwindow" src="<?php echo $this->initial_page; ?>"></iframe>
</body>
