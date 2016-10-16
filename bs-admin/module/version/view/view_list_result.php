<body>
	<h2 class="version"><?php echo _('Versions'); ?><span><?php echo $this->version_info; ?></span></h2>
	<div class="contents">
		<form name="F1" id="F1" method="post" action="index.php" target="main">
			<div class="version-info">
				<?php echo $this->version_information->getHtml(); ?>
				<p><?php echo $this->action_message; ?></p>
			</div>
			<?php echo $this->version_control_result->getHtml(); ?>
		</form>
	</div>
</body>
