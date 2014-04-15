<body>
	<div id="HEADER"><h2 class="version">バージョン詳細<span><?php echo $this->version_info ?></span></h2></div>
	<form name="F1" method="post" action="index.php">
		<div id="CONTROL">
			<?php echo $this->result_control->getHtml(); ?>
		</div>

		<div id="MAIN">
			<?php echo $this->message; ?>
		</div>
	</form>
</body>
