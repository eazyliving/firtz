<?php

	class feed {
	
		public $attr = array();
		public $episode_slugs = array();
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
			
			$this->attr = $attr;
			
		}
		
		public function loadEpisodes($slug = '') {
			
			$main = $this->main;
			if ($slug=="") {
				foreach ($this->episode_slugs as $slug) {
	
					$episode = new episode($main,$this->feedDir."/".$slug.".epi",$this->attr,$slug);
				
					$this->episodes[$episode->item['slug']]= $episode;
					
				}
			} else {
				$episode = new episode($main,$this->feedDir."/".$slug.".epi",$this->attr,$slug);
				$this->episodes[$episode->item['slug']]= $episode;
			}
		}
		
		
		public function findEpisodes() {
		
			$items = array();
			$itemfiles = glob($this->feedDir.'/*.epi');
			$this->episode_slugs=array();

		
			foreach ( $itemfiles as $EPISODEFILE ) {
			
				$slug = basename($EPISODEFILE,'.epi');
				$this->episode_slugs[]=$slug;
			}
			
		
		}
		
		public function renderRSS2($audioformat = '') {
		
			$main = $this->main;
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
			echo Template::instance()->render('rss2.xml','application/xml');
			
		}
		
		public function renderHTML() {
		
			$main = $this->main;
			$main->set('feedattr',$this->attr);
			
			if ($main->exists('epi')) {
				$items= array ( $this->episodes[$main->get('epi')] );
			} else {
				foreach ($this->episodes as $episode) $items[]=$episode->item;
			}
			$main->set('items',$items);
			echo Template::instance()->render('site.html');
			
		}
	}
	
?>