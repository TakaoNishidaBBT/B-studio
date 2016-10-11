<body>
	<div id="header"><h2 class="version"><?php echo _('Versions'); ?><span><?php echo $this->version_info ?></span></h2></div>
	<form name="F1" method="post" action="index.php">
		<div id="control">
			<?php echo $this->result_control->getHtml(); ?>
		</div>

		<div id="main">
			<?php echo $this->message; ?>
		</div>
	</form>
</body>
