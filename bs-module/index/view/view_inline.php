<?php
	for($i=count($__templates)-1 ; $i>=0 ; $i--) {
		eval('?>' . $__templates[$i]['php']);
	}
	if($__start_html) {
		eval('?>' . $__start_html);
	}
	if($__innerHTML) {
		// To protect php tag
		$__innerHTML = str_replace('<?', '<!-----?', $__innerHTML);
		$__innerHTML = str_replace('?>', '?----->', $__innerHTML);
		echo $__innerHTML;
	}
	if($__end_html) {
		eval('?>' . $__end_html);
	}
