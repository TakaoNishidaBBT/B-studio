<body>
	<h2 class="version"><?php echo __('Versions'); ?><span><?php echo $this->version_info; ?></span></h2>
	<div class="contents bframe_adjustparent bframe_scroll" data-param="margin:133"">
		<form name="F1" id="F1" method="post" action="index.php" target="main">
			<div class="version-info">
				<?php echo $this->version_information->getHtml(); ?>
				<p><?php echo __('will be set.'); ?></p>
			</div>
			<?php echo $this->version_control_confirm->getHtml(); ?>
		</form>
	</div>
</body>
