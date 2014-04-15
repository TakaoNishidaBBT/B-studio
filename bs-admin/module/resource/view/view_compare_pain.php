<body>
	<input type="hidden" id="node_id" value="<?php echo $this->request['node_id']; ?>" />
	<input type="hidden" id="target_id" value="<?php echo $this->request['target_id']; ?>" />
	<div id="resource_pain" class="bframe_adjustwindow">
		<ul class="upload_control">
			<?php echo $this->disp_change->getHtml(); ?>
		</ul>
		<div class="bframe_pain_container bframe_adjustparent" param="margin:36">
			<table class="pain">
				<tr>
					<td class="left">
						<div id="bframe_pain_left" class="bframe_compare_pain">
							<div class="bframe_thumbs_container">
								<div class="thumbs">
									<?php echo $this->left->getHtml(); ?>
								</div>
							</div>
						</div>
					</td>
					<td class="right">
						<div id="bframe_pain_right" class="bframe_compare_pain">
							<div class="bframe_thumbs_container">
								<div class="thumbs">
									<?php echo $this->right->getHtml(); ?>
								</div>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
</body>
