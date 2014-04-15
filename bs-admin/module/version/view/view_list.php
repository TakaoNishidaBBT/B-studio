<body>
	<div id="HEADER">
		<?php echo $this->header->gethtml(); ?>
	</div>

	<div id="LIST_MAIN" class="bframe_adjustparent" param="margin:110">
		<div class="list_container">
			<form name="F1" id="F1" method="post" action="index.php" target="main">
			<?php
				if($this->select_message) {
					echo $this->select_message;
				}
				echo $this->dg->getHtml($this->page_no);
				echo $this->version_control->getHtml();
			?>
			</form>
		</div>
	</div>
</body>
