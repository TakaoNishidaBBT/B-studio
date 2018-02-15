<body>
	<div id="header"><h2 class="user"><?php echo __('Users'); ?></h2></div>

	<form name="F1" method="post" action="index.php">
		<div id="control">
			<?php echo $this->control->getHtml(); ?>
		</div>
		<div id="main" class="bframe_adjustparent bframe_scroll" data-param="margin:68">
			<?php 
				if($this->action_message) {
					echo '<p class="error-message">' . $this->action_message . '</p>' . "\n";
				}
			?>
			<?php echo $this->form->getHtml($this->display_mode); ?>
		</div>
		<?php echo $this->form->getHiddenHtml(); ?>

	</form>
</body>
