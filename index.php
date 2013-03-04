<?php

ini_set('auto_detect_line_endings',true);

$main=require('lib/base.php');

$main->set('FEEDDIR','./feeds');

$main->set('UI','templates/');
$main->set('version',0);
$main->set('revision',9);
$main->set('generator','firtz feed generator v'.$main->get('version').".".$main->get('revision'));
$main->set('pager','');
$main->set('BASEURL',"http://".$main->get('HOST').dirname($_SERVER['SCRIPT_NAME']));
$main->set('BASEPATH',$_SERVER['DOCUMENT_ROOT']);
$main->set('singlepage',false);
$main->set('showpage',false);
$main->set('AUTOLOAD','classes/');
$main->set('CDURATION',300);
$main->set('page',0);
$main->set('DEBUG',1);

$main->set('feedattr_default',array('title','description','formats','flattrid','author','summary','image','keywords','category','email','language','explicit','itunes','disqus','auphonic-path','auphonic-glob','auphonic-url','auphonic-mode','twitter','itunesblock','mediabaseurl','mediabasepath','redirect','bitlove','clone'));

$main->set('itemattr',array('title','description','link','guid','article','payment','chapters','enclosure','duration','keywords','image','date','noaudio'));
$main->set('extattr',array('slug','template','arguments','prio','script','type')); 

$main->set('mimetypes',array('mp3'=>'audio/mpeg','torrent'=>'application/x-bittorrent','mpg'=>'video/mpeg','m4a'=>'audio/mp4','m4v'=>'video/mp4','oga'=>'audio/ogg','ogg'=>'audio/ogg','ogv'=>'video/ogg','webm'=>'audio/webm','webm'=>'video/webm','flac'=>'audio/flac','opus'=>'audio/ogg;codecs=opus','mka'=>'audio/x-matroska','mkv'=>'video/x-matroska','pdf'=>'application/pdf','epub'=>'application/epub+zip','png'=>'image/png','jpg'=>'image/jpeg'));


$firtz = new firtz();
$firtz->loadAllTheExtensions();

$feeds = array();

foreach (glob($main->get('FEEDDIR').'/*',GLOB_ONLYDIR) as $dir) {
	if (substr(basename($dir),0,1)!="_") $feeds[]=basename($dir);
}

$main->set('feeds',$feeds);

function sortByPubDate($a,$b) {
	return (strtotime($a->item['pubDate']) < strtotime($b->item['pubDate']) );
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
		
		$feed->findEpisodes();
		$feed->loadEpisodes();
		$feed->renderRSS2($params['audio']);
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
		$feed->renderRSS2();
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
		$main->set('maxpage',ceil(sizeof($feed->episodes) / 3) );
		$feed->episodes = array_slice($feed->episodes,0,3);
		
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
		$feed = new feed($main,$slug,$FEEDCONFIG);
		$feed->findEpisodes();
		$feed->loadEpisodes($params['epi']);
		$feed->renderHTML();
	}, $main->get('CDURATION')
);

/*
	web page mode, single page for episode
*/

$main->route('GET|HEAD /@feed/show/page/@pagenum',
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
		$main->set('maxpage',ceil(sizeof($feed->episodes) / 3) );
		
		$feed->episodes = array_slice($feed->episodes, ($pagenum-1) *3,3);
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
		$allfeeds = $main->get('feeds');
		if (sizeof($allfeeds)==1) $main->reroute('/'.$allfeeds[0].'/show/');
		
		foreach ($allfeeds as $slug) {
			$FEEDPATH = $main->get('FEEDDIR').'/'.$slug;
			$FEEDCONFIG = $FEEDPATH.'/feed.cfg';
			$feed = new feed($main,$slug,$FEEDCONFIG);
			$feeds[]=$feed->attr;
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

$main->route('GET|HEAD /@feed/page/@page',
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




$main->route('GET /clone',
	function($main,$params) {
		
		function addDirectoryToZip($zip, $dir, $base)
		{
			$newFolder = str_replace($base, '', $dir);
			$zip->addEmptyDir($newFolder);
			foreach(glob($dir . '/*') as $file)
			{
				if(is_dir($file))
				{
					$zip = addDirectoryToZip($zip, $file, $base);
				}
				else
				{
					$newFile = str_replace($base, '', $file);
					$zip->addFile($file, $newFile);
				}
			}
			return $zip;
		}
		
		$z = new ZipArchive();
		
		try {
			$filename = tempnam(sys_get_temp_dir(),'firtz');
		} catch (Exception $e) {
			echo $e;
			exit;
		}

		$z->open($filename, ZIPARCHIVE::CM_PKWARE_IMPLODE);
		
		foreach ($main->get('feeds') as $slug) {
			
			$z=addDirectoryToZip($z,'js','');
			$z=addDirectoryToZip($z,'css','');
			$z=addDirectoryToZip($z,'pwp','');
			
			$z->addEmptyDir($slug);
			$z->addEmptyDir($slug.'/show');
			
			$FEEDPATH = $main->get('FEEDDIR').'/'.$slug;
			$FEEDCONFIG = $FEEDPATH.'/feed.cfg';
			$feed = new feed($main,$slug,$FEEDCONFIG);
		
			if ($feed->attr['clone']=='') continue;
		
			$main->set('BASEURL',$feed->attr['clone']);
			
			$feed->findEpisodes();
			$feed->loadEpisodes();
			
			foreach ($feed->attr['audioformats'] as $audio) {
				$xml = $feed->renderRSS2($audio,true);
				$z->addFromString($slug."/".$audio.".xml",$xml);
			}
			
			$main->set('epi','');
			$html = $feed->renderHTML(true);
			
			$z->addFromString($slug.'/show/index.html',$html);
			foreach ($feed->real_slugs as $episode_slug) {
				$main->set('epi',$episode_slug);
				$html = $feed->renderHTML(true);
				$z->addEmptyDir($slug.'/show/'.$episode_slug);
				$z->addFromString($slug.'/show/'.$episode_slug.'/index.html',$html);
			}
			
		}
		
		$z->close();
		
		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename="'.basename($filename).'.zip"');
		readfile($filename);
		
		exit;
	}
);


$main->run();

?>
