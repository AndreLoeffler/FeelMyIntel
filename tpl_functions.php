<?php
/**
 * DokuWiki Template FeelMyIntel Functions
 *
 * @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author  Andre Löffler <info@andre-loeffler.net>
 * @author  Michael Klier <chi@chimeric.de>
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();
if (!defined('DOKU_LF')) define('DOKU_LF',"\n");

// load sidebar contents
$sbpos = tpl_getConf('sidebar');

// set notoc option and toolbar regarding the sitebar setup
switch($sbpos) {
  case 'both':
    $notoc = (in_array('toc','main')) ? true : false;
    $toolb = (in_array('toolbox','main')) ? true : false;
    break;
  case 'none':
    $notoc = false;
    $toolb = false;
    break;
}

/**
 * Dispatches the given sidebar type to return the right content
 *
 * @author Michael Klier <chi@chimeric.de>
 */
function fmi_tpl_sidebar_dispatch($sb,$pos,$pn) {
    global $lang;
    global $conf;
    global $ID;
    global $REV;
    global $INFO;
    global $TOC;

    $svID  = $ID;   // save current ID
    $svREV = $REV;  // save current REV 
    $svTOC = $TOC;  // save current TOC

    //$pname = tpl_getConf('pagename');
    $pname = $pn;
    
    switch($sb) {

        case 'main':
            if(tpl_getConf('closedwiki') && !isset($_SERVER['REMOTE_USER'])) return;
            $main_sb = $pname;
            if(@page_exists($main_sb) && auth_quickaclcheck($main_sb) >= AUTH_READ) {
                $always = tpl_getConf('main_sidebar_always');
                if($always or (!$always && !getNS($ID))) {
                    print '<div class="main_sidebar sidebar_box">' . DOKU_LF;
                    print p_sidebar_xhtml($main_sb,$pos) . DOKU_LF;
                    print '</div>' . DOKU_LF;
                }
            } elseif(!@page_exists($main_sb) && auth_quickaclcheck($main_sb) >= AUTH_CREATE) {
                if(@file_exists(DOKU_TPLINC.'lang/'. $conf['lang'].'/nosidebar.txt')) {
                    $out = p_render('xhtml', p_get_instructions(io_readFile(DOKU_TPLINC.'lang/'.$conf['lang'].'/nosidebar.txt')), $info);
                } else {
                    $out = p_render('xhtml', p_get_instructions(io_readFile(DOKU_TPLINC.'lang/en/nosidebar.txt')), $info);
                }
                $link = '<a href="' . wl($pname) . '" class="wikilink2">' . $pname . '</a>' . DOKU_LF;
                print '<div class="main_sidebar sidebar_box">' . DOKU_LF;
                print str_replace('LINK', $link, $out);
                print '</div>' . DOKU_LF;
            }
            break;

        case 'index':
            if(tpl_getConf('closedwiki') && !isset($_SERVER['REMOTE_USER'])) return;
            print '<div class="index_sidebar sidebar_box">' . DOKU_LF;
            print '  ' . p_index_xhtml($svID,$pos) . DOKU_LF;
            print '</div>' . DOKU_LF;
            break;

        case 'toc':
            if(tpl_getConf('closedwiki') && !isset($_SERVER['REMOTE_USER'])) return;
            if(auth_quickaclcheck($svID) >= AUTH_READ) {
                $toc = tpl_toc(true);
                // replace ids to keep XHTML compliance
                if(!empty($toc)) {
                    $toc = preg_replace('/id="(.*?)"/', 'id="sb__' . $pos . '__\1"', $toc);
                    print '<div class="toc_sidebar sidebar_box">' . DOKU_LF;
                    print ($toc);
                    print '</div>' . DOKU_LF;
                }
            }
            break;
        
        case 'toolbox':

            if(tpl_getConf('hideactions') && !isset($_SERVER['REMOTE_USER'])) return;

            if(tpl_getConf('closedwiki') && !isset($_SERVER['REMOTE_USER'])) {
                print '<div class="toolbox_sidebar sidebar_box">' . DOKU_LF;
                print '  <div class="level1">' . DOKU_LF;
                print '    <ul>' . DOKU_LF;
                print '      <li><div class="li">';
                tpl_actionlink('login');
                print '      </div></li>' . DOKU_LF;
                print '    </ul>' . DOKU_LF;
                print '  </div>' . DOKU_LF;
                print '</div>' . DOKU_LF;
            } else {
                $actions = array('admin', 
                                 'revert', 
                                 'edit', 
                                 'history', 
                                 'recent', 
                                 'backlink', 
                                 'media', 
                                 'subscription', 
                                 'index', 
                                 'login', 
                                 'profile',
                                 'top');

                print '<div class="toolbox_sidebar sidebar_box">' . DOKU_LF;
                print '  <div class="level1">' . DOKU_LF;
                print '    <ul>' . DOKU_LF;

                foreach($actions as $action) {
                    if(!actionOK($action)) continue;
                    // start output buffering
                    if($action == 'edit') {
                        // check if new page button plugin is available
                        if(!plugin_isdisabled('npd') && ($npd =& plugin_load('helper', 'npd'))) {
                            $npb = $npd->html_new_page_button(true);
                            if($npb) {
                                print '    <li><div class="li">';
                                print $npb;
                                print '</div></li>' . DOKU_LF;
                            }
                        }
                    }
                    ob_start();
                    print '     <li><div class="li">';
                    if(tpl_actionlink($action)) {
                        print '</div></li>' . DOKU_LF;
                        ob_end_flush();
                    } else {
                        ob_end_clean();
                    }
                }

                print '    </ul>' . DOKU_LF;
                print '  </div>' . DOKU_LF;
                print '</div>' . DOKU_LF;
            }

            break;

        case 'trace':
            if(tpl_getConf('closedwiki') && !isset($_SERVER['REMOTE_USER'])) return;
            print '<div class="trace_sidebar sidebar_box">' . DOKU_LF;
            print '  <h1>'.$lang['breadcrumb'].'</h1>' . DOKU_LF;
            print '  <div class="breadcrumbs">' . DOKU_LF;
            ($conf['youarehere'] != 1) ? tpl_breadcrumbs() : tpl_youarehere();
            print '  </div>' . DOKU_LF;
            print '</div>' . DOKU_LF;
            break;

        case 'extra':
            if(tpl_getConf('closedwiki') && !isset($_SERVER['REMOTE_USER'])) return;
            print '<div class="extra_sidebar sidebar_box">' . DOKU_LF;
            @include(dirname(__FILE__).'/' . $pos .'_sidebar.html');
            print '</div>' . DOKU_LF;
            break;

        default:
            if(tpl_getConf('closedwiki') && !isset($_SERVER['REMOTE_USER'])) return;
            // check for user defined sidebars
            if(@file_exists(DOKU_TPLINC.'sidebars/'.$sb.'/sidebar.php')) {
                print '<div class="'.$sb.'_sidebar sidebar_box">' . DOKU_LF;
                @require_once(DOKU_TPLINC.'sidebars/'.$sb.'/sidebar.php');
                print '</div>' . DOKU_LF;
            }
            break;
    }

    // restore ID, REV and TOC
    $ID  = $svID;
    $REV = $svREV;
    $TOC = $svTOC;
}

