<?php

	/* 
		application class 
		some globals and extensions

	*/	
	
	class firtz {
	
		public $data = array();
		public $extensions = array();
		public $attr = array();
		public $main = "";
		function __construct($main) {
			
			$this->markdown = new Parsedown();
			$this->main = $main;
			$this->BASEURL =$main->get('scheme').'://'.$main->get('HOST');
			if (substr($this->BASEURL,-1) != "/") $this->BASEURL.='/';

			if (dirname($_SERVER['SCRIPT_NAME']) != "/") $this->BASEURL.=trim(dirname($_SERVER['SCRIPT_NAME']),"/");

			if (substr($this->BASEURL,-1) != "/") $this->BASEURL.='/';
          	  
			$this->BASEPATH = $_SERVER['DOCUMENT_ROOT'];
			if (substr($this->BASEPATH,-1) != "/") $this->BASEPATH.='/';
			if (dirname($_SERVER['SCRIPT_NAME']) != "/") $this->BASEPATH.=dirname($_SERVER['SCRIPT_NAME']);
			if (substr($this->BASEPATH,-1) != "/") $this->BASEPATH.='/';

			$main->set('BASEPATH',$main->fixslashes($this->BASEPATH));
			$main->set('BASEURL',$main->fixslashes($this->BASEURL));
			
			foreach ($main->get('firtzattr_default') as $var) $attr[$var]="";
			$attr['feedalias'] = array();
			if (file_exists('./firtz.cfg')) {
				
				# firtz global config file... at last :(
				
					foreach ($main->get('firtzattr_default') as $var) $attr[$var]="";
			
					$fh = fopen('./firtz.cfg','r');
					
					$thisattr="";
					
					while (!feof($fh)) {
						
						$line = trim(fgets($fh));
						
						if ($line=="" || substr($line,0,2)=="#:") continue;
						
						if ($line=="---end---") {
							break;
						}
						
						/* a new attribute */
						 
						if (substr($line,-1)==":" && in_array(substr($line,0,-1),$main->get('firtzattr_default'))) {
							$thisattr = substr($line,0,-1);
							$attr[$thisattr]="";
						} elseif ($thisattr == "feedalias") {
							$alias = explode(" ",$line);
							if (count($alias)==3) $attr['feedalias'][] = array('format'=>$alias[0],'feed'=>$alias[1],'route'=>$alias[2]);
						} else {
							/* concat a new line to existing attribute */
							
							if ($thisattr!="") $attr[$thisattr] .= ($attr[$thisattr]!="") ? "\n".$line : $line;
						}
						
					}
					
					fclose($fh);
		
					/* sanitize data */
					
				
			}
			$this->attr=$attr;
		}
	
		function time_difference($date) {
		
			if(empty($date)) {
				return "No date provided";
			}
			
			$periods         = array("s", "m", "h", "d", " weeks", " months", " years", " dc");
			$lengths         = array("60","60","24","7","4.35","12","10");
			
			$now             = time();
			$unix_date         = strtotime($date);
			
			   // check validity of date
			if(empty($unix_date)) {  
				return "Bad date";
			}
		 
			// is it future date or past date
			if($now > $unix_date) {  
				$difference     = $now - $unix_date;
				$tense         = "";
				
			} else {
				$difference     = $unix_date - $now;
				$tense         = "from now";
			}
			
			for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
				$difference /= $lengths[$j];
			}
			
			$difference = round($difference);
			
			if($difference != 1) {
				$periods[$j].= "";
			}
			
			return "$difference$periods[$j] {$tense}";
		}
			
		function loadAllTheExtensions($main) {
		
			if (!file_exists($main->get('EXTDIR'))) return;
			
			foreach (glob($main->get('EXTDIR').'/*',GLOB_ONLYDIR) as $dir) {
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
