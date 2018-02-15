<body>
	<div id="header">
		<?php echo $this->header->gethtml(); ?>
	</div>

	<div id="list-main" class="bframe_adjustparent bframe_scroll" data-param="margin:85">
		<div class="list-container">
			<form name="F1" id="F1" method="post" action="index.php" target="main">
			<?php
				if($this->select_message) {
					echo $this->select_message;
				}
				echo $this->dg->getHtml($this->page_no);

				if($this->error_message) {
					echo '<p class="error-message">' . $this->error_message . '</p>' . "\n";
				}
				echo $this->version_control->getHtml();
			?>
			</form>
		</div>
	</div>
</body>
