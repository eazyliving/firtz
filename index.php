<?php

ini_set('auto_detect_line_endings',true);

$main=require('src/lib/base.php');

$main->set('FEEDDIR','./feeds');

$main->set('UI','');
$main->set('templatepath','templates/default/');
$main->set('version',1);
$main->set('revision',5);
$main->set('generator','firtz podcast publisher v'.$main->get('version').".".$main->get('revision'));
$main->set('pager','');
$main->set('BASEURL',"http://".str_replace("/","",$main->get('HOST')).dirname($_SERVER['SCRIPT_NAME']));
$main->set('BASEPATH',$_SERVER['DOCUMENT_ROOT']);
$main->set('singlepage',false);
$main->set('showpage',false);
$main->set('AUTOLOAD','src/classes/');
$main->set('CDURATION',3600);
$main->set('page',0);
$main->set('DEBUG',3);
$main->set('epi','');
$main->set('og',array());
$main->set('clonemode',false);
$main->set('extxml','');
$main->set('exthtml','');
$main->set('LOCALES','src/dict/');
$main->set('rfc5005','');
$main->set('audio','');

$main->set('firtzattr_default',array('feedalias','baseurlredirect'));

$main->set('feedattr_default',array('title','description','formats','flattrid','author','summary','image','keywords','category','email','language','explicit','itunes','disqus','auphonic-path','auphonic-glob','auphonic-url','auphonic-mode','twitter','adn','itunesblock','mediabaseurl','mediabasepath','redirect','bitlove','cloneurl','clonepath','licenseurl','licensename','rfc5005','baseurl','adntoken','feedalias','articles-per-page','template','template-vars'));

$main->set('itemattr',array('title','description','link','guid','article','payment','chapters','enclosure','duration','keywords','image','date','noaudio','adnthread','location'));
$main->set('extattr',array('slug','template','arguments','prio','script','type')); 

$main->set('mimetypes',array('mp3'=>'audio/mpeg','torrent'=>'application/x-bittorrent','mpg'=>'video/mpeg','m4a'=>'audio/mp4','m4v'=>'video/mp4','oga'=>'audio/ogg','ogg'=>'audio/ogg','ogv'=>'video/ogg','webma'=>'audio/webm','webm'=>'video/webm','flac'=>'audio/flac','opus'=>'audio/ogg;codecs=opus','mka'=>'audio/x-matroska','mkv'=>'video/x-matroska','pdf'=>'application/pdf','epub'=>'application/epub+zip','png'=>'image/png','jpg'=>'image/jpeg','mobi'=>'application/x-mobipocket-ebook'));

$firtz = new firtz($main);
$firtz->loadAllTheExtensions($main);
$main->set('firtz',$firtz);
$feeds = array();

foreach (glob($main->get('FEEDDIR').'/*',GLOB_ONLYDIR) as $dir) {
	if (substr(basename($dir),0,1)!="_") $feeds[]=basename($dir);
}

$main->set('feeds',$feeds);

function sortByPubDate($a,$b) {
	if (strtotime($a->item['pubDate']) == strtotime($b->item['pubDate'])) return ($a->item['slug'] < $b->item['slug'] );
	return (strtotime($a->item['pubDate']) < strtotime($b->item['pubDate']) );
}

function firtz_markdown($text) {
	global $main;
	$f = $main->get('firtz');
	return $f->markdown->convert($text);
}

foreach ($firtz->attr['feedalias'] as $alias) {
	
	$main->route('GET|HEAD '.$alias['route'],
	
	function($main,$params) use ($alias) {
		
		header ('HTTP/1.1 301 Moved Permanently');
		header ('Location: /'.$alias['feed'].'/'.$alias['format']);
		die();
	
	}
);


}


