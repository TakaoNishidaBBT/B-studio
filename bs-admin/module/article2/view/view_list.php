<body>
	<div id="header">
		<h2 class="article"><?php echo __('Article2'); ?></h2>
		<?php echo $this->header->gethtml(); ?>
	</div>

	<div id="list-main" class="bframe_adjustparent" param="margin:110">
		<div class="list-container">
		<?php
			if($this->select_message) {
				echo $this->select_message;
			}
			echo $this->dg->getHtml($this->page_no);
		?>
		</div>
	</div>
</body>
