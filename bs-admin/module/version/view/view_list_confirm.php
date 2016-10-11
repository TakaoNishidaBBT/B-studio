<body>
	<h2 class="version"><?php echo _('Versions'); ?><span><?php echo $this->version_info; ?></span></h2>
	<div class="contents">
		<form name="F1" id="F1" method="post" action="index.php" target="main">
			<div class="info">
				<p>
					■<?php echo _('Publish version: '); ?>
					<span class="bold"><?php echo $this->reserved_version . '　' . $this->reserve_datetime; ?></span>
				</p>
				<p>
					■<?php echo _('Working version: '); ?>
					<span class="bold"><?php echo $this->working_version; ?></span>
				</p>
				<p><?php echo _('will be registerd.'); ?></p>
			</div>
			<?php echo $this->version_control_confirm->getHtml(); ?>
		</form>
	</div>
</body>
