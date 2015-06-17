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
		public $main = "";
		
		function __construct($main,$EXTDIR) {
			
			if (!file_exists($EXTDIR."/ext.cfg")) return false;
			$this->dir = $EXTDIR;
			$this->main = $main;
			$route = "";
			$fh = fopen($EXTDIR."/ext.cfg",'r');
			$thisattr = "";
			$this->template['file'] = "";
			$this->template['type'] = "";
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
						
						if (sizeof(explode(" ",$line))==2) {
							list($this->template['file'],$this->template['type']) = explode(" ",$line);
						} else {
							$this->template['file'] = chop($line);
							$this->template['type'] = "text/html";
						}
					}
					
					if ($thisattr == "script") {
						foreach (explode(" ",$line) as $script) {
							if (file_exists($EXTDIR.'/'.$script)) include_once($EXTDIR.'/'.$script);
						}
					}
					
					if ($thisattr=='vars') { 
							
							/* extension variables go to @ext['extslug']*/
							
							if (!isset($thisextvars)) $thisextvars = array();
							$var = explode(' ',$line)[0];
							$value = substr($line,strpos($line,' ')+1);
							$thisextvars[$var]=$value;
							
					}
					
					if ($thisattr=='episode-vars') { 
							
							/* these are vars, this extension adds to episodes */
							
							if (!isset($episodevars)) $episodevars = array();
							$episodevars[]=$line;
							
					}
					
					
					if ($thisattr == "slug" ) $this->slug = $line;
					if ($thisattr == "type" ) $this->type = $line;
					
				}
			
			}
			
			fclose($fh);
			
			if (isset($thisextvars)) {
				$extvars = $main->get('extvars');
				$extvars[$this->slug] = $thisextvars;
				$main->set('extvars',$extvars);
			}
		
			if (isset($episodevars)) {
				$itemattr = $main->get('itemattr');
				foreach ($episodevars as $evar)	$itemattr[]=$evar;
				$main->set('itemattr',$itemattr);
			}
			
			$ui = $main->get('UI').",ext/".$this->slug."/";
			$main->set('UI',$ui);
		
			$this->route = $this->slug."/".$route;
		
			$dict = $main->get('LOCALES').",ext/".$this->slug."/dict/";
			$main->set('LOCALES',$dict);
			
		}
		
		function init() {
			
			$run_func = $this->slug."_init";
			if (function_exists($run_func)) $run_func();
			
			
		}
		
		function run() {
			$run_func = $this->slug."_run";
			if (function_exists($run_func)) $run_func();
		}
		
	}
	
?>