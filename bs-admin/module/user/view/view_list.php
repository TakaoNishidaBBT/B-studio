<body>
	<div id="HEADER">
		<?php echo $this->header->gethtml(); ?>
	</div>

	<div id="LIST_MAIN" class="bframe_adjustparent" param="margin:110">
		<div class="list_container">
			<?php
				if($this->select_message) {
					echo $this->select_message;
				}
			?>
			<form name="F1" id="F1" method="post" action="index.php" target="main">
				<?php
					echo $this->dg->getHtml($this->page_no);
				?>
			</form>
		</div>
	</div>
</body>
