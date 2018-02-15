<body>
	<div id="header">
		<?php echo $this->header->gethtml(); ?>
	</div>

	<div id="list-main" class="bframe_adjustparent bframe_scroll" data-param="margin:85">
		<div class="list-container">
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
