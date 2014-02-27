<?php
/**
 * DokuWiki FeelMyIntel Template
 *
 * This is the template you need to change for the overall look
 * of DokuWiki.
 *
 * You should leave the doctype at the very top - It should
 * always be the very first line of a document.
 *
 * @author Andre Löffler <info@andre-loeffler.net>
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Michael Klier <chi@chimeric.de>
 * @author Laura Eun <laura.eun@live.de>
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();
global $ACT;
// include custom fmi template functions
require_once(dirname(__FILE__).'/tpl_functions.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $conf['lang']?>"
 lang="<?php echo $conf['lang']?>" dir="<?php echo $lang['direction']?>">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>
    <?php tpl_pagetitle()?>
    [<?php echo strip_tags($conf['title'])?>]
  </title>

  <?php tpl_metaheaders()?>
  <!--get favicon location either out of the template images folder or data/media folder-->
  <?php $favicon = tpl_getMediaFile(array(':wiki:favicon.ico', ':favicon.ico', 'images/favicon.ico'), true);?>
  <link rel="shortcut icon" href="<?php echo $favicon;?>" />

  <?php /*old includehook*/ @include(dirname(__FILE__).'/meta.html')?>
</head>

<body>
<?php /*old includehook*/ @include(dirname(__FILE__).'/topheader.html')?>
<div id="wrapper" class='<?php echo $ACT ?>'>
  <div class="dokuwiki">
	
    <?php html_msgarea() ?>
	<?php
	// get logo either out of the template images folder or data/media folder
    $logo = tpl_getMediaFile(array(':wiki:'.tpl_getConf('logoname'), ':'.tpl_getConf('logoname'), 'images/'.tpl_getConf('logoname')), true);
	?>
    <div class="headerinc">
      <a href="<?php echo $url?>?id=start" accesskey="h" title="[[START]]" name="dokuwiki__top"><img src="<?php echo $logo?>" width="<?php echo tpl_getConf('logowidth') ?>" height="<?php echo tpl_getConf('logoheigth') ?>" border="0" /></a>
	  <div class="webcam-container">
		<?php fmi_tpl_webcam(tpl_getConf('webcam_path'),tpl_getConf('webcam_time')); ?>
	  </div>
    </div>
    <?php /*old includehook*/ @include(dirname(__FILE__).'/header.html')?>
    <?php /*old includehook*/ @include(dirname(__FILE__).'/pageheader.html')?>
    <?php flush()?>

    <?php if(tpl_getConf('sidebar') == 'both') { ?>
      <?php if(!fmi_tpl_sidebar_hide()) { ?>
	  <div class="sidebar-container">
        <div class="left_sidebar full-width">
          <?php echo fmi_tpl_sidebar_dispatch('main','left','sidebar',true); ?>
        </div>
	    <div style="clear:both;" class="left-sidebar full-width">
          <div class="main_sidebar sidebar_box">
	       	<?php tpl_searchform(); ?>
          </div>
        </div>
        <?php if(isset($INFO['userinfo']) && !tpl_getConf('closed')) { ?>
	      <div style="clear: both" class="left_sidebar full-width">
	        <?php echo fmi_tpl_sidebar_dispatch('main','left','internal:private'); ?>
	      </div>
        <?php }?>
        <div class="left_sidebar full-width">
            <?php echo fmi_tpl_sidebar_dispatch('main','left','calendar'); ?>
        </div>
	  </div> <!-- sidebar-container -->
        
	  <div class="center_page">
        <?php if(isset($INFO['userinfo'])) { ?>
		  <span class="userlink" style="display: <?php echo (strpos($ID,"user:") !== false ? 'block' : 'none'); ?>;">
		    <?php
		    	$match = str_replace('user:','',$ID); 
		    	
		    	echo '<a href="http://rivendell.informatik.uni-wuerzburg.de/lam/templates/account/edit.php?type=user&DN=\'uid%3D'.$match.'%2Cou%3DPeople%2Cdc%3Dfmi-wuerzburg%2Cdc%3Dde\'">'.
				            	 "Profil von ".$match."</a>";
		    ?>
		  </span>
		<?php } ?>
        <?php ($notoc) ? tpl_content(false) : tpl_content() ?>
      </div>
        
      <?php } else { ?>
        <div class="page">
        	<div class="admin_page">
          		<?php tpl_content()?> 
        	</div>
        </div> 
      <?php }?>
    <?php } ?>

    <div class="clearer"></div>

    <?php flush()?>

    <?php if(!tpl_getConf('hideactions') || tpl_getConf('hideactions') && isset($_SERVER['REMOTE_USER'])) { ?>
    <div class="bar admin-bar" id="bar__bottom">
        <div class="footer center">
        	<div class="footer column">
        		<label class="arrow left" for="leftbox"></label>
        		<input class="hidden-check" type="checkbox" id="leftbox">
        		<div class="hidden-box left" style="<?php fmi_tpl_showhidden() ?>">
        			<?php 
		            	tpl_actionlink('edit');
		                tpl_actionlink('history');
						if ((tpl_getConf('show_backlink') == 'bottom') || (tpl_getConf('show_backlink') == 'both')) {
							tpl_actionlink('backlink');
						}
						tpl_actionlink('back');
						tpl_actionlink('media');
						tpl_actionlink('top');
						tpl_actionlink('subscription');
			        ?>
        		</div>
        	</div>
        	<div class="footer column">
	    		<div class="footer row top">
	          		<div class="user">
	          			<?php 
	          				if(isset($_SERVER['REMOTE_USER'])) {
	          					fmi_tpl_userinfo();
	          					echo " - ";
	          				}
	          				fmi_tpl_pageinfo();
	          			?>
	          		</div>
	    		</div>    	
	        	<div class="footer row">
				    <?php /*old includehook*/ @include(dirname(__FILE__).'/footer.html')?>
	        	</div>
        	</div>
        	<div class="footer column">
        		<label class="arrow right" for="rightbox"></label>
        		<input class="hidden-check" type="checkbox" id="rightbox">
        		<div class="hidden-box right" style="<?php fmi_tpl_showhidden() ?>">
        			<?php 
	                  	tpl_actionlink('login');
	        			tpl_actionlink('revert');
	        			tpl_actionlink('profile');
	        			tpl_actionlink('recent');
	                  	tpl_actionlink('index');
	                  	tpl_actionlink('register');
	        			tpl_actionlink('admin');
        			?>
        		</div>
        	</div>
        </div>
    </div>
    <div class="clearer"></div>
    <?php } ?>
    <div id="subcancel" style=""></div>
    <script type="text/javascript">
    jQuery("#subcancel").click(function() {
      jQuery("#subcancel").toggle();
      jQuery(".submenu").hide();});
    </script>

  </div><!-- close DokuWiki -->
</div> 

<div class="no"><?php /* provide DokuWiki housekeeping, required in all templates */ tpl_indexerWebBug()?></div>
</body>
</html>