<body spellcheck="false" onload="bframe.effect.fadeIn(document.body, 100, 0, 100, 50);" style="opacity:0">
	<div class="bframe_adjustparent bframe_shortcut" id="contents_container">
		<div id="contents" class="bframe_adjustparent">
			<div class="main_container bframe_adjustwindow" data-param="margin:0" >
				<form name="F1" id="F1" method="post" action="index.php">
					<?php
						echo $this->tab_control->getHtml();
						echo $this->form->getHtml();
					?>
				</form>
			</div>
		</div>
	</div>
</body>
