<?php

if (is_admin()) {
  add_action('admin_menu', 'shownotes_create_menu');
  add_action('admin_init', 'shownotes_register_settings');
}

function shownotes_settings_page() {
  print '<div class="wrap"><h2>Shownotes Options</h2><form method="post" action="options.php">';
  settings_fields('shownotes_options');
  do_settings_sections('shownotes');
  print '<p class="submit"><input name="Submit" type="submit" class="button button-primary" value="';
  esc_attr_e('Save Changes');
  print '" /></p></form></div>';
}


function shownotes_create_menu() {
  add_options_page(' Shownotes Options', ' Shownotes', 'manage_options', 'shownotes', 'shownotes_settings_page');
}

function shownotes_register_settings() {
  $ps = 'shownotes';
  $settings = array(
    'version' => array(
      'title'  => '',
      'function' => true
    ),
    'main' => array(
      'title'  => 'General Settings',
      'fields' => array(
        'mode'        => 'Template',
        'tags_mode'     => 'Tag mode',
        'tags'        => '',
        'untagged'      => 'hide untagged items',
        'tagdecoration'   => 'Special tag decoration',
        'delimiter'     => 'String between items',
        'last_delimiter'  => 'String after last item',
        'css_id'      => 'CSS-File',
        'osf_shortcode'   => 'OSF shortcode',
        'md_shortcode'    => 'Markdown shortcode'
      )
    ),
    'import' => array(
      'title' => 'Import from ShowPad',
      'fields' => array(
        'podcastname' => 'Podcast Name'
      )
    ),
    'affiliate' => array(
      'title'  => 'Affiliate',
      'fields' => array(
        'amazon'     => 'Amazon Id',
        'thomann'    => 'Thomann.de Id',
        'tradedoubler' => 'Tradedoubler Id'
      )
    ),
    'info' => array(
      'title'  => 'Information',
      'function' => true
    )
  );

  register_setting('shownotes_options', 'shownotes_options');

  foreach ($settings as $sectionname => $section) {
    $function = false;
    if (isset($section['function'])) {
      $function = $ps . '_' . $sectionname;
    }
    add_settings_section($ps . '_' . $sectionname, $section['title'], $function, $ps);
    if (isset($section['fields'])) {
      $i = 0;
      foreach ($section['fields'] as $fieldname => $description) {
        $i += 1;
        add_settings_field($ps . '_' . $sectionname . '_' . $fieldname, $description, $ps . '_' . $sectionname . '_' . $fieldname, $ps, $ps . '_' . $sectionname, array(
          'label_for' => 'ps' . $sectionname . $i
        ));
      }
    }
  }
}

function shownotes_version() {
  function versionInt($string) {
    $versionint = '0';
    $version = explode('.', $string);
    for($i = 0; $i < count($version); $i++) {
      if(strlen($version[$i]) == 1) {
        $versionint .= '0'.$version[$i];
      } else {
        $versionint .= $version[$i];
      }
    }
    return ($versionint+0);
  }

  $options = get_option('shownotes_options');
  $version = '0.3.5';
  $infofoo = array('foo' => versionInt($options['version']), 'bar' => versionInt('0.3.2'));

  if(isset($options['version'])) {
    $lastversion = $options['version'];
    if($version != $lastversion) {
      print '<h3>Version</h3><p>Congratulations, you just upgraded the <b>shownotes</b> plugin from <b>version '.$lastversion.'</b> to <b>version '.$version.'</b></p>';
      if(versionInt($lastversion) < versionInt('0.3.5')) {
        //print '<p>this version fixes a few bugs and use the PHP Parser for preview</p>';
      }
      if(versionInt($lastversion) < versionInt('0.3.4')) {
        print '<p>this version fixes a few bugs and use the PHP Parser for preview</p>';
      }
      if(versionInt($lastversion) < versionInt('0.3.3')) {
        print '<p>more hierarchy, better chapter handling, font-awesome icons added, feed improvements</p>';
      }
      if(versionInt($lastversion) < versionInt('0.3.2')) {
        print '<p>this version adds hierarchy (OSF can set the hierarchy with "-" = (first rank), ..., "----" (fourth rank), ...</p><p>it also has new icons for links</p>';
      }
      if(versionInt($lastversion) < versionInt('0.3.1')) {
        print '<p>This is how upgrade notices would look like</p>';
        // if last version is smaller than 0.3.1
        // here can also be a upgrade code
      }
    $options['version'] = $version;
    update_option( 'shownotes_options', $options );
    }
  } else {
    $options['version'] = $version;
    update_option( 'shownotes_options', $options );
    print '<h3>Version</h3><p>Congratulations, you just installed the <b>shownotes</b> plugin <b>version '.$version.'</b><br/>you can get more informations about OSF <a href="http://shownotes.github.io/OSF-in-a-Nutshell/">here</a>, use our <a href="http://pad.shownotes.org/">ShowPad <i>(Etherpad)</i></a> to write your show notes or follow us at <a href="http://twitter.com/dieshownotes">Twitter</a> and <a href="https://alpha.app.net/shownotes">App.net</a>.</p>';
  }
  if (!isset($options['version'])) {
    $options['version'] = $version;
  }
  print '<input id="version" name="shownotes_options[version]" value="' . htmlspecialchars($options['version']) . '" style="display:none;" />';
}

