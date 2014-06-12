<body>
	<h2 class="version">バージョン管理</h2>
	<div class="contents">
		<form name="F1" id="F1" method="post" action="index.php" target="main">
			<div class="info">
				<p>
					■公開バージョン：
					<strong><?php echo $this->reserved_version . '　' . $this->reserve_datetime; ?></strong>
				</p>
				<p>
					■作業中バージョン：
					<strong><?php echo $this->working_version; ?></strong>
				</p>
				<p>に設定します。</p>
			</div>
			<?php echo $this->version_control_confirm->getHtml(); ?>
		</form>
	</div>
</body>
