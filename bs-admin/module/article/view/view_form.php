<body onload="articleDetailInit('description_flag_1', 'description_flag_2')">
	<div id="HEADER"><h2 class="article">記事詳細</h2></div>

	<form name="F1" method="post" action="index.php">
		<div id="CONTROL">
			<?php echo $this->control->getHtml(); ?>
		</div>
		<?php
			if($this->action_message) {
				echo '<span class="error-message">' . $this->action_message . '</span>' . "\n";
			}
		?>
		<div id="MAIN" class="bframe_adjustparent" param="margin:120">
			<p><span class="require">※</span>：必須項目</p>
			<?php echo $this->form->getHtml($this->display_mode); ?>
		</div>
		<?php echo $this->form->getHiddenHtml(); ?>
	</form>
</body>
