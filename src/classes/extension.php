<?php

	/* 
		extension class 
		new slugs, new templates, new bugs

	*/	
	
	class extension {
	
	
		public $data = array();
		public $slug = "";
		public $arguments = array();
		public $templates = array();
		public $route = "";
		public $dir = "";
		public $script = "";
		public $type="";
		public $prio=99;
		
		function __construct($main,$EXTDIR) {
		
			if (!file_exists($EXTDIR."/ext.cfg")) return false;
			$this->dir = $EXTDIR;
			
			$route = "";
			$fh = fopen($EXTDIR."/ext.cfg",'r');
			$thisattr = "";
			while (!(feof($fh))) {
				
				$line = trim(fgets($fh));
				
				if (substr($line,0,2)=="#:" || $line =="") continue;
				if (substr($line,-1)==":" && ( in_array(substr($line,0,-1),$main->get('extattr')) )) {
				
					$thisattr = substr($line,0,-1);
				
				} else {
					
					if ($thisattr == "arguments") {
						foreach (explode(" ",$line) as $arg) {
							$this->arguments[]=$arg;
							$route.="@".$arg."/";
						}
					}
					
					if ($thisattr == "template") {
						list($this->template['file'],$this->template['type']) = explode(" ",$line);
					}
					
					if ($thisattr == "script") {
						foreach (explode(" ",$line) as $script) {
							if (file_exists($EXTDIR.'/'.$script)) include_once($EXTDIR.'/'.$script);
						}
					}
					
					if ($thisattr == "slug" ) $this->slug = $line;
					if ($thisattr == "type" ) $this->type = $line;
				}
			
			}
			
			fclose($fh);
			$ui = $main->get('UI')." ; ./ext/".$this->slug."/ ";
			$main->set('UI',$ui);
			$this->route = $this->slug."/".$route;
			
		}
	
	}

?>