foreach ($firtz->extensions as $slug => $extension) {
	if ($extension->type != 'output') continue;
	$slug = $extension->slug;
	$main->route("GET|HEAD /@feed/$slug/*",
		function($main,$params) use ($slug) {
				
			$firtz = $main->get('firtz');
			$extension = $firtz->extensions[$slug];
			
			$arguments = array();
			$arguments_ext = $extension->arguments;
			$arguments_get = explode("/",$params[2]);
			
			foreach ($arguments_get as $key=>$val) {
				if (isset($arguments_ext[$key])) {
					$argname = $arguments_ext[$key];
					$arguments[$argname] = $val;
					$main->set($argname,$val);
				}
			
			}
			
			$extension->arguments=$arguments;
		
			$feedslug = $params['feed'];
			if (!in_array($params['feed'],$main->get('feeds'))) $main->error(404);
			
			$BASEPATH = $main->get('FEEDDIR').'/'.$params['feed'];
			$FEEDCONFIG = $BASEPATH.'/feed.cfg';
			
			$feed = new feed($main,$feedslug,$FEEDCONFIG);
			$feed->findEpisodes();
			$feed->loadEpisodes();
			$feed->runExt($main,$extension);
		}, $main->get('CDURATION')
	);

}

foreach ($firtz->extensions as $slug => $extension) {
	
	if ($extension->type != 'output') continue;

	$slug = $extension->slug;
	$main->route("GET|HEAD /@feed/$slug",
		function($main,$params) use ($slug) {
			$firtz = $main->get('firtz');
			
			$extension = $firtz->extensions[$slug];
			
			$arguments = array();
			$arguments_ext = $extension->arguments;
			
			foreach ($arguments_ext as $argname) {
				$arguments[$argname] = "";
				$main->set($argname,"");
			}
			
			$extension->arguments=$arguments;
			
			$feedslug = $params['feed'];
			if (!in_array($params['feed'],$main->get('feeds'))) $main->error(404);
			
			$BASEPATH = $main->get('FEEDDIR').'/'.$params['feed'];
			$FEEDCONFIG = $BASEPATH.'/feed.cfg';
			
			$feed = new feed($main,$feedslug,$FEEDCONFIG);
			$feed->findEpisodes();
			$feed->loadEpisodes();
			$feed->runExt($main,$extension);
		}, $main->get('CDURATION')
	);

}


/*
	direct call for a specified feed/audio-combination
	
*/


$main->route('GET|HEAD /@feed/@audio',
	function ($main,$params) {
	
		$slug = $params['feed'];
		if (!in_array($slug,$main->get('feeds'))) $main->error(404);
		
		$BASEPATH = $main->get('FEEDDIR').'/'.$slug;
		$FEEDCONFIG = $BASEPATH.'/feed.cfg';
		
		$feed = new feed($main,$slug,$FEEDCONFIG);
		
		if ($feed->attr['redirect']!="") {
			header ('HTTP/1.1 301 Moved Permanently');
			header ('Location: '.$feed->attr['redirect']);
			die();
		}
		
		$main->set('audio',$params['audio']);

		$feed->findEpisodes();
		$feed->loadEpisodes();
		if ($feed->attr['rfc5005']=="on") {
			$main->set('rfc5005','on');
			$main->set('maxpage',ceil(sizeof($feed->episodes) / 10) );
			$feed->episodes = array_slice($feed->episodes,0,10);
		}
		
		
		$feed->renderRSS2($params['audio']);
		
	}, $main->get('CDURATION')
);

$main->route('GET|HEAD /@feed/map',
	function ($main,$params) {
	
		$slug = $params['feed'];
		if (!in_array($slug,$main->get('feeds'))) $main->error(404);
		
		$BASEPATH = $main->get('FEEDDIR').'/'.$slug;
		$FEEDCONFIG = $BASEPATH.'/feed.cfg';
		
		$feed = new feed($main,$slug,$FEEDCONFIG);
		
		$feed->findEpisodes();
		$feed->loadEpisodes();
		
		$feed->renderMap();
		
	}, $main->get('CDURATION')
);


/*
	get the main feed (audioformat according to formats: attribute in feed.cfg
	
*/

