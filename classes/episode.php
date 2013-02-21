<?php

	class episode {
	
		public $item=array();
		public $main = "";
	
		public function parseAuphonic($main,$filename,$feedattrs) {
			
			$mime = $main->get('mimetypes');
			foreach ($main->get('itemattr') as $var) $item[$var]="";
			
			$thisattr = "";
			
			$prod = json_decode(file_get_contents($filename,true));
			if ($prod===false) return false;
			
			#echo "<pre>".print_r($prod->metadata->album,1);exit;
			
			#'title','description','link','guid','article','payment','chapters','enclosure','duration','keywords','image','date'

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
			#echo "<pre>".print_r($item,1);exit;
			return $item;
		}
	
	
		public function parseConfig($main,$filename,$feedattrs) {
			
			$mime = $main->get('mimetypes');
			foreach ($main->get('itemattr') as $var) $item[$var]="";
			
			$thisattr = "";
			$fh = fopen($filename,'r');
			
			while (!feof($fh)) {
				$line = trim(fgets($fh));
				if ( ( $line=="" && $thisattr!="article") || substr($line,0,2)=="#:") continue;
				
				if ($line=="---end---") {
					break;
				}
				if (substr($line,-1)==":" && ( in_array(substr($line,0,-1),$main->get('itemattr')) ||  in_array(substr($line,0,-1),$feedattrs['audioformats']) )) {
					
					$thisattr = substr($line,0,-1);
					$item[$thisattr]="";
				
				} elseif ($thisattr=="chapters") {
				
					$sep = strpos ( $line, " " );
					$time = substr($line,0,$sep);
					$name = substr($line,$sep+1);
					$item['chapters'][]=array('start'=>$time,'title'=>$name,'link'=>'','image'=>'');
					
				} elseif (in_array($thisattr,$feedattrs['audioformats'])) {
				
					$audio = explode ( " ",$line );
				
					if (!array_key_exists($thisattr,$mime)) {
						if (sizeof($audio)==3) {
							$mimetype = $audio[2];
						} else {
							
							$mimetype = "audio/mpeg";
						}
					} else {
						$mimetype = $mime[$thisattr];
					}
					
					if (sizeof($audio)>1) {
						$item[$thisattr] = array ( 'link' => $audio[0] , 'length' => $audio[1] , 'type' => $mimetype);
					} else {
						$item[$thisattr] = array ( 'link' => $audio[0] , 'length' => 0, 'type' => $mimetype );
					}
					$item['audiofiles'][$thisattr]=$item[$thisattr];
					
				} else {
					if ($thisattr!="") $item[$thisattr] .= ($item[$thisattr]!="") ? "\n".$line : $line;
				}
				
			}
			fclose($fh);
			return $item;
		}
	
		public function __construct($main,$ITEMFILE,$feedattrs,$slug,$auphonic=false) {
			
			
			if (!file_exists($ITEMFILE)) {
				$this->item=array();
				return;
			}
			
			
			$this->main = $main;
			$item = array();
			
			
			foreach ($feedattrs['audioformats'] as $format) {
				$item[$format]="";
			}
			
			$thisattr="";
			
			if ($auphonic===false) {
				$item = $this->parseConfig($main,$ITEMFILE,$feedattrs);
			} else {
				$item = $this->parseAuphonic($main,$ITEMFILE,$feedattrs);
				if ($item===false) {
					$this->item=array();
					return;
				}
			}
			
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
				
			usort($item['chapters'],function ($a,$b) {return ($a['start']>$b['start']);} );
				
			$this->item=$item;
		}
		
		public function renderRSS2($audioformat) {
		
			$main = $this->main;
			
			$this->item['enclosure']=$this->item[$audioformat];
			
			$main->set('item',$this->item);
			return Template::instance()->render('rss2_item.xml','application/xml');
		}
		
		public function renderHTML() {
		
			$main = $this->main;
			
			$main->set('item',$this->item);
			echo Template::instance()->render('html_item.html');
		}
		
	}
	
?>
