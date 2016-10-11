<body>
	<h2 class="version"><?php echo _('Versions'); ?><span><?php echo $this->version_info; ?></span></h2>
	<div class="contents">
		<form name="F1" id="F1" method="post" action="index.php" target="main">
			<div class="info">
				<p>
					■<?php echo _('Publish version: '); ?>
					<span class="bold"><?php echo $this->session['reserved_version_name'] . '　' . $this->session['reserve_datetime']; ?></span>
				</p>
				<p>
					■<?php echo _('Working version: '); ?>
					<span class="bold"><?php echo $this->session['working_version_name']; ?></span>
				</p>
				<p><?php echo $this->action_message; ?></p>
			</div>
			<?php echo $this->version_control_result->getHtml(); ?>
		</form>
	</div>
</body>
