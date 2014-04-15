<body>
	<div id="HEADER"><h2 class="settings">サイト管理者設定<span></span></h2></div>
	<?php
		if($this->action_message) {
			echo '<span class="error-message">' . $this->action_message . '</span>' . "\n";
		}
	?>
	<form name="F1" method="post" action="index.php">
		<div id="CONTROL">
			<?php echo $this->control->getHtml(); ?>
		</div>
		<div id="MAIN" class="bframe_adjustparent" param="margin:120">
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
