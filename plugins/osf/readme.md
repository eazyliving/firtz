#Shownotes
Contributors: simonwaldherr  
Donate link: https://flattr.com/profile/simonwaldherr  
Tags: shownotes, osf, markup  
Requires at least: 3.1  
Tested up to: 3.5  
Stable tag: trunk  
License: MIT License  
License URI: http://opensource.org/licenses/mit-license.php  

simplifies Show Notes, write them in OSF, get them as HTML

##Description

This Plugin converts <a href="https://github.com/shownotes/OSF-in-a-Nutshell/blob/master/OSF-in-a-Nutshell.md">Shownotes in OSF</a> to HTML

##Installation

1. Upload the `shownotes-shortcode` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the `Plugins` menu in WordPress
3. Use the `[shownotes]` shortcode in your post or page with the options on the front page.
4. Visit the options page

##Frequently Asked Questions

###What does this Plugin?

convert OSF to HTML

###What is OSF?

a simple format for (Podcast-) show notes, more informations about OSF are at the <a href="https://github.com/shownotes/OSF-in-a-Nutshell/blob/master/OSF-in-a-Nutshell.md">OSF-in-a-Nutshell Repo</a>

###Why should i use it?

it's easy to write, you get better results in less time and it's automatically changes links to affiliate links

##Support

###only on GitHub

if you need help, go to <a href="https://github.com/SimonWaldherr/wp-osf-shownotes/issues">github.com/simonwaldherr/wp-osf-shownotes</a>

##Screenshots

1. [Options](https://raw.github.com/SimonWaldherr/wp-osf-shownotes/on-wp.org/screenshot-1.png)
2. [Result (Block style)](https://raw.github.com/SimonWaldherr/wp-osf-shownotes/on-wp.org/screenshot-2.png)
3. [Result (List style)](https://raw.github.com/SimonWaldherr/wp-osf-shownotes/on-wp.org/screenshot-3.png)
4. [Result (Button style)](https://raw.github.com/SimonWaldherr/wp-osf-shownotes/on-wp.org/screenshot-4.png)
5. [Meta box for OSF/MD input with Preview](https://raw.github.com/SimonWaldherr/wp-osf-shownotes/on-wp.org/screenshot-5.png)

##Changelog

###0.3.5
* more and nicer icons
* preview via php parser (instead of tinyOSF)
* API added

###0.3.4
* more stable as 0.3.3
* save and preselect ShowPad Pad name

###0.3.3
* empty chapters fix
* chapter without time fix
* add more hierarchy (ranks)
* improvements for instaca.st
* some small improvements
* font awesome icons added
* API Cache on Serverside added

###0.3.2
* added hierarchy at list template
* added upgrade code in settings
* works even without chapters and times
* more valid HTML
* better feed code
* code cleanup

###0.3.1
* new Screenshots
* Wordpress SVN Bugfix
* added upgrade code in settings

###0.3.0
* new template added (buttons)
* firefox style fix
* better style
* small fixes
* better output
* clean feed output

###0.3.0
* new template added (buttons)
* firefox style fix
* better style
* small fixes
* better output
* clean feed output

###0.2.5
* delimiter bug fixed
* titles added to items
* switch between in- and exclude tags
* much better tags handling

###0.2.4
* put osf code in a separate file
* requires at least 3.1 (no, not really, but there is no reason to run WP3.0)
* filter disturbing chars at beginning and end of items
* fix custom field rename bug

###0.2.3
* rename custom field shownotes to _shownotes to hide it from custon fields list
* open links in new tab/window
* podcaster template added
* header/get persons code improved
* change - to &amp;#8209;
* show more debug info

###0.2.2
* delimiter after last item added

###0.2.1
* delimiter after subitem bugfix

###0.2
* debug mode added
* only show template associated options
* chapter delemiter added
* preview in popup added

###0.1.3
* show all podcasts in dropdown (asterisk)

###0.1.2
* better templates
* various improvements
* load Pads and Padcontent via AJAX
* better shortcode handling

###0.1.1
* delimiter changeable
* shortcodes changeable
* better shownoter matching
* also include subitems without tags
* small bugs removed

###0.1.0
* typographically correct quotation marks
* export modes renamed and glossary added
* show shownoter (as new output mode)
* tags selectable via attributes
* markdown support added
* settings rearranged

###0.0.6
* small fullmode fix

###0.0.5
* shownot.es CSS can be turned off
* import from ShowPad
* export tags changeable

###0.0.4
* wikigeeks style added

###0.0.3
* first Version on Wordpress.org.
* working Version with meta_box and shortcode content.