$main->route('GET|HEAD /@feed',
	function ($main,$params) {
		$slug = $params['feed'];
		if (!in_array($slug,$main->get('feeds'))) $main->error(404);
		
		$BASEPATH = $main->get('FEEDDIR').'/'.$slug;
		$FEEDCONFIG = $BASEPATH.'/feed.cfg';
		
		$feed = new feed($main,$slug,$FEEDCONFIG);
		
		if ($feed->attr['redirect']!="") {
			header ('HTTP/1.1 301 Moved Permanently');
			header ('Location: '.$feed->attr['redirect']);
			die();
		}
		
		$feed->findEpisodes();
		$feed->loadEpisodes();
		
		if ($feed->attr['rfc5005']=="on") {
			$main->set('rfc5005','on');
			$main->set('maxpage',ceil(sizeof($feed->episodes) / 10) );
			$feed->episodes = array_slice($feed->episodes,0,10);
		}
		
		
		$feed->renderRSS2();
	}, $main->get('CDURATION')
);

$main->route('GET|HEAD /@feed/page/@page',
	function ($main,$params) {
		$slug = $params['feed'];
		if (!in_array($slug,$main->get('feeds'))) $main->error(404);
		
		$BASEPATH = $main->get('FEEDDIR').'/'.$slug;
		$FEEDCONFIG = $BASEPATH.'/feed.cfg';
		
		$feed = new feed($main,$slug,$FEEDCONFIG);
		
		if ($feed->attr['redirect']!="") {
			header ('HTTP/1.1 301 Moved Permanently');
			header ('Location: '.$feed->attr['redirect']);
			die();
		}
		
		$main->set('rfc5005','on');
		
		$feed->findEpisodes();
		$feed->loadEpisodes();
		
		$main->set('page',ltrim($params['page'],'0'));
		$main->set('maxpage',ceil(sizeof($feed->episodes) / 10) );
		
		if ($main->get('page')=='first' || $main->get('page')=='current')  $main->set('page',1);
		if ($main->get('page')=='last') $main->set('page',$main->get('maxpage'));
		
		$feed->episodes = array_slice($feed->episodes,($main->get('page')-1)*10,10);
		
		$feed->renderRSS2();
		
	}, $main->get('CDURATION')
);

$main->route('GET|HEAD /@feed/@audio/page/@page',
	function ($main,$params) {
		$slug = $params['feed'];
		if (!in_array($slug,$main->get('feeds'))) $main->error(404);
		
		$BASEPATH = $main->get('FEEDDIR').'/'.$slug;
		$FEEDCONFIG = $BASEPATH.'/feed.cfg';
		
		$feed = new feed($main,$slug,$FEEDCONFIG);
		
		if ($feed->attr['redirect']!="") {
			header ('HTTP/1.1 301 Moved Permanently');
			header ('Location: '.$feed->attr['redirect']);
			die();
		}
		
		$main->set('rfc5005','on');
		
		$feed->findEpisodes();
		$feed->loadEpisodes();
		
		$main->set('page',ltrim($params['page'],'0'));
		$main->set('maxpage',ceil(sizeof($feed->episodes) / 10) );
		
		if ($main->get('page')=='first' || $main->get('page')=='current')  $main->set('page',1);
		if ($main->get('page')=='last') $main->set('page',$main->get('maxpage'));
		
		$feed->episodes = array_slice($feed->episodes,($main->get('page')-1)*10,10);
		$main->set('audio',$params['audio']);
		
		$feed->renderRSS2($params['audio']);
		
	}, $main->get('CDURATION')
);




/*
	web page mode, complete page
	paging mode comes next

*/

$main->route('GET|HEAD /@feed/show',
	function ($main,$params) {
		
		$slug = $params['feed'];
		
		if (!in_array($slug,$main->get('feeds'))) $main->error(404);
		
		$BASEPATH = $main->get('FEEDDIR').'/'.$slug;
		$FEEDCONFIG = $BASEPATH.'/feed.cfg';
		
		$feed = new feed($main,$slug,$FEEDCONFIG);
		$feed->findEpisodes();
		$feed->loadEpisodes();
		$main->set('page',1);
		$main->set('maxpage',ceil(sizeof($feed->episodes) / $feed->attr['articles-per-page']) );
		$feed->episodes = array_slice($feed->episodes,0,$feed->attr['articles-per-page']);
		
		$feed->renderHTML();
		
	}, $main->get('CDURATION')
);

