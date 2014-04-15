<body>
	<div id="HEADER">
		<h2 class="article">記事一覧</h2>
		<?php echo $this->header->gethtml(); ?>
	</div>

	<div id="LIST_MAIN" class="bframe_adjustparent" param="margin:110">
		<div class="list_container">
		<?php
			if($this->select_message) {
				echo $this->select_message;
			}
			echo $this->dg->getHtml($this->page_no);
		?>
		</div>
	</div>
</body>