function shownotes_main_mode() {
  $options = get_option('shownotes_options');
  $modes = array('block style', 'button style', 'list style', 'clean osf', 'glossary', 'shownoter');
  print '<select id="main_mode" onchange="templateAssociated(1);" name="shownotes_options[main_mode]">';
  foreach($modes as $mode) {
    if($mode == $options['main_mode']) {
      print '<option selected>'.$mode.'</option>';
    } else {
      print '<option>'.$mode.'</option>';
    }
  }
  print "<select/>";
  print "<script>window.onload = function () {templateAssociated(0);}</script>";
}

function shownotes_main_tags_mode() {
  $options  = get_option('shownotes_options');
  $tags_modes = array('only use items with the following tags', 'use all items except items with following tags');
  $i = 0;
  print '<select id="main_tags_mode" name="shownotes_options[main_tags_mode]">';
  foreach($tags_modes as $tags_mode) {
    if($i == $options['main_tags_mode']) {
      print '<option value="'.$i.'" selected>'.$tags_mode.'</option>';
    } else {
      print '<option value="'.$i.'">'.$tags_mode.'</option>';
    }
    ++$i;
  }
  print "<select/>";
}

function shownotes_main_tags() {
  $options = get_option('shownotes_options');
  if (!isset($options['main_tags'])) {
    $options['main_tags'] = "";
  }
  print '<input id="main_tags" name="shownotes_options[main_tags]" value="' . $options['main_tags'] . '" style="width:18em;" /> <i>&nbsp; split by space &nbsp;(leave empty to include all tags)</i>';
}

function shownotes_main_tags_feed() {
  $options = get_option('shownotes_options');
  if (!isset($options['main_tags_feed'])) {
    $options['main_tags_feed'] = "";
  }
  print '<input id="main_tags_feed" name="shownotes_options[main_tags_feed]" value="' . $options['main_tags_feed'] . '" style="width:18em;" /> <i>&nbsp; split by space &nbsp;(leave empty to include all tags)</i>';
}

function shownotes_main_untagged() { 
  $options = get_option('shownotes_options');
  $checked = "";
  if ( isset( $options['main_untagged'] ) ) {
    $checked = "checked ";
  }
  print "<input id='main_untagged' name='shownotes_options[main_untagged]' $checked type='checkbox' value='1' />";
}

function shownotes_main_tagdecoration() { 
  $options = get_option('shownotes_options');
  $checked = "";
  if ( isset( $options['main_tagdecoration'] ) ) {
    $checked = "checked ";
  }
  print "<input id='main_tagdecoration' name='shownotes_options[main_tagdecoration]' $checked type='checkbox' value='1' />&nbsp;&nbsp; (topics bold, quotes italic, non-tagged small)";
}

function shownotes_main_delimiter() {
  $options = get_option('shownotes_options');
  if (!isset($options['main_delimiter'])) {
    $options['main_delimiter'] = '&nbsp;—&#32;';
  }
  print '<input id="main_delimiter" name="shownotes_options[main_delimiter]" value="' . htmlspecialchars($options['main_delimiter']) . '" style="width:8em;" /> <i>&nbsp; e.g.: <code>'.htmlspecialchars('&nbsp;—&#32;').'</code></i>';
}

function shownotes_main_last_delimiter() {
  $options = get_option('shownotes_options');
  if (!isset($options['main_last_delimiter'])) {
    $options['main_last_delimiter'] = '.';
  }
  print '<input id="main_last_delimiter" name="shownotes_options[main_last_delimiter]" value="' . htmlspecialchars($options['main_last_delimiter']) . '" style="width:8em;" /> <i>&nbsp; e.g.: <code>.</code> </i>';
}

