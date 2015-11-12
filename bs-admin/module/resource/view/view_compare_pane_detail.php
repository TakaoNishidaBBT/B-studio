<body>
	<input type="hidden" id="node_id" value="<?php echo $this->request['node_id']; ?>" />
	<input type="hidden" id="target_id" value="<?php echo $this->request['target_id']; ?>" />
	<div id="resource_pane" class="bframe_adjustwindow">
		<ul class="upload_control">
			<?php echo $this->disp_change->getHtml(); ?>
		</ul>
		<div class="bframe_pane_container bframe_adjustparent" param="margin:36">
			<div id="bframe_pane_left_detail" class="bframe_compare_pane bframe_adjustparent">
				<div class="bframe_thumbs_container bframe_adjustparent">
					<div class="detail">
						<?php echo $this->left->getHtml(); ?>
					</div>
				</div>
			</div>
			<div id="bframe_pane_right_detail" class="bframe_compare_pane bframe_adjustparent">
				<div class="bframe_thumbs_container bframe_adjustparent">
					<div class="detail">
						<?php echo $this->right->getHtml(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
