<body>
	<div id="HEADER"><h2 class="user">ユーザマスタ</h2></div>
	<form name="F1" method="post" action="index.php">
		<div id="CONTROL">
			<?php echo $this->result_control->getHtml(); ?>
		</div>

		<div id="MAIN">
			<?php echo $this->result->getHtml(); ?>
		</div>
	</form>
</body>
