<body onload="bstudio.articleDetailInit('description_flag_1', 'description_flag_2')">
	<div id="header"><h2 class="article"><?php echo __('Article2'); ?></h2></div>

	<form name="F1" method="post" action="index.php">
		<div id="control">
			<?php echo $this->control->getHtml(); ?>
		</div>
		<?php
			if($this->action_message) {
				echo '<span class="error-message">' . $this->action_message . '</span>' . "\n";
			}
		?>
		<div id="main" class="bframe_adjustparent" data-param="margin:68">
			<div id="settings" class="bframe_adjustparent bframe_scroll">
				<?php echo $this->settings->getHtml($this->display_mode); ?>
			</div>
			<div id="content" class="bframe_adjustparent bframe_scroll" data-param="margin:30">
				<?php echo $this->editor->getHtml($this->display_mode); ?>
			</div>
		</div>
	</form>
</body>
