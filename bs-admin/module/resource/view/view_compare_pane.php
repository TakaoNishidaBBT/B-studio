<body>
	<input type="hidden" id="node_id" value="<?php echo $this->request['node_id']; ?>" />
	<input type="hidden" id="target_id" value="<?php echo $this->request['target_id']; ?>" />
	<div id="resource_pane" class="bframe_adjustwindow">
		<ul class="resource_control">
			<li id="display_detail" class="view detail"><a href="#" title="list view"><img src="images/common/view_detail.png" alt="view list" /></a></li>
			<li id="display_thumbnail" class="view thumbnail"><a href="#" title="thumbnail view"><img src="images/common/view_thumbnail.png" alt="view list" /></a></li>
		</ul>
		<div class="bframe_pane_container bframe_adjustparent" param="margin:36">
			<table class="pane">
				<tr>
					<td class="left">
						<div id="bframe_pane_left" class="bframe_compare_pane">
							<div class="bframe_thumbs_container">
								<div class="thumbs">
									<?php echo $this->left->getHtml(); ?>
								</div>
							</div>
						</div>
					</td>
					<td class="right">
						<div id="bframe_pane_right" class="bframe_compare_pane">
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
