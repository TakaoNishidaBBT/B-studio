<body spellcheck="false">
	<div class="bframe_adjustparent" id="contents_container">
		<div id="contents" class="bframe_adjustparent" data-param="margin:2">
			<div class="main_container bframe_adjustwindow" data-param="margin:0" >
				<form name="F1" id="F1" method="post" action="index.php">
					<?php
						echo $this->tab_control->getHtml();
						echo $this->form->getHtml();
					?>
					<div class="control" style="text-align: right">
						<input type="button" class="cancel-button" value="Cancel" onclick="window.frameElement.deactivate();" />
						<input type="button" class="register-button" value="OK" onclick="bstudio.setProperty('category2')" />
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
