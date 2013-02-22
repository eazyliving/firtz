<?php

	class episode {
	
		public $item=array();
		public $main = "";
	
		public function parseAuphonic($main,$filename,$feedattrs) {
			
			/* parse a json production description file */
						
			$mime = $main->get('mimetypes');
			foreach ($main->get('itemattr') as $var) $item[$var]="";
			
			$thisattr = "";
			
			$prod = json_decode(file_get_contents($filename,true));
			if ($prod===false) return false;
			
			$item['title'] = $prod->metadata->title;
			$item['description'] = $prod->metadata->subtitle;
			$item['article'] = $prod->metadata->summary;
			$item['duration'] = $prod->length_timestring;
			$item['date']= date('r',strtotime($prod->creation_time));
			$item['keywords'] = implode(",",$prod->metadata->tags);
			foreach ($prod->chapters as $chapter) $item['chapters'][]=array('start'=>$chapter->start,'title'=>$chapter->title);
			
			
			$services = array();
			foreach ($prod->outgoing_services as $service) $services[$service->uuid]=$service->base_url;
			
			foreach ($prod->output_files as $output) {
				$service = $output->outgoing_services[0];
				if ($output->format=="image") $item['image']=$services[$service].$output->filename;
				if (in_array($output->ending,$feedattrs['audioformats'])) {
					$mimetype = (array_key_exists($output->ending,$mime) ?  $mime[$output->ending] : "audio/mpeg");
					$item[$output->ending] = array ( 'link' => $services[$service].$output->filename, 'length' => $output->size , 'type'=> $mimetype );
					$item['audiofiles'][$output->ending]=$item[$output->ending];
				}
			}	
			
			return $item;
		}
	
	
		public function parseConfig($main,$filename,$feedattrs,$item=array()) {
			
			/* parse an .epi file */
			/* if item is given, it's a reparsing for overwriting data from an auphonic-episode */
		
			$mime = $main->get('mimetypes');
			if (sizeof($item)==0) 
			{ 
				foreach ($main->get('itemattr') as $var) $item[$var]="";
			} 
			$thisattr = "";
			$fh = fopen($filename,'r');
			
			while (!feof($fh)) {
				
				$line = trim(fgets($fh));
				
				/* continue if comment or empty line. except for article attribute */
				if ( ( $line=="" && $thisattr!="article") || substr($line,0,2)=="#:") continue;
				
				if ($line=="---end---")	break;
				
				if (substr($line,-1)==":" && ( in_array(substr($line,0,-1),$main->get('itemattr')) ||  in_array(substr($line,0,-1),$feedattrs['audioformats']) )) {
				
					/* new attribute starts */
				
					$thisattr = substr($line,0,-1);
					$item[$thisattr]="";
				
				} elseif ($thisattr=="chapters") {
				
					/* a chapter line */
					/* no link or image atm */
					/* will have to separate with | or because of title attribute, which might contain white space */
					
					$sep = strpos ( $line, " " );
					$time = substr($line,0,$sep);
					$name = substr($line,$sep+1);
					$item['chapters'][]=array('start'=>$time,'title'=>$name,'link'=>'','image'=>'');
					
				} elseif (in_array($thisattr,$feedattrs['audioformats'])) {
				
					/* configured audio formats */
					/* only audioformats allowed, that are configured in feed.cfg */
					
					$audio = explode ( " ",$line );
				
					if (!array_key_exists($thisattr,$mime)) {
					
						/* mimetype not found in presets */
						
						if (sizeof($audio)==3) {
							
							/* maybe it's in the .epi? */
							
							$mimetype = $audio[2];
							
						} else {
							/* fallback. hmpf */
							$mimetype = "audio/mpeg";
						}
					} else {
					
						/* everything went better than expected */
					
						$mimetype = $mime[$thisattr];
					}
					
					if (sizeof($audio)>1) {
						/* that's great: length of file is given */
						
						$item[$thisattr] = array ( 'link' => $audio[0] , 'length' => $audio[1] , 'type' => $mimetype);
					} else {
						/* boooh! get your metadata right! */
					
						$item[$thisattr] = array ( 'link' => $audio[0] , 'length' => 0, 'type' => $mimetype );
					}
					
					$item['audiofiles'][$thisattr]=$item[$thisattr];
					
				} else {
					
					/* this is an attribute which may have linebreaks. append line to current attribute */
					if ($thisattr!="") $item[$thisattr] .= ($item[$thisattr]!="") ? "\n".$line : $line;
				}
				
			}
			
			fclose($fh);
			return $item;
		}
	
		public function __construct($main,$ITEMFILE,$feedattrs,$slug,$auphonic=false,$item=array()) {
			
			if (!file_exists($ITEMFILE)) {
				$this->item=array();
				return;
			}
			
			$this->main = $main;
			
			$reparse = false;
			
			if (sizeof($item)==0) {
				/* new item, set attributes */
				
			} else {
			
				/* reparsing an old one to overwrite data */
				$reparse = true;
			}
			
			if ($auphonic===false) {
				/* parse an .epi */
				$item = $this->parseConfig($main,$ITEMFILE,$feedattrs,$item);
			} else {
				/* parse the auphonic json file */
				$item = $this->parseAuphonic($main,$ITEMFILE,$feedattrs);
				if ($item===false) {
					$this->item=array();
					return;
				}
			}
			
			if ($reparse === true) {	
				/* just reparsing. skip the sanitation part */
				$this->item = $item;
				return;
			}
			
			/* data sanitation */
			
			$item['pagelink'] = $main->get('BASEURL').$feedattrs['slug']."/show/".$slug;
			$item['slug'] = $slug;
			$item['guid'] = $feedattrs['slug'] . "-" . $item['slug']; 
			
			$item['description']=($item['description']?:substr($item['article'],0,255));
			$item['summary'] = strip_tags($item['article']);
			$item['article'] = nl2br($item['article']);

			if ($item['image']=="") $item['image']=$feedattrs['image'];
			
			if ($item['date']!="") {
				$pubDate = strtotime($item['date']);
				if ($pubDate===false) $pubDate = filectime($ITEMFILE);
			} else {
				$pubDate = filectime($ITEMFILE);
			}
			
			$item['pubDate'] = strftime ("%a, %d %b %Y %H:%M:%S %z" , $pubDate);
			
			if ($feedattrs['flattrid']!="") {
				$item['flattrdescription'] = rawurlencode($item['description']);
				$item['flattrkeywords'] = rawurlencode($item['keywords']);
				$item['flattrlink'] = rawurlencode($item['pagelink']);
				$item['flattrtitle'] = rawurlencode($item['title']);
			}
			
			/* who says, chapters are in order? sort them! */
			if ($item['chapters']) usort($item['chapters'],function ($a,$b) {return ($a['start']>$b['start']);} );
				
			$this->item=$item;
		}
		
		public function renderRSS2($audioformat) {
		
			/* this function is obsolete and kept for nostalgic reasons */
			
			$main = $this->main;
			
			$this->item['enclosure']=$this->item[$audioformat];
			
			$main->set('item',$this->item);
			return Template::instance()->render('rss2_item.xml','application/xml');
		}
		
		public function renderHTML() {
		
			/* this function is obsolete and kept for nostalgic reasons */
			
			$main = $this->main;
			
			$main->set('item',$this->item);
			echo Template::instance()->render('html_item.html');
		}
		
	}
	
?>
