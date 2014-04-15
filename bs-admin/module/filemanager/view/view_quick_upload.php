<body>
	<script type="text/javascript">
	window.parent.CKEDITOR.tools.callFunction(
		<?php echo $response["CKEditorFuncNum"]; ?>,
		'<?php echo $response["url"]; ?>',
		'<?php echo $response["message"]; ?>'
	);
	</script>
</body>
