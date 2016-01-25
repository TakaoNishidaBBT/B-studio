<?php
	if($__contents['php']) {
		eval('?>' . $__contents['php']);
	}
	for($i=0; $i < count($__templates); $i++) {
		eval('?>' . $__templates[$i]['php']);
	}
	if($__start_html || $__innerHTML || $__end_html) {
		eval('?>' . $__start_html . $__innerHTML . $__end_html);
	}