/*
	web page mode, single page for episode
*/

$main->route('GET|HEAD /@feed/show/@epi',
	function ($main,$params) {
		$slug = $params['feed'];
		if (!in_array($slug,$main->get('feeds'))) $main->error(404);
		
		$BASEPATH = $main->get('FEEDDIR').'/'.$slug;
		$FEEDCONFIG = $BASEPATH.'/feed.cfg';
		$main->set('singlepage',true);
		$main->set('epi',$params['epi']);
		$feed = new feed($main,$slug,$FEEDCONFIG);
		$feed->findEpisodes();
		$feed->loadEpisodes($params['epi']);
		$feed->renderHTML();
	}, $main->get('CDURATION')

);

$main->route('GET|HEAD /@feed/showbare/@epi',
	function ($main,$params) {
		$slug = $params['feed'];
		if (!in_array($slug,$main->get('feeds'))) $main->error(404);
		
		$BASEPATH = $main->get('FEEDDIR').'/'.$slug;
		$FEEDCONFIG = $BASEPATH.'/feed.cfg';
		$main->set('singlepage',true);
		$main->set('epi',$params['epi']);
		$feed = new feed($main,$slug,$FEEDCONFIG);
		$feed->findEpisodes();
		$feed->loadEpisodes($params['epi']);
		$feed->renderHTMLbare();
	}, $main->get('CDURATION')

);


$main->route('GET|HEAD /@feed/show/@epi/@ignore',
	function ($main,$params) {
		$slug = $params['feed'];
		if (!in_array($slug,$main->get('feeds'))) $main->error(404);
		
		$BASEPATH = $main->get('FEEDDIR').'/'.$slug;
		$FEEDCONFIG = $BASEPATH.'/feed.cfg';
		$main->set('singlepage',true);
		$main->set('epi',$params['epi']);
		$feed = new feed($main,$slug,$FEEDCONFIG);
		$feed->findEpisodes();
		$feed->loadEpisodes($params['epi']);
		$feed->renderHTML();
	}, $main->get('CDURATION')

);

/*
	web page mode, pageing 3 shows
*/

$main->route('GET|HEAD /@feed/show/pager/@pagenum',
	function ($main,$params) {
		$slug = $params['feed'];
		if (!in_array($slug,$main->get('feeds'))) $main->error(404);
		$pagenum = ltrim($params['pagenum'],'0');
		if (!is_numeric($pagenum)) $pagenum=1;
		
		$BASEPATH = $main->get('FEEDDIR').'/'.$slug;
		$FEEDCONFIG = $BASEPATH.'/feed.cfg';
		
		$feed = new feed($main,$slug,$FEEDCONFIG);
		$feed->findEpisodes();

		$feed->loadEpisodes();
		$main->set('page',$pagenum);
		$main->set('maxpage',ceil(sizeof($feed->episodes) / $feed->attr['articles-per-page']) );
		
		$feed->episodes = array_slice($feed->episodes, ($pagenum-1) *$feed->attr['articles-per-page'],$feed->attr['articles-per-page']);
		$feed->renderHTML();
	}, $main->get('CDURATION')
);



/*
	main page without any parameters
	simple list of available podcasts (web page)
	if single feed page, then redirect to web page
   
*/

$main->route('GET /',
	function($main,$params) {

		$feeds = array();
		$firtz = $main->get('firtz');
		$allfeeds = $main->get('feeds');
		if (sizeof($allfeeds)==1) $main->reroute('/'.$allfeeds[0].'/show');
		
		foreach ($allfeeds as $slug) {
			
			
			
			$FEEDPATH = $main->get('FEEDDIR').'/'.$slug;
			$FEEDCONFIG = $FEEDPATH.'/feed.cfg';
			$feed = new feed($main,$slug,$FEEDCONFIG);
			$feeds[]=$feed->attr;
			if ('http://'.$main->get('HOST') == $feed->attr['baseurl'] ) {
				$main->reroute('/'.$slug.'/show');
			}
			$main->set('frontlanguage',substr($feed->attr['language'],0,2));
			$main->set('fronttitle',$feed->attr['title']);
			$main->set('frontauthor',$feed->attr['author']);
		}

		$main->set('frontfeeds',$feeds);
		echo Template::instance()->render('front.html');
	}, $main->get('CDURATION')
);

