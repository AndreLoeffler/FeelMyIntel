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
	
    <?php
    	if(!tpl_getConf('closedwiki') || (tpl_getConf('closedwiki') && isset($_SERVER['REMOTE_USER']))) { 
    		html_msgarea();
    	}
    ?>
	<?php
	// get logo either out of the template images folder or data/media folder
    $logo = tpl_getMediaFile(array(':wiki:'.tpl_getConf('logoname'), ':'.tpl_getConf('logoname'), 'images/'.tpl_getConf('logoname')), true);
	?>
    <div class="stylehead">
      <div class="headerinc">
      <a href="<?php echo $url?>?id=start" accesskey="h" title="[[START]]" name="dokuwiki__top"><img src="<?php echo $logo?>" width="<?php echo tpl_getConf('logowidth') ?>" height="<?php echo tpl_getConf('logoheigth') ?>" border="0" /></a>
	  	<div class="webcam-container">
			<?php fmi_tpl_webcam(tpl_getConf('webcam_path'),tpl_getConf('webcam_time')); ?>
	  	</div>
      </div>
    
      <?php /*old includehook*/ @include(dirname(__FILE__).'/header.html')?>
      </div>

    <?php /*old includehook*/ @include(dirname(__FILE__).'/pageheader.html')?>

    <?php flush()?>

    <?php if(tpl_getConf('sidebar') == 'both') { ?>
      <?php if(!fmi_tpl_sidebar_hide()) { ?>
	  <div class="sidebar-container">
        <div class="left_sidebar full-width">
          <div id ="submenu-container">
          	<?php fmi_tpl_submenus(tpl_getConf('submenu_name'));?>
	      </div>
          <?php fmi_tpl_sidebar_dispatch('main','left','sidebar') ?>
        </div>
	      <div style="clear:both;" class="left-sidebar full-width">
        	<div class="main_sidebar sidebar_box">
	        	<?php tpl_searchform() ?>
        	</div>
          </div>
        <?php if(isset($INFO['userinfo'])) { ?>
	        <div style="clear: both" class="left_sidebar full-width">
	          <?php fmi_tpl_sidebar_dispatch('main','left','internal:private') ?>
	        </div>
        <?php }?>
        <div class="left_sidebar full-width">
            <?php fmi_tpl_sidebar_dispatch('main','left','calendar') ?>
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

    <?php if(!$toolb) { ?>
    <?php if(!tpl_getConf('hideactions') || tpl_getConf('hideactions') && isset($_SERVER['REMOTE_USER'])) { ?>
    <?php if(!tpl_getConf('closedwiki') || (tpl_getConf('closedwiki') && isset($_SERVER['REMOTE_USER']))) { ?>
    
    <div class="bar admin-bar" id="bar__bottom">
    	<div class="bar-left">
	      <div class="left-media">
	        <?php 
	          switch(tpl_getConf('wiki_actionlinks')) {
	            case('buttons'):
	            	tpl_button('edit');
	                tpl_button('history');
					if ((tpl_getConf('show_backlink') == 'bottom') || (tpl_getConf('show_backlink') == 'both')) {
					tpl_button('backlink');
					}
	              	break;
	            case('links'):
	            	tpl_actionlink('edit');
	                tpl_actionlink('history');
					if ((tpl_getConf('show_backlink') == 'bottom') || (tpl_getConf('show_backlink') == 'both')) {
					tpl_actionlink('backlink');
					}
	              	break;
	          }
	        ?>
	      </div>

	      <div class="left-media">
	        <?php 
	          switch(tpl_getConf('wiki_actionlinks')) {
	            case('buttons'):
					tpl_button('back');
	                tpl_button('media');
	                tpl_button('top');
	              	break;
	            case('links'):
					tpl_actionlink('back');
	                tpl_actionlink('media');
	                tpl_actionlink('top');
	              	break;
	          }
	        ?>
	      </div>
	      <div class="left-media">
	        <?php 
	          switch(tpl_getConf('wiki_actionlinks')) {
	            case('buttons'):
	                tpl_button('subscription');
	              	break;
	            case('links'):
	                tpl_actionlink('subscription');
	             	break;
	          }
	        ?>
	      </div>
    	</div>
	      <div class="bar-right">
    		<div class="right-log">
          <?php
			switch(tpl_getConf('wiki_actionlinks')) {
              case('buttons'):
                if(!tpl_getConf('closedwiki') || (tpl_getConf('closedwiki') && isset($_SERVER['REMOTE_USER']))) {
                  tpl_button('admin');
                  tpl_button('revert');
                  tpl_button('profile');
                  if(tpl_getConf('sidebar') == 'none') tpl_searchform();
                } else {
                }
                break;
              case('links'):
                if(!tpl_getConf('closedwiki') || (tpl_getConf('closedwiki') && isset($_SERVER['REMOTE_USER']))) {
                  tpl_actionlink('admin');
                  tpl_actionlink('revert');
                  tpl_actionlink('profile');
                  if(tpl_getConf('sidebar') == 'none') tpl_searchform();
                } else {
                }
                break;
            }
          ?>
    		
    		</div>
    		<div class="right-log">
          <?php
			switch(tpl_getConf('wiki_actionlinks')) {
              case('buttons'):
                if(!tpl_getConf('closedwiki') || (tpl_getConf('closedwiki') && isset($_SERVER['REMOTE_USER']))) {
                  tpl_button('recent');
                  tpl_button('index');
                } else {
                }
                break;
              case('links'):
                if(!tpl_getConf('closedwiki') || (tpl_getConf('closedwiki') && isset($_SERVER['REMOTE_USER']))) {
                  tpl_actionlink('recent');
                  tpl_actionlink('index');
                } else {
                }
                break;
            }
          ?>
    		</div>
    		<div class="right-log">
          <?php
			switch(tpl_getConf('wiki_actionlinks')) {
              case('buttons'):
                if(!tpl_getConf('closedwiki') || (tpl_getConf('closedwiki') && isset($_SERVER['REMOTE_USER']))) {
                  tpl_button('register');
                  tpl_button('login');
                } else {
                  tpl_button('register');
                  tpl_button('login');
                }
                break;
              case('links'):
                if(!tpl_getConf('closedwiki') || (tpl_getConf('closedwiki') && isset($_SERVER['REMOTE_USER']))) {
                  tpl_actionlink('register');
                  tpl_actionlink('login');
                } else {
                  tpl_actionlink('register');
                  tpl_actionlink('login');
                }
                break;
            }
          ?>
    		</div>
        </div>
        <div class="bar-left footer">
		    <?php /*old includehook*/ @include(dirname(__FILE__).'/footer.html')?>
        
        </div>
        <div class="bar-right">
            <div class="right-log">
    		  <div class="stylefoot">
		        <div class="meta">
		          <div class="user">
		          <?php tpl_userinfo()?>
		          </div>
		          <div class="doc">
		          <?php tpl_pageinfo()?>
		          </div>
		        </div>
		      </div>
    		</div>
        </div>
    </div>
    <div class="clearer"></div>
    <?php } ?>
    <?php } ?>
    <?php } ?>


  </div><!-- close DokuWiki -->
</div> 

<div class="no"><?php /* provide DokuWiki housekeeping, required in all templates */ tpl_indexerWebBug()?></div>
</body>
</html>