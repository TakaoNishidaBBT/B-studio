<body onload="bstudio.articleDetailInit('description_flag_1', 'description_flag_2')">
	<div id="header"><h2 class="article"><?php echo _('Article2'); ?></h2></div>

	<form name="F1" method="post" action="index.php">
		<div id="control">
			<?php echo $this->control->getHtml(); ?>
		</div>
		<?php
			if($this->action_message) {
				echo '<span class="error-message">' . $this->action_message . '</span>' . "\n";
			}
		?>
		<div id="main" class="bframe_adjustparent" param="margin:108">
			<?php echo $this->form->getHtml($this->display_mode); ?>
		</div>
		<?php echo $this->form->getHiddenHtml(); ?>
	</form>
</body>
