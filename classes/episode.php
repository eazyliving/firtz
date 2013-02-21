<?php

	class episode {
	
		public $item=array();
		public $main = "";
		public function __construct($main,$ITEMFILE,$feedattrs,$slug) {
			
			$this->main = $main;
			$item = array();
			$mime = $main->get('mimetypes');
			foreach ($main->get('itemattr') as $var) $item[$var]="";
			
			$item['pagelink'] = $main->get('BASEURL').$feedattrs['slug']."/show/".$slug;
			
			$item['slug'] = $slug;
			$item['guid'] = $feedattrs['slug'] . "-" . $item['slug']; 
			foreach ($feedattrs['audioformats'] as $format) {
				$item[$format]="";
			}
			
			$thisattr="";
			
			$fh = fopen($ITEMFILE,'r');
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
					$item['chapters'][]=array('time'=>$time,'name'=>$name,'link'=>'','image'=>'');
					
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
			
			if ($item['description']=="") $item['description'] = substr($item['article'],0,255);
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
