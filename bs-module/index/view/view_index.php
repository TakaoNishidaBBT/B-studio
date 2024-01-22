<?php
	if(is_array($__templates)) {
		for($__i=0; $__i < count($__templates); $__i++) {
			eval('?>' . $__templates[$__i]['php']);
		}
	}
	if(isset($__contents['php']) && $__contents['php']) {
		eval('?>' . $__contents['php']);
	}
	if(isset($__start_html) || isset($__innerHTML) || isset($__end_html)) {
		eval('?>' . $__start_html . $__innerHTML . $__end_html);
	}
