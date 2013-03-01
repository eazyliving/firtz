<?php

	class firtzMarkdown extends Markdown {
	
		function renderString($string) {
		
			$str=preg_replace_callback(
				'/(<code.*?>.+?<\/code>|'.
				'<[^>\n]+>|\([^\n\)]+\)|"[^"\n]+")|'.
				'\\\\(.)/s',
				function($expr) {
					// Process escaped characters
					return empty($expr[1])?$expr[2]:$expr[1];
				},
					$this->build($string)
			);
			return $this->snip($str);
	
		}
	}
?>