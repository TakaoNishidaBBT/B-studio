<body>
	<div id="header"><h2 class="settings"><?php echo __('Site Admin'); ?><span></span></h2></div>
	<?php
		if($this->action_message) {
			echo '<span class="error-message">' . $this->action_message . '</span>' . "\n";
		}
	?>
	<form name="F1" method="post" action="index.php">
		<div id="control">
			<?php echo $this->control->getHtml(); ?>
		</div>
		<div id="main" class="bframe_adjustparent bframe_scroll" data-param="margin:68">
			<?php
				if($this->error_message) {
					echo $this->error_message;
				}
				echo $this->form->getHtml($this->display_mode);
			?>
		</div>

		<?php echo $this->form->getHiddenHtml(); ?>
	</form>
</body>
