<?php

function adnthread_init() {
	global $main;

	
}


function adnthread_run() {
	
	global $main;
	$attr= $main->get('extvars');
	$token = $attr['adnthread']['adntoken'];
	
	$stream = "https://alpha-api.app.net/stream/0/posts/";
	$curl = curl_init();
	
	curl_setopt($curl, CURLOPT_URL, $stream.$main->get('postid').'/replies');
	curl_setopt($curl, CURLOPT_TIMEOUT, 15);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));
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

}

?>