<?php

include_once './plugins/osf/osf.php';

$shownotes = file_get_contents('./feeds/'.$feedattrs['slug'].'/'.$item['plugindata']);

$shownotes = "\n".str_replace("\n", " \n", $shownotes);

$item['article'] = shownotes_markdown($shownotes);

?>