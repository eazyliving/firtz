<?php

	/* feed class */
	/* in fact it's more of a podcast class... */
	/* it does not represent a single feed to output but all data needed to create a podcast with multiple feeds */
	
	class feed {
	
		public $attr = array();
		
		public $episode_slugs = array();
		public $auphonic_slugs = array();
		public $real_slugs = array();
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
		
			/* populate attributes */
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
				
				/* a new attribute */
				 
				if (substr($line,-1)==":" && in_array(substr($line,0,-1),$main->get('feedattr_default'))) {
					$thisattr = substr($line,0,-1);
					$attr[$thisattr]="";
					
				} elseif ($thisattr=="category") {
					
					/* append a category line */
					
					$thiscat = explode("->",$line);
					if (sizeof($thiscat)>1) {
						$categories[]=array ( 'a'=>trim($thiscat[0]), 'b' => trim($thiscat[1])) ;
					} else {
						$categories[]=array ( 'a'=>trim($thiscat[0]), 'b' => '');
					}
				} elseif ($thisattr == "bitlove") {
				
					/* bitlove information */

					$bitlove = explode(" ",$line);

					if (sizeof($bitlove)==3) $attr['bitlove'][$bitlove[0]] = array('format'=>$bitlove[0],'user'=>$bitlove[1],'feed'=>$bitlove[2]);

				} else {
					/* concat a new line to existing attribute */
					
					if ($thisattr!="") $attr[$thisattr] .= ($attr[$thisattr]!="") ? "\n".$line : $line;
				}
				
			}
			#echo "<pre>".print_r($attr,1);exit;
			fclose($fh);
		
			/* sanitize data */
			
			$attr['categories']=$categories;
			
			$attr['slug']=$slug;
			$attr['link'] = $main->get('REALM');
			$attr['self'] = $main->get('REALM');
			
			if ($attr['flattrid']!="") {
			
				$attr['flattrlanguage'] = ($attr['language']!="") ? str_replace("-","_",$attr['language']) : "";
				$attr['flattrdescription'] = rawurlencode($attr['description']);
				$attr['flattrkeywords'] = rawurlencode($attr['keywords']);
				$attr['flattrlink'] = rawurlencode($main->get('BASEURL').$attr['slug'].'/show');
				$attr['flattrtitle'] = rawurlencode($attr['title']);
			}
			
			$attr['audioformats']=explode(" ",$attr['formats']);
			$attr['maintype']=$attr['audioformats'][0];
			$attr['alternate']= $attr['audioformats'];
			
			/* fishy - might take a look into that */
			$attr['baserel']="http://".$main->get('HOST')."/".$slug."/";
			
			if (file_exists(dirname($configfile)."/".$slug.".css")) {
				/*	yet undocumented ;) 
					if a $slug.css file exists in feed directory, this one replaces the
					standard bootstrap.css
				*/
				
				$attr['sitecss']=$main->get('BASEURL')."feeds/".$slug."/".$slug.".css";
			} else {
				$attr['sitecss']=$main->get('BASEURL').'css/bootstrap.min.css';
			}
			
			if (file_exists(dirname($configfile)."/".$slug.".html")) {
				/*	yet undocumented and non working;) 
					if a $slug.html file exists in feed directory, this one replaces the
					standard site.html template
				*/
				$main->set('sitetemplate',$slug.".html");
			} else {
				$main->set('sitetemplate','site.html');
			}
			
			if ($attr['auphonic-mode']=="") $attr['auphonic-mode']='off';
			
			$this->attr = $attr;
			
		}
		
		public function preloadEpisodes() {
			
			$main = $this->main;
			$maxPubDate = "";
			$realSlugs = array();
			
			/* Just a simple PreLoad to determine, which slugs are real
				needed for pageing mode to reduce load */
			
			
			switch ($this->attr['auphonic-mode']) {
				
				/* standard mode. just load all .epi files */
				
				case "off":
				case "":
					foreach ($this->episode_slugs as $slug)	$realSlugs[]=$slug;
					break;
				
				/* full mode: load auphonic and epi files.
					if there's an epi file whith the same name as an auphonic file,
					the data in epi file overwrites attributes from auphonic.
				*/
				
				case "full":
					
					foreach ($this->auphonic_slugs as $slug) $realSlugs[]=$slug;
					
					foreach ($this->episode_slugs as $slug) {
						
						if (!in_array($slug,$this->auphonic_slugs)) $realSlugs[]=$slug;
						
					}
					
					break;
				
				/* exclusive mode: only auphonic files are read, epi ignored */
				
				case "exclusive": 
					foreach ($this->auphonic_slugs as $slug) $realSlugs[]=$slug;
					
					break;
				
				/*	episode mode: like full, but only auphonic episodes are loaded,
					that also exist as episode files. epi data overwrites auphonic attributes
					this is the standard mode, if auphonic is in remote mode
				*/
				
				case "episodes": 
				
					foreach ($this->episode_slugs as $slug) {
						if (in_array($slug,$this->auphonic_slugs)) $realSlugs[]=$slug;
					}
					break;
			}
			
			$this->real_slugs = $realSlugs;
		
		}
		
		public function loadEpisodes($slug = '') {
			
			$main = $this->main;
			$maxPubDate = "";
			if ($slug!='') {
				
				/*	reduce slugs array to this one episode
					atm the only case this happens is calling /$feed/show/$episodeslug/
					also to be used, when paging is implemented
				*/
				if (!is_array($slug)) {
					$this->episode_slugs = array_intersect_key(array(0=>$slug),$this->episode_slugs);
					$this->auphonic_slugs= array_intersect_key(array(0=>$slug),$this->auphonic_slugs);
				} else {
					$this->episode_slugs = array_intersect_key($slug,$this->episode_slugs);
					$this->auphonic_slugs= array_intersect_key($slug,$this->auphonic_slugs);
				}
				
			}
			/* handle loading of episodes depending on auphonic mode */
			
			switch ($this->attr['auphonic-mode']) {
				
				/* standard mode. just load all .epi files */
				
				case "off":
				case "":
					foreach ($this->episode_slugs as $slug) {
						$episode = new episode($main,$this->feedDir."/".$slug.".epi",$this->attr,$slug);
						if ($episode->item) $this->episodes[$episode->item['slug']]= $episode;
					}
					break;
				
				/* full mode: load auphonic and epi files.
					if there's an epi file whith the same name as an auphonic file,
					the data in epi file overwrites attributes from auphonic.
				*/
				
				case "full":
					
					foreach ($this->auphonic_slugs as $slug) {
						$episode = new episode($main,$this->attr['auphonic-path']."/".$slug.".json",$this->attr,$slug,true);
						if ($episode->item) $this->episodes[$episode->item['slug']] = $episode;
					}
					
					foreach ($this->episode_slugs as $slug) {
						
						if (!in_array($slug,$this->auphonic_slugs)) {
							
							/* exclusive .epi */	
							$episode = new episode($main,$this->feedDir."/".$slug.".epi",$this->attr,$slug,false);
							if ($episode->item) $this->episodes[$episode->item['slug']]= $episode;
							
						} else {
						
							/* auphonic with same slug exists. take values from epi to overwrite args in auphonic episode */
						
							$old_episode = $this->episodes[$slug];
							$episode = new episode($main,$this->feedDir."/".$slug.".epi",$this->attr,$slug,false,$old_episode->item);
							if ($episode->item) $old_episode->item = $episode->item;
							
						}
					}
					
					break;
				
				/* exclusive mode: only auphonic files are read, epi ignored */
				
				case "exclusive": 
					foreach ($this->auphonic_slugs as $slug) {
						$episode = new episode($main,$this->attr['auphonic-path']."/".$slug.".json",$this->attr,$slug,true);
						if ($episode->item) $this->episodes[$episode->item['slug']]= $episode;
					}
					break;
				
				/*	episode mode: like full, but only auphonic episodes are loaded,
					that also exist as episode files. epi data overwrites auphonic attributes
					this is the standard mode, if auphonic is in remote mode
				*/
				
				case "episodes": 
				
					foreach ($this->episode_slugs as $slug) {
						if (in_array($slug,$this->auphonic_slugs)) {
							$episode = new episode($main,$this->attr['auphonic-path']."/".$slug.".json",$this->attr,$slug,true);
							if ($episode->item) $this->episodes[$episode->item['slug']]= $episode;
							
							/* take values from epi to overwrite args in auphonic episode */
							$epi_episode = new episode($main,$this->feedDir."/".$slug.".epi",$this->attr,$slug,false,$episode->item);
							if ($epi_episode->item) $episode->item = $epi_episode->item;;
							
							
						}
					}
					
					break;
			}
			
		
			# Sort episodes by pubDate
			
			function sortByPubDate($a,$b) {
				return (strtotime($a->item['pubDate']) < strtotime($b->item['pubDate']) );
			}
			uasort($this->episodes,'sortByPubDate');
			
			/* find the latest episode to fill in data in rss and atom feeds (<updated>) */
			
			$lastupdate = 0;
			$firtz = $main->get('firtz');
			foreach ($this->episodes as $episode) {
				
				# find last update time. cache stuff and feed info
				$update = strtotime($episode->item['pubDate']);
				if ($update>$lastupdate) $lastupdate = $update;
				
				# no feed image? take the first found episode image...
				if ($this->attr['image']=="" && $episode->item['image']!="") $this->attr['image']=$episode->item['image'];
				
				foreach ($firtz->extensions as $extslug => $ext) {
					#if ($ext->type!="content") continue;
					$efunc = $extslug."_episode";
				
					if (function_exists($efunc)) {
						$item = $efunc($episode->item);
						if ($item!==false) $episode->item = $item;
						
					} 
				
				}
			}
			$this->attr['lastupdate'] = date('c', $lastupdate);
			
			foreach ($this->episodes as $slug => $episode) {
				$realSlugs[]=$slug;
			}
			$this->real_slugs = $realSlugs;
		}
		
		
		public function findEpisodes() {
		
			/*	find all auphonic and epi files
				collect slugs and save them.
				no loading, just finding to reduce load in case, not all episodes have to be displayed (web page single mode/pageing mode)
			*/
			
			if ($this->attr['auphonic-path']!="" && file_exists($this->attr['auphonic-path']) && $this->attr['auphonic-mode']!="" && $this->attr['auphonic-mode']!="off") {
				
				/* get local auphonic files */
				$auphonic_episodes = glob ($this->attr['auphonic-path']."/".$this->attr['auphonic-glob']);
				
				foreach ($auphonic_episodes as $json) {
				
					$slug = basename ($json,'.json');
					$this->auphonic_slugs[]=$slug;
					
				}
				
			}
		
			/* find local epi files if not in auphonic exclusive mode */
			if ($this->attr['auphonic-mode']!='exclusive') {
				$itemfiles = glob($this->feedDir.'/*.epi');
				$this->episode_slugs=array();
				
				foreach ( $itemfiles as $EPISODEFILE ) {
					$slug = basename($EPISODEFILE,'.epi');
					
					if ($this->attr['auphonic-mode']=="episode") {
						
						/* auphonic episode mode. if there's no identically names auphonic episode, keep hands off */
						if (in_array($slug,$this->auphonic_slugs)) $this->episode_slugs[]=$slug;
					} else {	
						/* auphonic off, full */
						$this->episode_slugs[]=$slug;
					
					}
					
				}
				
			}
		
		}
		
		public function runExt($main,$extension) {
		
			/* execute template plugin */
			
			$main = $this->main;
			$this->attr['self']=$main->get('BASEURL').$this->attr['slug']."/".$extension->slug."/".$main->get('audio');

			$audioformat = ($main->get('audio')?:$this->attr['audioformats'][0]);
			
			$this->attr['audioformat']=$audioformat;
			$main->set('feedattr',$this->attr);

			/* collect episodes */
			$items=array();
			foreach ($this->episodes as $episode) {
				$item = $episode->item;
				if (isset($item[$audioformat])) {
					$item['enclosure'] = $item[$audioformat];
				}
				$items[]=$item;
			}
			$main->set('items',$items);
			
			/* render plugins template */
			
			echo Template::instance()->render($extension->template['file'],$extension->template['type']);
			
		}
		
		
		public function renderRSS2($audioformat = '',$ret=false) {
		
			/* render rss2 template */
			
			$main = $this->main;
			$this->attr['self']=$main->get('BASEURL').$this->attr['slug']."/".$audioformat;
			
			if ($audioformat == '') $audioformat = $this->attr['audioformats'][0];
			$this->attr['audioformat']=$audioformat;
			$main->set('feedattr',$this->attr);

			/* collect episodes */
			$items=array();
			foreach ($this->episodes as $episode) {
			
				$item = $episode->item;
				if (isset($item[$audioformat])) {
					$item['enclosure'] = $item[$audioformat];
				}
				$items[]=$item;
			}
			$main->set('items',$items);
			
			/*	render or return template 
				return rendered data will be used in clone mode, which will be used for static site clones
			*/
			if ($ret===false) {
				echo Template::instance()->render('rss2.xml','application/xml');
			} else {
				return Template::instance()->render('rss2.xml');
			}
		}
		
		public function renderHTML($ret=false,$pagename="") {
			
			/* render standard html template */
			
			$main = $this->main;
			$main->set('feedattr',$this->attr);
			
			/* single page from pages template? */
			if ($pagename!="") $main->set('showpage','pages/'.$pagename.'.html');
			
			/* collect episodes */
			$items = array();
			if ($main->exists('epi') && $main->get('epi')!="") {
				$items = array ( $this->episodes[$main->get('epi')]->item );
			} else {
				foreach ($this->episodes as $episode) $items[]=$episode->item;
			}
			
			$main->set('items',$items);
			
			/*	render or return template 
				return rendered data will be used in clone mode, which will be used for static site clones
			*/
			if ($ret===false) {
				echo Template::instance()->render('site.html');
			} else {
				return Template::instance()->render('site.html');
			}
		}
	}
	
?>