<body>
	<div id="header">
		<h2 class="article"><?php echo __('Article3'); ?></h2>
		<?php
			if($this->copy_control) {
				echo $this->copy_control->getHtml();
			}
		?>
	</div>

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
			<div id="settings" class="bframe_scroll" style="height:100%">
				<div id="settings-inner" style="height:100%">
					<?php echo $this->settings->getHtml($this->display_mode); ?>
				</div>
			</div>
			<div id="content" class="bframe_adjustparent bframe_scroll" data-param="margin:30">
				<?php
					echo $this->tab_control->getHtml();
					echo $this->editor->getHtml($this->display_mode);
				?>
			</div>
		</div>
	</form>
</body>
