<?php 

	function embed_init() {
		
		global $main;
		$main->set('XFRAME','GOFORIT');
		
	}

	function embed_run() {
		global $main;
		

		if ($main->get('epi') == '') $main->set('epi','latest');
		if ($main->get('epi') == 'latest') {
			$newitems = $main->get('items');
			$main->set('items',array($newitems[0]));
			return;
		}	
		
		$newitems = array();
		$epi_slug = $main->get('epi');
		foreach ($main->get('items') as $item) {
		
			if ($item['slug'] == $epi_slug) {
				$newitems[] = $item;
				break;
			}
		
		}	
		$main->set('items',$newitems);
		
	}
	
	
	

?>