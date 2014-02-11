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
function fmi_tpl_sidebar_dispatch($sb,$pos,$pn,$sub=false) {
    global $conf;
    global $ID;
    global $REV;
    global $TOC;

    $svID  = $ID;   // save current ID
    $svREV = $REV;  // save current REV 
    $svTOC = $TOC;  // save current TOC

	$ret = "";
    
    switch($sb) {
        case 'main':
            if(@page_exists($pn) && auth_quickaclcheck($pn) >= AUTH_READ) {
                $always = tpl_getConf('main_sidebar_always');
                if($always or (!$always && !getNS($ID))) {
                    $ret .= '<div class="main_sidebar sidebar_box">' . DOKU_LF;
                    $ret .= p_sidebar_xhtml($pn,$pos,$sub) . DOKU_LF;
                    $ret .= '</div>' . DOKU_LF;
                }
            } elseif(!@page_exists($pn) && auth_quickaclcheck($pn) >= AUTH_CREATE) {
                if(@file_exists(DOKU_TPLINC.'lang/'. $conf['lang'].'/nosidebar.txt')) {
                    $out = p_render('xhtml', p_get_instructions(io_readFile(DOKU_TPLINC.'lang/'.$conf['lang'].'/nosidebar.txt')), $info);
                } else {
                    $out = p_render('xhtml', p_get_instructions(io_readFile(DOKU_TPLINC.'lang/en/nosidebar.txt')), $info);
                }
                $link = '<a href="' . wl($pn) . '" class="wikilink2">' . $pn . '</a>' . DOKU_LF;
                $ret .= '<div class="main_sidebar sidebar_box">' . DOKU_LF;
                $ret .= str_replace('LINK', $link, $out);
                $ret .= '</div>' . DOKU_LF;
            }
            break;

        default:
            // check for user defined sidebars
            if(@file_exists(DOKU_TPLINC.'sidebars/'.$sb.'/sidebar.php')) {
                $ret .= '<div class="'.$sb.'_sidebar sidebar_box">' . DOKU_LF;
                @require_once(DOKU_TPLINC.'sidebars/'.$sb.'/sidebar.php');
                $ret .= '</div>' . DOKU_LF;
            }
            break;
    }

    // restore ID, REV and TOC
    $ID  = $svID;
    $REV = $svREV;
    $TOC = $svTOC;
    
    return $ret;
}

/**
 * Removes the TOC of the sidebar pages and 
 * shows a edit button if the user has enough rights
 *
 * TODO sidebar caching
 * 
 * @author Michael Klier <chi@chimeric.de>
 */
function p_sidebar_xhtml($sb,$pos,$sub=false) {
    $data = p_wiki_xhtml($sb,'',false);
    // strip TOC and replace headline ids for XHTML compliance
    $data = preg_replace('/<div class="toc">.*?(<\/div>\n<\/div>)/s', '', $data);
    if($sub) $data = fmi_DOM_include_submenus($data);
    if(auth_quickaclcheck($sb) >= AUTH_EDIT)
        $data .= '<div class="secedit">'.html_btn('secedit',$sb,'',array('do'=>'edit','rev'=>'','post')).'</div>';
    $data = preg_replace('/(<h.*?><a.*?name=")(.*?)(".*?id=")(.*?)(">.*?<\/a><\/h.*?>)/','\1sb_'.$pos.'_\2\3sb_'.$pos.'_\4\5', $data);

    return ($data);
}

function fmi_DOM_include_submenus($data){
	$dom = DOMDocument::loadHTML($data);
	$anchors = $dom->getElementsByTagName('a');
	foreach($anchors as $d){
		$st =  $d->getAttribute('href');
		if (strpos($st,':')) {
			@list($url,$name) = explode(':',$st,2);
			$nd = $dom->createTextNode(fmi_tpl_submenu_single($name));
			$css = $dom->createTextNode(fmi_tpl_makeCssRules($name,$d->nodeValue));
			$js = $dom->createTextNode(fmi_tpl_makeSubmenuScript($name));
			$d->appendChild($nd);
			$d->appendChild($css);
			$d->appendChild($js);
		}
	}
	$strap = array('&lt;', '&gt;', '<html>', '</html>', '<body>', '</body>');
	$replace = array('<','>');
	$data = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace($strap, $replace, $dom->saveHTML()));
	return $data;
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

function fmi_tpl_submenu_single($files) {
	$cont = '<div class="submenu-container"><div class="submenu left_sidebar" id="submenu'.$files.'">';
	$cont .= fmi_tpl_sidebar_dispatch('main',"left", tpl_getConf('submenu_name').":".$files).'</div>';
	$cont .= '</div>';
	return $cont;
}

function fmi_tpl_makeSubmenuScript($files){
	$ret  ='<script type="text/javascript">';
	$ret .='	jQuery(document).ready(function() {';
	$ret .='		jQuery("a[title=\''.tpl_getConf('submenu_name').':'.$files.'\']").click(function(){
						jQuery("#subcancel").height(jQuery(document).height()).toggle();
						jQuery("#submenu'.$files.'").toggle();
						return false;
					});
		  		});
	 		</script>';
	return $ret;
}

function fmi_tpl_makeCssRules($files,$name){
    return '<style type="text/css"> #submenu'.$files.' { left: 70%; top: -'.(ceil(strlen($name)/42)*27).'px; }</style>';
}

function fmi_tpl_webcam($path, $time) {
	echo "<img src='".$path."' class='webcam' id='webcam' alt='Webcambild aus dem Fachschaftszimmer'></img><script type='text/javascript'>function ImageRefresh() { var unique = new Date(); document.getElementById('webcam').src = '".$path."?time=' +  unique.getTime(); } setInterval('ImageRefresh()',".$time.");</script><noscript><div class='webcam-noscript'>Kein JS: Statitsches Bild,<br>Seite neu laden zum aktualisieren.</div></noscript>";
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

/**
 * Print info if the user is logged in
 * and show full name in that case
 *
 * Could be enhanced with a profile link in future?
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @return bool
 */
function fmi_tpl_userinfo() {
	global $lang;
	global $INFO;
	if(isset($_SERVER['REMOTE_USER'])) {
		print '<bdi>'.hsc($INFO['userinfo']['name']).'</bdi> (<bdi>'.hsc($_SERVER['REMOTE_USER']).'</bdi>)';
		return true;
	}
	return false;
}

function fmi_tpl_pageinfo() {
	global $lang;
	global $INFO;
	global $ID;

	// return if we are not allowed to view the page
	if(!auth_quickaclcheck($ID)) return false; 

	// prepare date and path
	$date = dformat($INFO['lastmod']);

	// print it
	if($INFO['exists']) {
		$out = $lang['lastmod'].': '.$date;
		if($INFO['editor']) $out .= ' von <bdi>'.editorinfo($INFO['editor']).'</bdi>';
		else $out .= ' ('.$lang['external_edit'].')';
		if($INFO['locked']) $out .= ' · '.$lang['lockedby'].': <bdi>'.editorinfo($INFO['locked']).'</bdi>';
		echo $out;
		return true;
	}
	return false;
}

function fmi_tpl_showhidden() {
	if(isset($_SERVER['REMOTE_USER'])) echo "display: block";
	else echo "";
}