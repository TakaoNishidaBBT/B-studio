<body>
	<input type="hidden" id="node_id" value="<?php echo $this->request['node_id']; ?>" />
	<input type="hidden" id="target_id" value="<?php echo $this->request['target_id']; ?>" />
	<div id="resource_pain" class="bframe_adjustwindow">
		<ul class="upload_control">
			<?php echo $this->disp_change->getHtml(); ?>
		</ul>
		<div class="bframe_pain_container bframe_adjustparent" param="margin:36">
			<div id="bframe_pain_left_detail" class="bframe_compare_pain bframe_adjustparent">
				<div class="bframe_thumbs_container bframe_adjustparent">
					<div class="detail">
						<?php echo $this->left->getHtml(); ?>
					</div>
				</div>
			</div>
			<div id="bframe_pain_right_detail" class="bframe_compare_pain bframe_adjustparent">
				<div class="bframe_thumbs_container bframe_adjustparent">
					<div class="detail">
						<?php echo $this->right->getHtml(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
