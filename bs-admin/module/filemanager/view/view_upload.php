<body>
	<div id="upload_content" class="bframe_adjustparent" param="margin:34">
		<form id="form1" method="post" enctype="multipart/form-data">
			<p>アップロードするファイルを選択してください</p>

			<div>
				<input id="uploadFile" type="file" multiple="multiple" name="Filedata[]" class="bframe_uploader" style="display:none;" />
				<input id="selectButton" type="button" value="ファイル選択" />
			</div>
		</form>

		<div id="divStatus"></div>

		<fieldset>
			<legend>Upload Queue</legend>
			<div class="fieldset" id="fsUploadProgress">
		</fieldset>
	</div>
</body>
