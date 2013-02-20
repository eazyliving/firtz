<?php

ini_set('auto_detect_line_endings',true);

$main=require('lib/base.php');

$main->set('MODE','0777');
#$main->set('TEMP',sys_get_temp_dir());
$main->set('FEEDDIR','./feeds');
$main->set('UI','templates/');
$main->set('version',0);
$main->set('revision',1);
$main->set('generator','firtz feed generator v'.$main->get('version').".".$main->get('revision'));
$main->set('pager','');
$main->set('BASEURL',"http://".$main->get('HOST').dirname($_SERVER['SCRIPT_NAME']));
$main->set('BASEPATH',$_SERVER['DOCUMENT_ROOT']);
$main->set('singlepage',false);

$main->set('AUTOLOAD','classes/');

$main->set('CACHE',false);
$firtz = new firtz();


$feeds = array();
foreach (glob($main->get('FEEDDIR').'/*',GLOB_ONLYDIR) as $dir) {
	$feeds[]=basename($dir);
}

$main->set('feeds',$feeds);

$main->set('feedattr_default',array('title','description','formats','flattrid','author','summary','image','keywords','category','email','language','explicit','itunes','disqus'));
$main->set('itemattr',array('title','description','link','guid','article','payment','chapters','enclosure','duration','keywords','image','date'));
$main->set('extattr',array('slug','template','arguments'));

$main->set('mimetypes',array('mp3'=>'audio/mpeg','torrent'=>'application/x-bittorrent','mpg'=>'video/mpeg','m4a'=>'audio/mp4','m4v'=>'video/mp4','oga'=>'audio/ogg','ogg'=>'audio/ogg','ogv'=>'video/ogg','webm'=>'audio/webm','webm'=>'video/webm','flac'=>'audio/flac','opus'=>'audio/ogg;codecs=opus','mka'=>'audio/x-matroska','mkv'=>'video/x-matroska','pdf'=>'application/pdf','epub'=>'application/epub+zip','png'=>'image/png','jpg'=>'image/jpeg'));

/*
	
	direct call for a specified feed/audio-combination
	
*/

$firtz->loadAllTheExtensions();
foreach ($firtz->extensions as $slug => $extension) {
	$slug = $extension->slug;
	$main->route("GET|HEAD /@feed/$slug/@audio",
		function($main,$params) use ($slug) {
		
			$feedslug = $params['feed'];
			if (!in_array($feedslug,$main->get('feeds'))) $main->error(404);
			
			$BASEPATH = $main->get('FEEDDIR').'/'.$feedslug;
			$FEEDCONFIG = $BASEPATH.'/feed.cfg';
			
			$firtz = $main->get('firtz');
			$extension = $firtz->extensions[$slug];
			$feed = new feed($main,$feedslug,$FEEDCONFIG);
			$feed->findEpisodes();
			$feed->loadEpisodes();
			$feed->runExt($main,$params['audio'],$extension);
		}
	);

}

$main->route('GET|HEAD /@feed/@audio',
	function ($main,$params) {
		
		$slug = $params['feed'];
		if (!in_array($slug,$main->get('feeds'))) $main->error(404);
		
		$BASEPATH = $main->get('FEEDDIR').'/'.$slug;
		$FEEDCONFIG = $BASEPATH.'/feed.cfg';
		
		$feed = new feed($main,$slug,$FEEDCONFIG);
		$feed->findEpisodes();
		$feed->loadEpisodes();
		$feed->renderRSS2($params['audio']);
	}
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
		$feed->findEpisodes();
		$feed->loadEpisodes();
		$feed->renderRSS2();
	}
);

/*
	web page mode, complete page
	paging mode comes next

*/

$main->route('GET /show/@feed',
	function ($main,$params) {
		$slug = $params['feed'];
		if (!in_array($slug,$main->get('feeds'))) $main->error(404);
		
		$BASEPATH = $main->get('FEEDDIR').'/'.$slug;
		$FEEDCONFIG = $BASEPATH.'/feed.cfg';
		
		$feed = new feed($main,$slug,$FEEDCONFIG);
		$feed->findEpisodes();
		$feed->loadEpisodes();
		$feed->renderHTML();
	}
);

/*
	
	web page mode, single page for episode
	
*/

$main->route('GET /show/@feed/@epi',
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
	}
);

/*
	main page without any parameters
	simple list of available podcasts (web page)
   
*/

$main->route('GET /',
	function($main,$params) {

		$feeds = array();

		foreach ($main->get('feeds') as $slug) {
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
	}
);



$main->run();

?>