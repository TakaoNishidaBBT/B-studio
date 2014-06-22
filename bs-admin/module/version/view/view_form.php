<body>
	<div id="header"><h2 class="version">バージョン詳細<span><?php echo $this->version_info ?></span></h2></div>

	<form name="F1" method="post" action="index.php">
		<div id="control">
			<?php echo $this->control->getHtml(); ?>
		</div>
		<?php 
			if($this->action_message) {
				echo '<span class="error-message">' . $this->action_message . '</span>' . "\n";
			}
		?>
		<div id="main" class="bframe_adjustparent" param="margin:120">
			<p><span class="require">※</span>：必須項目</p>
			<?php echo $this->form->getHtml($this->display_mode); ?>
		</div>
		<?php echo $this->form->getHiddenHtml(); ?>

	</form>
</body>