/**
 * Removes the TOC of the sidebar pages and 
 * shows a edit button if the user has enough rights
 *
 * TODO sidebar caching
 * 
 * @author Michael Klier <chi@chimeric.de>
 */
function p_sidebar_xhtml($sb,$pos,$subst=array()) {
    $data = p_wiki_xhtml($sb,'',false);
    if(!empty($subst)) {
        $data = preg_replace($subst['pattern'], $subst['replace'], $data);
    }
    if(auth_quickaclcheck($sb) >= AUTH_EDIT) {
        $data .= '<div class="secedit">'.html_btn('secedit',$sb,'',array('do'=>'edit','rev'=>'','post')).'</div>';
    }
    // strip TOC
    $data = preg_replace('/<div class="toc">.*?(<\/div>\n<\/div>)/s', '', $data);
    // replace headline ids for XHTML compliance
    $data = preg_replace('/(<h.*?><a.*?name=")(.*?)(".*?id=")(.*?)(">.*?<\/a><\/h.*?>)/','\1sb_'.$pos.'_\2\3sb_'.$pos.'_\4\5', $data);
    return ($data);
}

/**
 * Renders the Index
 *
 * copy of html_index located in /inc/html.php
 *
 * TODO update to new AJAX index possible?
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Michael Klier <chi@chimeric.de>
 */
