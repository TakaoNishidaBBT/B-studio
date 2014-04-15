<?php
	if($__contents['php']) {
		eval('?>' . $__contents['php']);
	}
	for($i=count($__templates)-1 ; $i>=0 ; $i--) {
		eval('?>' . $__templates[$i]['php']);
	}
	if($__start_html || $__innerHTML || $__end_html) {
		eval('?>' . $__start_html . $__innerHTML . $__end_html);
	}