function shownotes_main_css_id() {
  $options  = get_option('shownotes_options');
  $cssnames = array('none', 'icons after items', 'icons before items', 'new icons before items', 'buttons', 'fontawesome');
  $i = 0;
  print '<select id="css_id" name="shownotes_options[css_id]">';
  foreach($cssnames as $cssname) {
    if($i == $options['css_id']) {
      print '<option value="'.$i.'" selected>'.$cssname.'</option>';
    } else {
      print '<option value="'.$i.'">'.$cssname.'</option>';
    }
    ++$i;
  }
  print "<select/>";
}

function shownotes_main_osf_shortcode() {
  $options = get_option('shownotes_options');
  if (!isset($options['main_osf_shortcode'])) {
    $options['main_osf_shortcode'] = 'shownotes';
  }
  print '<input id="main_osf_shortcode" name="shownotes_options[main_osf_shortcode]" value="' . $options['main_osf_shortcode'] . '" style="width:8em;" />';
}

function shownotes_main_md_shortcode() {
  $options = get_option('shownotes_options');
  if (!isset($options['main_md_shortcode'])) {
    $options['main_md_shortcode'] = 'md-shownotes';
  }
  print '<input id="main_md_shortcode" name="shownotes_options[main_md_shortcode]" value="' . $options['main_md_shortcode'] . '" style="width:8em;" />';
}

function shownotes_import_podcastname() {
  $options = get_option('shownotes_options');
  if (!isset($options['import_podcastname'])) {
    $options['import_podcastname'] = "";
  }
  print '<input id="import_podcastname" name="shownotes_options[import_podcastname]" value="' . $options['import_podcastname'] . '" style="width:18em;" /> <i>&nbsp; enter Podcastname in ShowPad &nbsp;(e.g.: mobilemacs)</i>';
}

function shownotes_affiliate_amazon() {
  $options = get_option('shownotes_options');
  if (!isset($options['affiliate_amazon'])) {
    $options['affiliate_amazon'] = "";
  }
  print '<input id="affiliate_amazon" name="shownotes_options[affiliate_amazon]" value="' . $options['affiliate_amazon'] . '" style="width:8em;" /> <i> e.g.: shownot.es-21</i>';
}

function shownotes_affiliate_thomann() {
  $options = get_option('shownotes_options');
  if (!isset($options['affiliate_thomann'])) {
    $options['affiliate_thomann'] = "";
  }
  print '<input id="affiliate_thomann" name="shownotes_options[affiliate_thomann]" value="' . $options['affiliate_thomann'] . '" style="width:8em;" /> <i> e.g.: 93439</i>';
}

function shownotes_affiliate_tradedoubler() {
  $options = get_option('shownotes_options');
  if (!isset($options['affiliate_tradedoubler'])) {
    $options['affiliate_tradedoubler'] = "";
  }
  print '<input id="affiliate_tradedoubler" name="shownotes_options[affiliate_tradedoubler]" value="' . $options['affiliate_tradedoubler'] . '" style="width:8em;" /> <i> e.g.: 16248286</i>';
}

function shownotes_info() {
  $scriptname = explode('/wp-admin', $_SERVER["SCRIPT_FILENAME"]);
  $dirname  = explode('/wp-content', dirname(__FILE__));
  print '<p>This is <strong>Version 0.3.5</strong> of the <strong> Shownotes</strong>.<br>
  The <strong>Including file</strong> is: <code>wp-admin' . $scriptname[1] . '</code><br>
  The <strong>plugin-directory</strong> is: <code>wp-content' . $dirname[1] . '</code></p>
  <p><a class="FlattrButton" style="display:none;" rev="flattr;button:compact;" href="http://github.com/SimonWaldherr/wp-osf-shownotes"></a><script type="text/javascript">
/* <![CDATA[ */
  (function() {
    var s = document.createElement("script"), t = document.getElementsByTagName("script")[0];
    s.type = "text/javascript";
    s.async = true;
    s.src = "http://api.flattr.com/js/0.6/load.js?mode=auto";
    t.parentNode.insertBefore(s, t);
  })();
/* ]]> */</script></p>
  <p>Want to contribute? Found a bug? Need some help? <br/>you can found our github repo/page at
  <a href="https://github.com/SimonWaldherr/wp-osf-shownotes">github.com/SimonWaldherr/wp-osf-shownotes</a></p>
  <p>If you found a bug, please tell us your WP- and ps- (and PPP- if you use PPP) Version. <br/>Also your 
  Browser version, your PHP version and the URL of your Podcast can help us, find the bug.</p>';
}

?>
