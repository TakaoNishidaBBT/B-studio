<body>
	<div id="HEADER"><h2 class="user">ユーザ設定</h2></div>

	<form name="F1" method="post" action="index.php">
		<div id="CONTROL">
			<?php echo $this->control->getHtml(); ?>
		</div>
		<div id="MAIN" class="bframe_adjustparent" param="margin:120">
			<?php 
				if($this->action_message) {
					echo '<p class="error-message">' . $this->action_message . '</p>' . "\n";
				}
			?>
			<p><span class="require">※</span>：必須項目</p>
			<?php echo $this->form->getHtml($this->display_mode); ?>
		</div>
		<?php echo $this->form->getHiddenHtml(); ?>

	</form>
</body>