function p_index_xhtml($ns,$pos) {
  require_once(DOKU_INC.'inc/search.php');
  global $conf;
  global $ID;
  $dir = $conf['datadir'];
  $ns  = cleanID($ns);
  #fixme use appropriate function
  if(empty($ns)){
    $ns = dirname(str_replace(':','/',$ID));
    if($ns == '.') $ns ='';
  }
  $ns  = utf8_encodeFN(str_replace(':','/',$ns));

  // extract only the headline
  preg_match('/<h1>.*?<\/h1>/', p_locale_xhtml('index'), $match);
  print preg_replace('#<h1(.*?id=")(.*?)(".*?)h1>#', '<h1\1sidebar_'.$pos.'_\2\3h1>', $match[0]);

  $data = array();
  search($data,$conf['datadir'],'search_index',array('ns' => $ns));

  print '<div id="' . $pos . '__index__tree">' . DOKU_LF;
  print html_buildlist($data,'idx','html_list_index','html_li_index');
  print '</div>' . DOKU_LF;
}

/**
 * searches for namespace sidebars
 *
 * @author Michael Klier <chi@chimeric.de>
 */
function _getNsSb($id) {
    $pname = tpl_getConf('pagename');
    $ns_sb = '';
    $path  = explode(':', $id);
    $found = false;

    while(count($path) > 0) {
        $ns_sb = implode(':', $path).':'.$pname;
        if(@page_exists($ns_sb)) return $ns_sb;
        array_pop($path);
    }
    
    // nothing found
    return false;
}

/**
 * Checks wether the sidebar should be hidden or not
 *
 * @author Michael Klier <chi@chimeric.de>
 */
function fmi_tpl_sidebar_hide() {
    global $ACT;
    $act_hide = array( 'admin', 'conflict', 'media' );
    if(in_array($ACT, $act_hide)) {
        return true;
    } else {
        return false;
    }
}

function fmi_tpl_webcam($path, $time) {
	echo "<img src='".$path."' class='webcam' id='webcam' alt='Webcambild aus dem Fachschaftszimmer'></img>
		    <script type='text/javascript'>
			  function ImageRefresh() {
			    var unique = new Date();
			      document.getElementById('webcam').src = '".$path."?time=' +  unique.getTime();
			    }
			  setInterval('ImageRefresh()',".$time."); 
			</script>
		  	<noscript>
		  		<div class='webcam-noscript'>Kein JS: Statitsches Bild,<br>Seite neu laden zum aktualisieren.</div>
		  	</noscript>";
}

function fmi_tpl_submenus($space) {
	$tar = 'data/pages/'.$space;
	if (is_dir($tar)) {
		$dir = opendir($tar);
		while (false !== ($files = readdir($dir))) {
			if ($files != '.' && $files != '..') {
						$files = substr($files, 0, -4);
						echo '<div class="submenu left_sidebar" id="submenu'.$files.'">';
							fmi_tpl_sidebar_dispatch('main',"left", $space.":".$files);
						echo '</div>';
						fmi_tpl_makeSubmenuScript($space,$files);
						fmi_tpl_makeCssRules($space,$files);
				}
			}
	}
	echo '<div id="subcancel" style=""></div>';
	echo '<script type="text/javascript">';
	echo 'jQuery("#subcancel").click(function() {';
	echo '	jQuery("#subcancel").toggle();';
	echo '  jQuery(".submenu").hide();});';
	echo '</script>';
}

function fmi_tpl_makeSubmenuScript($space,$files){
	echo   '<script type="text/javascript">';
	echo   '	jQuery(document).ready(function() {';
	echo   '		jQuery("a[title=\''.$space.':'.$files.'\']").click(function(){
						jQuery("#subcancel").height(jQuery(document).height()).toggle();
						jQuery("#submenu'.$files.'").toggle();
						return false;
					});
		  		});
	 		</script>';
}

function fmi_tpl_makeCssRules($space,$files){
	$out = array('*', '[', ']', ' ', ':', $space);
	$f = fopen('data/pages/sidebar.txt', 'r');
	$height = -1;
	if ($f) {
		while(false !== ($buffer = fgets($f, 4096))) {
			$height++;
			if (strpos($buffer = strtolower(str_replace($out, '', $buffer)), '|')) @list($buffer, $b) = explode('|', $buffer, 2);
			if ($buffer == $files) break;
			}
	}
	
	echo '<style type="text/css">';
    echo '	#submenu'.$files.' {';
	echo '    left: '.(30 + strlen($files)*8).'px; top: '.(4 + $height*27).'px;';
	echo '  }';
	echo '</style>';
}