<body>
	<div id="HEADER"><h2 class="settings">基本設定<span><?php echo $this->version_info; ?></span></h2></div>
	<form name="F1" method="post" action="index.php">
		<div id="CONTROL">
			<?php echo $this->result_control->getHtml(); ?>
		</div>

		<div id="MAIN">
			<?php echo $this->result->getHtml(); ?>
		</div>
	</form>
</body>
</html>
