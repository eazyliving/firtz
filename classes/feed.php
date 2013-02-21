<?php

	class feed {
	
		public $attr = array();
		
		public $episode_slugs = array();
		public $auphonic_slugs = array();
		
		public $episodes = array();
		
		public $feedDir = "";
		public $main = "";
		
		public function __construct($main,$slug,$configfile) {
		
			if (!file_exists($configfile)) {
				echo "Config for $slug not found (missing $configfile)";
				die();
			}
			$this->main = $main;
			$this->feedDir=dirname($configfile);
			
			$attr=array();
		
			foreach ($main->get('feedattr_default') as $var) $attr[$var]="";
			
			$fh = fopen($configfile,'r');
			
			$thisattr="";
			$categories = array();
			
			while (!feof($fh)) {
				
				$line = trim(fgets($fh));
				if ($line=="" || substr($line,0,2)=="#:") continue;
				
				if ($line=="---end---") {
					break;
				}
				if (substr($line,-1)==":" && in_array(substr($line,0,-1),$main->get('feedattr_default'))) {
					$thisattr = substr($line,0,-1);
					$attr[$thisattr]="";
					
				} elseif ($thisattr=="category") {
					
					$thiscat = explode("->",$line);
					if (sizeof($thiscat)>1) {
						$categories[]=array ( 'a'=>trim($thiscat[0]), 'b' => trim($thiscat[1])) ;
					} else {
						$categories[]=array ( 'a'=>trim($thiscat[0]), 'b' => '');
					}
					
				} else {
					if ($thisattr!="") $attr[$thisattr] .= ($attr[$thisattr]!="") ? "\n".$line : $line;
				}
				
			}
			fclose($fh);
		
			$attr['categories']=$categories;
			
			$attr['slug']=$slug;
			$attr['link'] = $main->get('REALM');
			$attr['self'] = $main->get('REALM');
			
			if ($attr['flattrid']!="") {
			
				$attr['flattrlanguage'] = ($attr['language']!="") ? str_replace("-","_",$attr['language']) : "";
				$attr['flattrdescription'] = rawurlencode($attr['description']);
				$attr['flattrkeywords'] = rawurlencode($attr['keywords']);
				$attr['flattrlink'] = rawurlencode($main->get('BASEURL').'/show/'.$attr['slug']);
				$attr['flattrtitle'] = rawurlencode($attr['title']);
			}
			
			$attr['audioformats']=explode(" ",$attr['formats']);
			$attr['maintype']=$attr['audioformats'][0];
			$attr['alternate']= $attr['audioformats'];
			
			$attr['baserel']="http://".$main->get('HOST')."/".$slug."/";
			
			if ($attr['image']!="") {
				$image = new image($attr['image']);
			}
			
			if (file_exists(dirname($configfile)."/".$slug.".css")) {
				$attr['sitecss']=$main->get('BASEURL')."/feeds/".$slug."/".$slug.".css";
			} else {
				$attr['sitecss']=$main->get('BASEURL').'/css/bootstrap.min.css';
			}
			
			if (file_exists(dirname($configfile)."/".$slug.".html")) {
				$main->set('sitetemplate',$slug.".html");
			} else {
				$main->set('sitetemplate','site.html');
			}
			if ($attr['auphonic-mode']=="") $attr['auphonic-mode']='off';
			$this->attr = $attr;
			
		}
		
		public function loadEpisodes($slug = '') {
			
			$main = $this->main;
			$maxPubDate = "";
		
			if ($slug!='') {
				$this->episode_slugs = array_intersect_key(array(0=>$slug),$this->episode_slugs);
				$this->auphonic_slugs= array_intersect_key(array(0=>$slug),$this->auphonic_slugs);
			}
			
			switch ($this->attr['auphonic-mode']) {
				case "off":
				case "":
					foreach ($this->episode_slugs as $slug) {
						$episode = new episode($main,$this->feedDir."/".$slug.".epi",$this->attr,$slug);
						if ($episode->item) $this->episodes[$episode->item['slug']]= $episode;
					}
					break;
			
				case "full":
					/* todo: overwrite episode attributes with data from config file */
					foreach ($this->auphonic_slugs as $slug) {
						$episode = new episode($main,$this->attr['auphonic-path']."/".$slug.".json",$this->attr,$slug,true);
						if ($episode->item) $this->episodes[$episode->item['slug']]= $episode;
					}
					
					foreach ($this->episode_slugs as $slug) {
						$episode = new episode($main,$this->feedDir."/".$slug.".epi",$this->attr,$slug,false);
						if ($episode->item) $this->episodes[$episode->item['slug']]= $episode;
					}
					
					break;
			
				case "exclusive": 
					foreach ($this->auphonic_slugs as $slug) {
						$episode = new episode($main,$this->attr['auphonic-path']."/".$slug.".json",$this->attr,$slug,true);
						if ($episode->item) $this->episodes[$episode->item['slug']]= $episode;
					}
					break;
					
				case "episodes": 
					
					/* todo: overwrite episode attributes with data from config file */
					
					foreach ($this->episode_slugs as $slug) {
						if (in_array($slug,$this->auphonic_slugs)) {
							$episode = new episode($main,$this->attr['auphonic-path']."/".$slug.".json",$this->attr,$slug,true);
							if ($episode->item) $this->episodes[$episode->item['slug']]= $episode;
						}
					}
					break;
			}
			
			
		
			# Sort episodes by pubDate
			
			function sortByPubDate($a,$b) {
				return (strtotime($a->item['pubDate']) < strtotime($b->item['pubDate']) );
			}
			uasort($this->episodes,'sortByPubDate');
			
			$lastupdate = 0;
			foreach ($this->episodes as $episode) {
				$update = strtotime($episode->item['pubDate']);
				if ($update>$lastupdate) $lastupdate = $update;
			}
			$this->attr['lastupdate'] = date('c', $lastupdate);
		}
		
		
		public function findEpisodes() {
		
			$items = array();
			
			if ($this->attr['auphonic-path']!="" && file_exists($this->attr['auphonic-path']) && $this->attr['auphonic-mode']!="" && $this->attr['auphonic-mode']!="off") {
			
				$auphonic_episodes = glob ($this->attr['auphonic-path']."/".$this->attr['auphonic-glob']);
				foreach ($auphonic_episodes as $json) {
				
					$slug = basename ($json,'.json');
					$this->auphonic_slugs[]=$slug;
					
				}
				
			}
		
			$itemfiles = glob($this->feedDir.'/*.epi');
			$this->episode_slugs=array();

			foreach ( $itemfiles as $EPISODEFILE ) {
				$slug = basename($EPISODEFILE,'.epi');
				
				switch ($this->attr['auphonic-mode']) {
						
					case "episode": 
						if (array_key_exists($slug,$this->auphonic)) $this->episode_slugs[]=$slug;
						break;
					
					case "full": 
					case "off":
						$this->episode_slugs[]=$slug;
						break;
						
					case "exclusive": 
						break;
					
				}
				
				$this->episode_slugs[]=$slug;
			}
			rsort($this->episode_slugs);
		
		}
		
		public function runExt($main,$extension) {
		
			$main = $this->main;
			$this->attr['self']=$main->get('BASEURL').$this->attr['slug']."/".$extension->slug."/".$main->get('audio');

			$audioformat = ($main->get('audio')?:$this->attr['audioformats'][0]);
			
			$this->attr['audioformat']=$audioformat;
			$main->set('feedattr',$this->attr);

			$items=array();
			foreach ($this->episodes as $episode) {
				$item = $episode->item;
				$item['enclosure'] = $item[$audioformat];
				$items[]=$item;
			}
			$main->set('items',$items);
			
			echo Template::instance()->render($extension->template['file'],$extension->template['type']);
			
		}
		
		
		public function renderRSS2($audioformat = '',$ret=false) {
		
			$main = $this->main;
			$this->attr['self']=$main->get('BASEURL').$this->attr['slug']."/".$audioformat;
			
			if ($audioformat == '') $audioformat = $this->attr['audioformats'][0];
			$this->attr['audioformat']=$audioformat;
			$main->set('feedattr',$this->attr);

			$items=array();
			
			foreach ($this->episodes as $episode) {
			
				$item = $episode->item;
				$item['enclosure'] = $item[$audioformat];
				$items[]=$item;
			}
			$main->set('items',$items);
			if ($ret===false) {
				echo Template::instance()->render('rss2.xml','application/xml');
			} else {
				return Template::instance()->render('rss2.xml');
			}
		}
		
		public function renderHTML($ret=false) {
		
			$main = $this->main;
			$main->set('feedattr',$this->attr);
			$items = array();
			if ($main->exists('epi') && $main->get('epi')!="") {
				$items = array ( $this->episodes[$main->get('epi')]->item );
			} else {
				foreach ($this->episodes as $episode) $items[]=$episode->item;
			}
		
			$main->set('items',$items);
			
			if ($ret===false) {
				echo Template::instance()->render('site.html');
			} else {
				return Template::instance()->render('site.html');
			}
		}
	}
	
?>