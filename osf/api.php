<?php

$fdl       = $_POST['fdl'];
$fdlid     = $_GET['fdlid'];
$emode     = $_POST['mode'];
$preview   = $_POST['preview'];
$shownotes = urldecode($_POST['shownotes']);

if (isset($fdlid)) {
  if (is_numeric($fdlid)) {
    if ($_GET['fdname'] != '') {
      $fdname = $_GET['fdname'] . '.mp4chaps.txt';
    } else {
      $fdname = $fdlid . '.mp4chaps.txt';
    }
    header('Content-Disposition: attachment; filename="' . $fdname . '"');
    $filecontent = file_get_contents('./cache/' . $fdlid . '.mp4chaps.txt');
    unlink('./cache/' . $fdlid . '.mp4chaps.txt');
    echo $filecontent;
    die();
  }
}

include_once 'osf.php';

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
$shownotes = htmlspecialchars_decode(str_replace('<br />', '', str_replace('<p>', '', str_replace('</p>', '', $shownotes))));
$shownotesArray = osf_parser($shownotes, $data);

if ($emode == '') {
  $emode = 'block style';
} elseif ($emode == 'html') {
  $emode = 'block style';
} elseif ($emode == 'block') {
  $emode = 'block style';
} elseif ($emode == 'list') {
  $emode = 'list style';
} elseif ($emode == 'osf') {
  $emode = 'clean osf';
}

function get_the_ID() {
  return 1;
}
function is_feed() {
  return false;
}

if (($emode == 'block style') || ($emode == 'button style')) {
  $export = osf_export_block($shownotesArray['export'], $fullint, $emode);
} elseif ($emode == 'list style') {
  $export = osf_export_list($shownotesArray['export'], $fullint, $emode);
} elseif ($emode == 'clean osf') {
  $export = osf_export_osf($shownotesArray['export'], $fullint, $emode);
} elseif ($emode == 'glossary') {
  $export = osf_export_glossary($shownotesArray['export'], $fullint);
} elseif (($emode == 'shownoter') || ($emode == 'podcaster')) {
  if (isset($shownotesArray['header'])) {
    if ($emode == 'shownoter') {
      $export = osf_get_persons('shownoter', $shownotesArray['header']);
    } elseif ($emode == 'podcaster') {
      $export = osf_get_persons('podcaster', $shownotesArray['header']);
    }
  }
} else {
  $export = osf_export_chapterlist($shownotesArray['export'], $fullint);
}

if (isset($preview) && ($preview != 'false')) {
  if (($emode == 'clean osf')||($emode == 'chapter')) {
    $export = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Kapitelmarken</title></head><body><pre>' . $export . '</pre></body></html>';
  } else {
    $export = '<!DOCTYPE html><html>
<head>
  <meta charset="utf-8">
  <title>tinyOSF.js</title>
  <link rel="icon" href="http://shownotes.github.io/tinyOSF.js/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="http://shownotes.github.io/tinyOSF.js/shownotes.css" type="text/css" media="screen">
  <link rel="stylesheet" href="http://shownotes.github.io/tinyOSF.js/style.css" type="text/css" media="screen">
  <script src="http://shownotes.github.io/tinyOSF.js/tinyosf_exportmodules.js"></script>
  <script src="http://shownotes.github.io/tinyOSF.js/tinyosf.js"></script>
  <style>.osf_chaptertime, .osf_chapter {vertical-align: middle !important;}</style>
</head>
<body>
  <div id="parsedBox">
    <div id="parsed">' . $export . '</div>
    <div id="footer">&nbsp;<span>© 2013 <a href="http://shownot.es/">shownot.es</a></span></div>
  </div>
</body>
</html>';
  }
}

if (isset($fdl)) {
  $fdlid = round(rand(10000,900000000)*time()/300000);
  if (!is_dir('./cache/')) {
    mkdir('./cache/', 0666);
  }
  file_put_contents('./cache/' . $fdlid . '.mp4chaps.txt', $export);
  echo $fdlid;
} else {
  echo $export;
}

?>
