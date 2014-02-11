<?php
/**
 * configuration-manager metadata for the FeelMyIntel-template
 * 
 * @license:    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author:		Andre Löffler <info@andre-loeffler.net>
 * @author:     Michael Klier <chi@chimeric.de>
 */

$meta['sidebar']                  = array('multichoice', '_choices' => array('both', 'none'));
$meta['pagename']                 = array('string', '_pattern' => '#[a-z0-9]*#');
$meta['main_sidebar_always']	    = array('onoff');
$meta['hideactions']              = array('onoff');
$meta['logoname']                 = array('string', '_pattern' => '#[a-z0-9]*#');
$meta['logowidth']                = array('string', '_pattern' => '#[a-z0-9]*#');
$meta['logoheigth']               = array('string', '_pattern' => '#[a-z0-9]*#');
$meta['show_backlink']            = array('multichoice', '_choices' => array('none', 'both', 'top', 'bottom'));
$meta['webcam_path']			  = array('string');
$meta['webcam_time']			  = array('numeric');
$meta['submenu_name']			  = array('string', '_pattern' => '#^[a-z:]*#');
?>