/*
	single page mode with custom content page
	put them im templates/pages/
*/

$main->route('GET|HEAD /@feed/show/page/@page',
	function($main,$params) {
		$slug = $params['feed'];
		if (!in_array($slug,$main->get('feeds'))) $main->error(404);
		
		$BASEPATH = $main->get('FEEDDIR').'/'.$slug;
		$FEEDCONFIG = $BASEPATH.'/feed.cfg';
		$main->set('singlepage',true);
		$feed = new feed($main,$slug,$FEEDCONFIG);
		$feed->findEpisodes();
		$feed->loadEpisodes();
		$feed->renderHTML(false,$params['page']);
		
	}, $main->get('CDURATION')
);

$main->route('GET|HEAD /@feed/show/page/@dir/@page',
	function($main,$params) {
		$slug = $params['feed'];
		if (!in_array($slug,$main->get('feeds'))) $main->error(404);
		
		$BASEPATH = $main->get('FEEDDIR').'/'.$slug;
		$FEEDCONFIG = $BASEPATH.'/feed.cfg';
		$main->set('singlepage',true);
		$feed = new feed($main,$slug,$FEEDCONFIG);
		$feed->findEpisodes();
		$feed->loadEpisodes();
		$feed->renderHTML(false,$params['dir'].'/'.$params['page']);
		
	}, $main->get('CDURATION')
);

$main->route('GET /@feed/adnthread/@postid',

	function($main,$params) {
	
		$slug = $params['feed'];
		if (!in_array($slug,$main->get('feeds'))) $main->error(404);
		
		$BASEPATH = $main->get('FEEDDIR').'/'.$slug;
		$FEEDCONFIG = $BASEPATH.'/feed.cfg';
		$feed = new feed($main,$slug,$FEEDCONFIG);
		if (file_exists($feed->feedDir."/templates")) {
			$ui = $feed->feedDir."/templates/ ; ".$main->get('UI');
			$main->set('UI',$ui);
			$main->set('templatepath',$feed->feedDir."/templates");
		}
		$main->set('feedattr',$feed->attr);
		$stream = "https://alpha-api.app.net/stream/0/posts/";

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $stream.$params['postid'].'/replies');
		curl_setopt($curl, CURLOPT_TIMEOUT, 15);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$feed->attr['adntoken']));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$return= curl_exec($curl);
		curl_close($curl);
		$x = json_decode($return,true);
		
		foreach ($x['data'] as $key=>$post) {
			$x['data'][$key]['html'] = preg_replace('/([^a-zA-Z0-9])\@([a-zA-Z0-9_]+)/','\1<a href="http://alpha.app.net/\2" rel="nofollow" target="_blank" title="View \2\'s ADN Profile">@\2</a>\3',$x['data'][$key]['html']);
			$x['data'][$key]['html'] = preg_replace('/(^|\s)#(\w+)/',
			'\1#<a href="https://alpha.app.net/hashtags/\2" rel="nofollow" target="_blank" title="Posts tagged with \2">\2</a>',$x['data'][$key]['html']);
			
		}
		$main->set('adnposts',array_reverse($x['data']));
		echo Template::instance()->render('adnthread.html');
		
	
	}, $main->get('CDURATION')
);


$main->route('GET|HEAD /@feed/raw/@epi',
	function ($main,$params) {
		$slug = $params['feed'];
		if (!in_array($slug,$main->get('feeds'))) $main->error(404);
		
		$BASEPATH = $main->get('FEEDDIR').'/'.$slug;
		$FEEDCONFIG = $BASEPATH.'/feed.cfg';
		$main->set('epi',$params['epi']);
		$feed = new feed($main,$slug,$FEEDCONFIG);
		$feed->findEpisodes();
		$feed->loadEpisodes($params['epi']);
		
		$feed->renderRaw($params['epi']);
	}, $main->get('CDURATION')
);


