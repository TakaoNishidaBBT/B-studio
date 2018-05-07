<body>
	<div id="header"><h2 class="article"><?php echo __('Mail Settings'); ?></h2></div>
	<form name="F1" method="post" action="index.php">
		<div id="control">
			<?php echo $this->result_control->getHtml(); ?>
		</div>

		<div id="main">
			<div class="wrap">
				<?php echo $this->result->getHtml(); ?>
			</div>
		</div>
	</form>
</body>
