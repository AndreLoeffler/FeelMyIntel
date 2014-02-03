<?php
/**
 * Default configuration for the FeelMyIntel template
 * 
 * @license:    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author:		Andre Löffler <info@andre-loeffler.net>
 * @author:     Michael Klier <chi@chimeric.de>
 */

$conf['sidebar']                    = 'both';                       // enable/disable sidebar
$conf['pagename']                   = 'sidebar';                    // the pagename for sidebars inside namespaces
$conf['main_sidebar_always']		    = 1;														// show main sidebar on all namespaces
$conf['wiki_actionlinks']           = 'links';                      // use buttons instead of links
$conf['closedwiki']                 = 0;                            // don't show sidebars for logged out users at all
$conf['hideactions']                = 0;                            // hide all wiki related actions for non logged in users
$conf['logoname']                   = 'headerlogo.png';             // name of the headerlogo
$conf['logowidth']                  = '128px';                      // width of the headerlogo
$conf['logoheigth']                 = '128px';                      // heigth of the headerlogo
$conf['show_backlink']              = 'both';                       // show backlink at top and/or bottom of the page
$conf['webcam_path']				= 'http://fmi-wuerzburg.de/webcam/webcam.jpg'; //route to webcam-image
$conf['webcam_time']				= '5000';						//timeout for refreshing webcam image
$conf['submenu_name']				= 'submenu';					//name for submenu-creator
?>
