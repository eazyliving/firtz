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
		public $markdown = "";
		
		public function __construct($main,$slug,$configfile) {
		
			$this->markdown = new Markdown();
		
			if (!file_exists($configfile)) {
				echo "Config for $slug not found (missing $configfile)";
				die();
			}
			
			$this->main = $main;
			$this->feedDir=dirname($configfile);
			
			
			if (file_exists($this->feedDir.'/'.$slug.'.html')) {
				$this->htmltemplate = $slug.'.html';
				$ui = $main->get('UI').';'.$this->feedDir.'/';
				$main->set('UI',$ui);
				
			} else {
				$this->htmltemplate = 'site.html';
			}
			
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
			
			fclose($fh);
		
			/* sanitize data */
			
			/* cloning? */
			if ($main->get('clonemode')===true) {
				$main->set('BASEURL',$attr['cloneurl']);
			}
			
			$attr['categories']=$categories;
			if ($attr['baseurl']!="" && substr($attr['baseurl'],-1)== "/") $attr['baseurl'] = substr($attr['baseurl'],0,-1);
			$attr['slug']=$slug;
			$attr['link'] = $main->get('BASEURL').$attr['slug'].'/show';
			$attr['self'] = $main->get('REALM');
			$attr['selfrel'] = (substr($attr['self'],-1)== "/") ? substr($attr['self'],0,-1) : $attr['self'];

			if ($attr['cloneurl']!='' && substr($attr['cloneurl'],-1)!='/') $attr['cloneurl'].='/';

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
			if ($main->get('clonemode')===false) {
				$attr['baserel']=$main->fixslashes('http://'.$main->get('HOST').'/'.$slug.'/');
				$attr['instacast']=$main->fixslashes('podcast://'.$main->get('HOST').'/'.$slug);
			} else {
				$attr['baserel']=$main->fixslashes($attr['cloneurl'].$slug.'/');
				$attr['instacast']=str_replace("http://","podcast://",$main->fixslashes($attr['cloneurl'].$slug));
			}
			
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
			
			if ($attr['rfc5005']=="") $attr['rfc5005'] = 'off';
			
			if ($attr['pagedcount']=="") $attr['pagedcount'] = 10;
			if ($attr['mediabaseurl']!='' && substr($attr['mediabaseurl'],-1)!='/') $attr['mediabaseurl'].='/';
			
			if ($attr['auphonic-mode']=='') $attr['auphonic-mode']='off';
			$this->attr = $attr;
			
		}
		
		public function preloadEpisodes() {
			
			$main = $this->main;
			$maxPubDate = "";
			$realSlugs = array();
			
			/* Just a simple PreLoad to determine, which slugs are really
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
		
		public function loadEpisodes($slug = array()) {
			
			$main = $this->main;
			$maxPubDate = "";
			if (sizeof($slug)!=0) {
				
				/*	reduce slugs array to this one episode
					happens for /$feed/show/$episodeslug/
					and /$feed/show/pager/$page
				*/
				if (!is_array($slug)) {
					$this->episode_slugs = array_intersect(array(0=>$slug),$this->episode_slugs);
					$this->auphonic_slugs = array_intersect(array(0=>$slug),$this->auphonic_slugs);
				} else {
					$this->episode_slugs = array_intersect($slug,$this->episode_slugs);
					$this->auphonic_slugs= array_intersect($slug,$this->auphonic_slugs);
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
							
							/* parse additional .epi */
							/* maybe the epi decided to invalidate the episode (future pubdate...)? then $episode->destroy is true */
							
							if ($episode->destroy === true) {
								unset($this->episodes[$slug]);
								continue;
							}
							
							if ($episode->item) {
								foreach ($episode->item as $key => $val) {
									if ($val!="" && sizeof($val)!=0) $old_episode->item[$key]=$val;
								}
							}
							
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
				
				case "episode": 
				
					foreach ($this->episode_slugs as $slug) {
						if (in_array($slug,$this->auphonic_slugs)) {
							
							$episode = new episode($main,$this->attr['auphonic-path']."/".$slug.".json",$this->attr,$slug,true);
							if ($episode->item) $this->episodes[$episode->item['slug']]= $episode;
							
							/* take values from epi to overwrite args in auphonic episode */
							$epi_episode = new episode($main,$this->feedDir."/".$slug.".epi",$this->attr,$slug,false,$episode->item);
						
							/* parse additional .epi */
							/* maybe the epi decided to invalidate the episode (future pubdate...)? then $episode->destroy is true */
							
							if ($epi_episode->destroy === true) {
								unset($this->episodes[$slug]);
								continue;
							}
							if ($epi_episode->item) {
								foreach ($epi_episode->item as $key => $val) {
									if ($val!="" && sizeof($val)!=0) $episode->item[$key]=$val;
								}
							}
							
							
						}
					}
					
					break;
			}
			
		
			# Sort episodes by pubDate
			
			
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
				$episode->item['article'] =  $this->markdown->convert(strip_tags($episode->item['article']));
				$episode->item['description'] =  strip_tags($this->markdown->convert($episode->item['description']),"<a>");
				$episode->item['summary'] =  strip_tags($episode->item['article']);
				
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
			$realSlugs = array();
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
				if (isset($item[$audioformat]))	$item['enclosure'] = $item[$audioformat];
				$items[]=$item;
				
			}
			$main->set('items',$items);
			
			/* render plugins template */
			
			echo Template::instance()->render($extension->template['file'],$extension->template['type']);
			
		}
		
		public function setOpenGraph() {
		
			$main = $this->main;
			
			$og = array();
			$og['url'] = $main->get('BASEURL').$this->attr['slug'].'/show';
			if (sizeof($this->episodes)==1) {
				
				$episode = reset($this->episodes);
			
				$og['title'] = $episode->item['title'];
				$og['url'].='/'.$episode->item['slug'];
			} else {
				$og['title']=$this->attr['title'];
			}
			$og['audio']=array();
			foreach ($this->episodes as $episode) {
				if (sizeof($episode->item['audiofiles'])==0) continue;
				$format = $this->attr['audioformats'][0];
				if (!isset($episode->item['audiofiles'][$format]['type'])) continue;
				$og['audio']['typename'] = substr($episode->item['audiofiles'][$format]['type'],0,5);
				$og['audio']['type'] = $episode->item['audiofiles'][$format]['type'];
				$og['audio']['url'] = $episode->item['audiofiles'][$format]['link'];
			}
			$main->set('og',$og);
			
		
		}
		
		public function renderPodlove($epi="",$ret=false) {
		
			/* render podlove feed specification template */
			/* https://github.com/podlove/podlove-specifications/blob/master/podlove-podcast-description.md */
			
			$main = $this->main;
			$audioformat = $this->attr['audioformats'][0];
			$this->attr['audioformat']=$audioformat;
			$main->set('feedattr',$this->attr);

			/*	render or return template 
				return rendered data will be used in clone mode, which will be used for static site clones
			*/
			if ($epi=="") {
				if (file_exists($this->feedDir."/templates")) {
					$ui = $this->feedDir."/templates/ ; ".$main->get('UI');
					$main->set('UI',$ui);
					$main->set('templatepath',$this->feedDir."/templates");
				}
				
				if ($ret===false) {
					echo Template::instance()->render('podlove_feed.xml','application/xml');
				} else {
					return Template::instance()->render('podlove.xml');
				}
			} else {
			
				
				foreach ($this->episodes as $episode) {
					$item = $episode->item;
				}
				$main->set('item',$item);
				if ($ret===false) {
					echo Template::instance()->render('podlove_episode.xml','application/xml');
				} else {
					return Template::instance()->render('podlove_episode.xml');
				}
			}
			
			
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
				if (isset($item[$audioformat])) $item['enclosure'] = $item[$audioformat];
				if ($item['chapters']!="") {
					foreach ($item['chapters'] as $key => $chapter) {
						if ($chapter['title']!="") {
							$item['chapters'][$key]['title']=$item['chapters'][$key]['title'] = str_replace(array("&","\""),array("&amp;amp;","&amp;quot;"),$chapter['title']);
						}
					}
				}
				#$item['description'] = str_replace("&","&amp;amp;",$item['description']);
				#$item['summary'] = str_replace("&","&amp;",$item['summary']);
				$items[]=$item;
			
			}
			$main->set('items',$items);
		
			/*	render or return template 
				return rendered data will be used in clone mode, which will be used for static site clones
			*/
			
			if (file_exists($this->feedDir."/templates")) {
				$ui = $this->feedDir."/templates/ ; ".$main->get('UI');
				$main->set('UI',$ui);
				$main->set('templatepath',$this->feedDir."/templates");
			}
			
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
			$this->setOpenGraph();
			/*	render or return template 
				return rendered data will be used in clone mode, which will be used for static site clones
			*/
			
			if (file_exists($this->feedDir."/templates")) {
				$ui = $this->feedDir."/templates/ ; ".$main->get('UI');
				$main->set('UI',$ui);
				$main->set('templatepath',$this->feedDir."/templates");
			}
			
			if ($ret===false) {
				echo Template::instance()->render($this->htmltemplate);
			} else {
				return Template::instance()->render($this->htmltemplate);
			}
		}
	}
	
?>