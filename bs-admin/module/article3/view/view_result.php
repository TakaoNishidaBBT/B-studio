<body>
	<div id="header"><h2 class="article">記事3詳細</h2></div>
	<form name="F1" method="post" action="index.php">
		<div id="control">
			<?php echo $this->result_control->getHtml(); ?>
		</div>

		<div id="main">
			<?php echo $this->result->getHtml(); ?>
		</div>
	</form>
</body>
