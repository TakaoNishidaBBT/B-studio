<body>
	<div id="header"><h2 class="settings"><?php echo __('Site Admin'); ?></h2></div>
	<form name="F1" method="post" action="index.php">
		<div id="control">
			<?php echo $this->result_control->getHtml(); ?>
		</div>

		<div id="main">
			<?php echo $this->result->getHtml(); ?>
		</div>
	</form>
</body>
</html>
