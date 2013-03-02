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
			if (substr($this->BASEURL,-1) != "/") $this->BASEURL.='/';
			if (dirname($_SERVER['SCRIPT_NAME']) != "/") $this->BASEURL.=dirname($_SERVER['SCRIPT_NAME']);
			if (substr($this->BASEURL,-1) != "/") $this->BASEURL.='/';
              
			$this->BASEPATH = $_SERVER['DOCUMENT_ROOT'];
			if (substr($this->BASEPATH,-1) != "/") $this->BASEPATH.='/';
			if (dirname($_SERVER['SCRIPT_NAME']) != "/") $this->BASEPATH.=dirname($_SERVER['SCRIPT_NAME']);
			if (substr($this->BASEPATH,-1) != "/") $this->BASEPATH.='/';

			$main->set('BASEPATH',$main->fixslashes($this->BASEPATH));
			$main->set('BASEURL',$main->fixslashes($this->BASEURL));
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
