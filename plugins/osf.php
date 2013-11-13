<?php

include_once './plugins/osf/osf.php';

$shownotes = file_get_contents('./feeds/'.$feedattrs['slug'].'/'.$item['plugindata']);
$shownotes_options['main_delimiter'] = ' &#8211; ';
$shownotes_options['main_last_delimiter'] = '. ';
$amazon = 'shownot.es-21';
$thomann = '93439';
$tradedoubler = '16248286';
$fullmode = 'false';
$fullmode = 'true';
$fullint = 2;
$tags = explode(' ', 'chapter section spoiler topic embed video audio image shopping glossary source app title quote link podcast news');
$data = array(
  'amazon'       => $amazon,
  'thomann'      => $thomann,
  'tradedoubler' => $tradedoubler,
  'fullmode'     => $fullmode,
  'tagsmode'     => 1,
  'tags'         => $tags
);

$shownotes = "\n".str_replace("\n", " \n", $shownotes);
$shownotesArray = osf_parser($shownotes, $data);

function get_the_ID() {
  return uniqid();
}
function is_feed() {
  return false;
}

$item['article'] = osf_export_block($shownotesArray['export'], $fullint, 'block style');
$item['chapters'] = osf_export_chapterlist($shownotesArray['export'], $fullint);

?>