if(php_sapi_name() == "cli") {
    
	function dir_recurse_copy($src,$dst) { 
		$dir = opendir($src); 
		@mkdir($dst); 
		while(false !== ( $file = readdir($dir)) ) { 
			if (( $file != '.' ) && ( $file != '..' )) { 
				if ( is_dir($src . '/' . $file) ) { 
					dir_recurse_copy($src . '/' . $file,$dst . '/' . $file); 
				} 
				else { 
					copy($src . '/' . $file,$dst . '/' . $file); 
				} 
			} 
		} 
		closedir($dir); 
	} 


	$main->set('clonemode',true);
	$main->set('extxml','.xml');
	$main->set('exthtml','.html');
	
	# CLI mode... create static pages
	foreach ($main->get('feeds') as $slug) {
		
		$FEEDPATH = $main->get('FEEDDIR').'/'.$slug;
		$FEEDCONFIG = $FEEDPATH.'/feed.cfg';
		
		$feed = new feed($main,$slug,$FEEDCONFIG);
	
		if ($feed->attr['cloneurl']=='' || $feed->attr['clonepath']=='') continue;
		$DEST = $feed->attr['clonepath'];
		
		if (!file_exists($DEST)) {
			@mkdir($DEST);
			if (!file_exists($DEST)) {
				echo "could not create destination path $DEST";
				exit;
			}
		}
		
		
		if (!is_writable($DEST)) {
			echo "no permissions to write to $DEST!";
			exit;
		}
		
		if (strpos($feed->attr['sitecss'],'/feeds/')!==false) {
			$origcss = $FEEDPATH.'/'.basename($feed->attr['sitecss']);
			$clonecss = $main->fixslashes($DEST.'/css/'.basename($feed->attr['sitecss']));
			copy($origcss,$clonecss);
			$feed->attr['sitecss']=$feed->attr['cloneurl'].'/css/'.basename($feed->attr['sitecss']);
		}
		
		dir_recurse_copy('js',$DEST.'/js');
		dir_recurse_copy('css',$DEST.'/css');
		dir_recurse_copy('pwp',$DEST.'/pwp');
		
		$DEST = $main->fixslashes($DEST.'/'.$slug);
		@mkdir($DEST);
		@mkdir($main->fixslashes($DEST.'/show/'));
		
		$main->set('BASEURL',$feed->attr['cloneurl']);
		
		$feed->findEpisodes();
		$feed->loadEpisodes();
		
		foreach ($feed->attr['audioformats'] as $audio) {
			$xml = $feed->renderRSS2($audio,true);
			file_put_contents($DEST.'/'.$audio.'.xml',$xml);
		}
		
		$main->set('epi','');
		$html = $feed->renderHTML(true);
		
		file_put_contents($DEST.'/show/index.html',$html);
		foreach ($feed->real_slugs as $episode_slug) {
			$main->set('epi',$episode_slug);
			$html = $feed->renderHTML(true);
			@mkdir($DEST.'/show/'.$episode_slug);
			file_put_contents($DEST.'/show/'.$episode_slug.'/index.html',$html);
		}
		
		foreach (glob('templates/pages/*.html') as $page) {
			$html = $feed->renderHTML(true,basename($page,'.html'));
			@mkdir($main->fixslashes($DEST.'/page'));
			file_put_contents($DEST.'/page/'.basename($page),$html);
		}
		
	
	
		$frontfeeds[]=$feed->attr;
		$main->set('frontlanguage',substr($feed->attr['language'],0,2));
		$main->set('fronttitle',$feed->attr['title']);
		$main->set('frontauthor',$feed->attr['author']);
	
	}
	$main->set('frontfeeds',$frontfeeds);
	$front=Template::instance()->render('front.html');
	file_put_contents($feed->attr['clonepath'].'index.html',$front);
	exit;
}

$main->run();

?>