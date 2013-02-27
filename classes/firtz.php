<?php

	/* 
		application class 
		some globals and extensions

	*/	
	
	class firtz {
	
	
		public $data = array();
		public $extensions = array();
		
		function __construct() {
		
			global $main;
			
			$this->main = $main;
			$main->set('firtz',$this);
			$this->BASEURL ="http://".$main->get('HOST');
			$this->BASEPATH = $_SERVER['DOCUMENT_ROOT'];
			if (dirname($_SERVER['SCRIPT_NAME']) != "/") $this->BASEPATH.=dirname($_SERVER['SCRIPT_NAME']);

			$main->set('BASEPATH',$this->BASEPATH);
			
		}
	
		function loadAllTheExtensions() {
		
			$main = $this->main;
			if (!file_exists($main->get('BASEPATH').'/ext/')) return;
			
			foreach (glob($main->get('BASEPATH').'/ext/*',GLOB_ONLYDIR) as $dir) {
				if (substr(basename($dir),0,1)=="_") continue;
				$extension = new extension ($main,$dir);
				
				if ($extension===false) {
					die("failed to load extension at $dir");
				} else {
					foreach ($this->extensions as $ext) {
						if ($ext->slug == $extension->slug) {
							die("failed to load extension at $dir - slug $this->slug already registered!");
						}
					}
					$this->extensions[$extension->slug]=$extension;
						
				}
			}
			
		}
		
	}

?>