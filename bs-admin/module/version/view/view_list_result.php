<body>
	<h2 class="version">バージョン管理</h2>
	<div id="LIST_MAIN">
		<div class="contents">
			<form name="F1" id="F1" method="post" action="index.php" target="main">
				<p>■公開バージョン：
					<strong><?php echo $this->session['reserved_version_name'] . '　' . $this->session['reserve_datetime']; ?></strong>
				</p>
				<p>■作業中バージョン：
					<strong><?php echo $this->session['working_version_name']; ?></strong>
				</p>
				<p>に設定しました。</p>

				<?php echo $this->version_control_result->getHtml(); ?>
			</form>
		</div>
	</div>
</body>
