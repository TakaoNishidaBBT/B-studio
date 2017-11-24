<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="terminal_id" content="<?php echo TERMINAL_ID ?>" />
<meta name="source_module" content="compare" />
<meta name="source_page" content="index" />
<link href="css/common.css" rel="stylesheet" media="all" />
<link href="css/menu.css" type="text/css" rel="stylesheet" media="all" />
<link href="css/context_menu.css" type="text/css" rel="stylesheet" media="all" />
<link href="css/modal_window.css" type="text/css" rel="stylesheet" media="all" />
<script src="js/bframe.js" type="text/javascript"></script>
<script src="js/bframe_ajax.js" type="text/javascript"></script>
<script src="js/bframe_popup.js" type="text/javascript"></script>
<script src="js/bframe_context_menu.js" type="text/javascript"></script>
<script src="js/bframe_menu.js" type="text/javascript"></script>
<script src="js/bframe_adjustwindow.js" type="text/javascript"></script>
<script src="js/bframe_modal_window.js" type="text/javascript"></script>
<title><?php echo $this->title ?></title></head>
<body>
	<script type="text/javascript">if(window != top) top.location.href='.'</script>
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
	<iframe class="main bframe_adjustwindow" frameborder="0" src="<?php echo $this->initial_page; ?>"
		name="main" id="main" align="top" scrolling="auto" width="100%" height="100%"></iframe>
</body>
