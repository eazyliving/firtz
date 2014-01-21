<?php

	class episode {
	
		public $item=array();
		public $main = "";
				
		public function parseAuphonic($main,$filename,$feedattrs) {
			
			/* parse a json production description file */
						
			$mime = $main->get('mimetypes');
			foreach ($main->get('itemattr') as $var) $item[$var]="";
			
			$thisattr = "";
			
			$prod = json_decode(str_replace("\\r", "\\n",  file_get_contents($filename)));
			if ($prod===false) return false;
			
			$item['title'] = $prod->metadata->title;
			$item['description'] = strip_tags($prod->metadata->subtitle);
			$item['article'] = $prod->metadata->summary;

			$item['duration'] = $prod->length_timestring;
			$item['date']= date('r',strtotime($prod->creation_time));
			
			foreach ($prod->chapters as $chapter) {
				$chap = array('start'=>$chapter->start,'title'=>$chapter->title,'image'=>'','href'=>'');
				if (isset($chapter->url)) $chap['href'] = $chapter->url;
				// images in chapters are located on auphonics server. no chance to get them :(
				// if (isset($chapter->image)) $chap['image'] = $chapter->image;
				$item['chapters'][]=$chap;
				
			}
			if (isset($prod->multi_input_files) && $item['chapters']!="") {
				foreach ($prod->multi_input_files as $mif) {
					if ($mif->type=='intro') {
						foreach ($item['chapters'] as $key=>$chap) $item['chapters'][$key]['start'] = strftime("%H:%M:%S",strtotime($chap['start']) + $mif->input_length);
					}
				}
			
			}
			
			$services = array();
			foreach ($prod->outgoing_services as $service) {
				// only services with a base_url work...
				if (isset($service->base_url) && $service->base_url!="") $services[$service->uuid]=$service->base_url;
				if ($service->type=="amazons3") $services[$service->uuid] = 'http://'.$service->bucket.'.s3.amazonaws.com/';
			}
			$item['audiofiles']=array();
		
			foreach ($prod->output_files as $output) {
				
				if (sizeof($output->outgoing_services)==0) continue;
				
				
				$service = "";
				// Check if this services exists
				foreach ($output->outgoing_services as $oservice) {
					if (array_key_exists($oservice,$services)) {
						$service = $oservice;
						break;
					}
				}

				if ($service=="") continue;
				
				if ($output->format=="image") $item['image']=$services[$service].$output->filename;
				if (in_array($output->ending,$feedattrs['audioformats'])) {
					$mimetype = (array_key_exists($output->ending,$mime) ?  $mime[$output->ending] : "application/octet");
					$item[$output->ending] = array ( 'link' => $services[$service].$output->filename, 'length' => $output->size , 'type'=> $mimetype );
					$item['audiofiles'][$output->ending]=$item[$output->ending];
				}
			}	
			
			# replace attributes by tags, starting with _
			# eg: _date:2013-12-17 10:00:00
			
			foreach ($prod->metadata->tags as $key=>$tag) {
				$tag = trim($tag);
				if (substr($tag,0,1)=="_" && strpos($tag,':')!==false) {
					$tagname = substr($tag,1,strpos($tag,':')-1);
					$tagval = trim(substr($tag,strpos($tag,':')+1));
									
					if (in_array($tagname,$main->get('itemattr'))) {
						$item[$tagname]=$tagval;
						unset($prod->metadata->tags[$key]);
					}
				}
			
			}
			
			$item['keywords'] = implode(",",$prod->metadata->tags);
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
			
			$item['audiofiles']=array();
			$slug = basename($filename,".epi");
			
			$fh = fopen($filename,'r');
			
			while (!feof($fh)) {
				
				$uline = fgets($fh);
				$line = trim($uline);
				
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
					#((\d+:)?(\d\d?):(\d\d?)(?:\.(\d+))?) ([^<>\r\n]{3,}) ?(<([^<>\r\n]*)>\s*(<([^<>\r\n]*)>\s*)?)?\r?
					# thanks, Simon Waldherr. You saved my brain here! :)
					
					preg_match('#((\d+:)?(\d\d?):(\d\d?)(?:\.(\d+))?) ([^<>\r\n]{3,}) ?(<([^<>\r\n]*)>\s*(<([^<>\r\n]*)>\s*)?)?\r?#',$line,$chapreg );
				
					$chap = array('start'=>'','title'=>'','image'=>'','href'=>'');
					if (isset($chapreg[1]) && isset($chapreg[6])) {
						$chap['start'] = $chapreg[1];	
						$chap['title'] = $chapreg[6];	
					
						if (isset($chapreg[8])) {
							$chap['href']=$chapreg[8];
							if (isset($chapreg[10])) {
								$chap['image']=$chapreg[10];
							}
						}
						$item['chapters'][]=$chap;

					
					}
					
					
					
					
					
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
					if ($thisattr!="" && $thisattr!="article") $item[$thisattr] .= ($item[$thisattr]!="") ? "\n".$line : $line;
					if ($thisattr == "article") $item[$thisattr] .= ($item[$thisattr]!="") ? "\n".$uline : $uline;
				}
				
			}
			
			if ($feedattrs['mediabaseurl']!="") {
				
				foreach ($feedattrs['audioformats'] as $format) 
				{
					if ($feedattrs['mediabasepath']!="") {
						$localfile = $feedattrs['mediabasepath']."/".$slug.".".$format;
						if (!file_exists($localfile)) continue;
					} else {
						$localfile = "";
					}
					
					if (array_key_exists($format,$mime)) {
						$mimetype = $mime[$format];
					} else {
						$mimetype = "audio/mpeg";
					}
					
					if (isset($item['audiofiles'][$format])) {
						$audiofilename = basename($item['audiofiles'][$format]['link'],".".$format);
					} else {
						$audiofilename = $slug;
					}
					
					$item[$format] = array ('link' => $feedattrs['mediabaseurl'].$audiofilename.".".$format , 'length' => $localfile!="" ? filesize($localfile) : 0, 'type' => $mimetype);
					
					$item['audiofiles'][$format]  = $item[$format];
				
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
			$this->destroy = false;
			
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
				#$this->item = $item;
				#return;
			}
			
			/* data sanitation */
			
			if ($item['date']!="") {
				$pubDate = strtotime($item['date']);
				if ($pubDate===false) $pubDate = filectime($ITEMFILE);
			} else {
				$pubDate = filectime($ITEMFILE);
			}
			
			if ($pubDate>time()) {
				$this->item=array();
				$this->destroy = true;
				return;
			}
			
			$item['pubDate'] = date ('D, d M Y H:i:s O' , $pubDate);
			
			
			$item['pagelink'] = $main->get('BASEURL').$feedattrs['slug']."/show/".$slug;
			$item['slug'] = $slug;
			if ($item['guid']=="") $item['guid'] = $feedattrs['slug'] . "-" . $item['slug']; 
			
			
			$item['description']=chop(strip_tags( ($item['description']?:substr(strip_tags($item['article']),0,255))));
			$item['summary'] = strip_tags($item['article']);
			

			if ($item['image']=="") $item['image']=$feedattrs['image'];
			
			if ($feedattrs['flattrid']!="") {
				$item['flattrdescription'] = rawurlencode($item['description']);
				$item['flattrkeywords'] = rawurlencode($item['keywords']);
				$item['flattrlink'] = rawurlencode($item['pagelink']);
				$item['flattrtitle'] = rawurlencode($item['title']);
			}
			
			/* who says, chapters are in order? sort them! */
			if ($item['chapters']) usort($item['chapters'],function ($a,$b) {return ($a['start']>$b['start']);} );

			if (strpos($item['duration'],'.')!==FALSE) $item['duration'] = substr($item['duration'],0,strpos($item['duration'],'.'));
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
