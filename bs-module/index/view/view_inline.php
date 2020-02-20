<?php
	for($__i=0; $__i < count($__templates); $__i++) {
		eval('?>' . $__templates[$__i]['php']);
	}
	if($__start_html) {
		eval('?>' . $__start_html);
	}
	if($__innerHTML) {
		// To protect php tag
		$__innerHTML = str_replace('<?', '<!-----?', $__innerHTML);
		$__innerHTML = str_replace('?>', '?----->', $__innerHTML);
		$__innerHTML = str_replace('<script', '<!-- script', $__innerHTML);
		$__innerHTML = str_replace('</script>', '</script -->', $__innerHTML);
		echo $__innerHTML;
	}
	if($__end_html) {
		eval('?>' . $__end_html);
	}
