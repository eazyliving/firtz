<?php

ini_set('auto_detect_line_endings',true);

$main=require('lib/base.php');

$main->set('FEEDDIR','./feeds');

$main->set('UI','templates/');
$main->set('version',0);
$main->set('revision',5);
$main->set('generator','firtz feed generator v'.$main->get('version').".".$main->get('revision'));
$main->set('pager','');
$main->set('BASEURL',"http://".$main->get('HOST').dirname($_SERVER['SCRIPT_NAME']));
$main->set('BASEPATH',$_SERVER['DOCUMENT_ROOT']);
$main->set('singlepage',false);
$main->set('showpage',false);
$main->set('AUTOLOAD','classes/');
$main->set('CDURATION',300);
$main->set('page',0);

$firtz = new firtz();


$feeds = array();
foreach (glob($main->get('FEEDDIR').'/*',GLOB_ONLYDIR) as $dir) {
	if (substr(basename($dir),0,1)!="_") $feeds[]=basename($dir);
}

$main->set('feeds',$feeds);

$main->set('feedattr_default',array('title','description','formats','flattrid','author','summary','image','keywords','category','email','language','explicit','itunes','disqus','auphonic-path','auphonic-glob','auphonic-url','auphonic-mode','twitter','itunesblock','mediabaseurl','mediabasepath'));
$main->set('itemattr',array('title','description','link','guid','article','payment','chapters','enclosure','duration','keywords','image','date'));
$main->set('extattr',array('slug','template','arguments','prio','script','type')); 

$main->set('mimetypes',array('mp3'=>'audio/mpeg','torrent'=>'application/x-bittorrent','mpg'=>'video/mpeg','m4a'=>'audio/mp4','m4v'=>'video/mp4','oga'=>'audio/ogg','ogg'=>'audio/ogg','ogv'=>'video/ogg','webm'=>'audio/webm','webm'=>'video/webm','flac'=>'audio/flac','opus'=>'audio/ogg;codecs=opus','mka'=>'audio/x-matroska','mkv'=>'video/x-matroska','pdf'=>'application/pdf','epub'=>'application/epub+zip','png'=>'image/png','jpg'=>'image/jpeg'));

$firtz->loadAllTheExtensions();

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
		$feed->findEpisodes();
		$feed->loadEpisodes();
		$feed->renderRSS2();
	}, $main->get('CDURATION')
);

/*
	web page mode, complete page
	paging mode comes next

*/

$main->route('GET /@feed/show',
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

$main->route('GET /@feed/show/@epi',
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

$main->route('GET /@feed/show/page/@pagenum',
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

$main->route('GET /@feed/page/@page',
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



$main->run();

?>
