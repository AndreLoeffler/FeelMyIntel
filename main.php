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
  <script type="text/javascript">
	  function ImageRefresh() {
	      var unique = new Date();
	      document.getElementById("webcam").src = "http://fmi-wuerzburg.de/webcam/webcam.jpg?time=" +  unique.getTime();
	  }
	  setInterval("ImageRefresh()",5000); 
  </script>
  
</head>
<body>
<?php /*old includehook*/ @include(dirname(__FILE__).'/topheader.html')?>
<div id="wrapper" class='<?php echo $ACT ?>'>
  <div class="dokuwiki">

    <?php html_msgarea()?>
	<?php
	// get logo either out of the template images folder or data/media folder
    $logo = tpl_getMediaFile(array(':wiki:'.tpl_getConf('logoname'), ':'.tpl_getConf('logoname'), 'images/'.tpl_getConf('logoname')), true);
	?>
    <div class="stylehead">
      <div class="headerinc" style="height:<?php tpl_getConf('logoheigth') ?>;">
      <a href="<?php echo $url?>?id=start" accesskey="h" title="[[START]]" name="dokuwiki__top"><img src="<?php echo $logo?>" width="<?php echo tpl_getConf('logowidth') ?>" height="<?php echo tpl_getConf('logoheigth') ?>" border="0" /></a>
	  <?php if ($conf['tagline']): ?>
		<div class="tagline">
		<?php echo $conf['tagline']; ?>
		</div>
	  <?php endif ?>
	  	<div class="webcam-container">
		  	<img src="http://fmi-wuerzburg.de/webcam/webcam.jpg" class="webcam" id="webcam" alt="Webcambild aus dem Fachschaftszimmer"></img>
		  	<noscript>
		  		<div class="webcam-noscript">Kein JS: Statitsches Bild,<br>Seite neu laden zum aktualisieren.</div>
		  	</noscript>
	  	</div>
      </div>
    
      <?php /*old includehook*/ @include(dirname(__FILE__).'/header.html')?>
      </div>

    <?php /*old includehook*/ @include(dirname(__FILE__).'/pageheader.html')?>

    <?php flush()?>

    <?php if(tpl_getConf('sidebar') == 'left') { ?>
      <?php if(!fmi_tpl_sidebar_hide()) { ?>
      <div class="sidebar-container">
        <div class="left_sidebar full-width">
          <?php tpl_searchform() ?>
          <?php fmi_tpl_sidebar('left') ?>
        </div>
        <div class="right_page">
		<?php if (tpl_getConf('translation_bar') == 'top' || tpl_getConf('translation_bar') == 'top and bottom') { ?>
          <?php
          $translation = &plugin_load('helper','translation');
          if ($translation) echo $translation->showTranslations();
         ?>
		<?php
		}
		?>
          <?php ($notoc) ? tpl_content(false) : tpl_content() ?>
		  <?php if (tpl_getConf('translation_bar') == 'bottom' || tpl_getConf('translation_bar') == 'top and bottom') { ?>
          <?php
          $translation = &plugin_load('helper','translation');
          if ($translation) echo $translation->showTranslations();
         ?>
		<?php
		}
		?>
        </div>
      </div>
      <?php } else { ?>
        <div class="page">
          <div class="center-page">
	          <?php tpl_content()?> 
          </div>
        </div> 
      <?php } ?>

    <?php } elseif(tpl_getConf('sidebar') == 'right') { ?>
      <?php if(!fmi_tpl_sidebar_hide()) { ?>
	  <div class="sidebar-container">
		<div class="left_page">
		<?php if (tpl_getConf('translation_bar') == 'top' || tpl_getConf('translation_bar') == 'top and bottom') { ?>
          <?php
          $translation = &plugin_load('helper','translation');
          if ($translation) echo $translation->showTranslations();
         ?>
		<?php
		}
		?>
          <?php ($notoc) ? tpl_content(false) : tpl_content() ?>
		<?php if (tpl_getConf('translation_bar') == 'bottom' || tpl_getConf('translation_bar') == 'top and bottom') { ?>
          <?php
          $translation = &plugin_load('helper','translation');
          if ($translation) echo $translation->showTranslations();
         ?>
		<?php
		}
		?>
        </div>
        <div class="right_sidebar">
          <?php tpl_searchform() ?>
          <?php fmi_tpl_sidebar('right') ?>
        </div>
      </div>
      <?php } else { ?>
        <div class="page">
          <?php tpl_content() ?> 
        </div> 
      <?php }?>

    <?php } elseif(tpl_getConf('sidebar') == 'both') { ?>
      <?php if(!fmi_tpl_sidebar_hide()) { ?>
	  <div class="sidebar-container">
        <div class="left_sidebar full-width">

	        <div id ="submenu-container">
	          <?php 
	          	$tar = 'data/pages/submenu';
				if (is_dir($tar)) {
					$dir = opendir($tar);
					while (false !== ($files = readdir($dir))) {
						if ($files != '.' && $files != '..') { 
							$files = substr($files, 0, -4); ?>
							<div class="submenu left_sidebar" id="submenu<?php echo $files; ?>">
								<?php fmi_tpl_sidebar("left", "submenu:".$files); ?>
							</div>
						    <script type="text/javascript">
						  		jQuery(document).ready(function() {
							  		jQuery("a[title='submenu:<?php echo $files; ?>']").click(function(){
										jQuery("#subcancel").height(jQuery(document).height()).toggle();
										jQuery("#submenu<?php echo $files; ?>").toggle();
										return false;
									});
						  		});
					 		</script>
					 		<style type="text/css">
					 			#submenu<?php echo $files; ?> {
					 				left: <?php echo 30 + strlen($files)*8 ?>px;
					 				<?php 
					 					$out = array('*', '[', ']', ' ', ':', 'submenu');
					 					$f = fopen('data/pages/sidebar.txt', 'r');
					 					$height = -1;
					 					if ($f) {
											while(false !== ($buffer = fgets($f, 4096))) {
												$height++;
												@list($a, $b) = explode('|', str_replace($out, '', $buffer), 2);
												if ($a == $files) { break; }
											}
										}
					 				?>
					 				top: <?php echo 4 + $height*27 ?>px;
					 			}
					 		</style>
						<?php 
						}
					}
				}
			  ?>
			</div>
          <?php if(tpl_getConf('search') == 'left') tpl_searchform() ?>
          <?php fmi_tpl_sidebar('left','sidebar') ?>
        </div>
        <?php if(isset($INFO['userinfo'])) { ?>
	        <div style="clear: both" class="left_sidebar full-width">
	          <?php fmi_tpl_sidebar('left','internal:private') ?>
	        </div>
        <?php }?>
        <!-- 
         -->
        <div class="left_sidebar full-width">
            <?php fmi_tpl_sidebar('left','calendar') ?>
        </div>
	  </div> <!-- sidebar-container -->
        
		<div class="center_page">
		<?php if (tpl_getConf('translation_bar') == 'top' || tpl_getConf('translation_bar') == 'top and bottom') { ?>
        <?php
          $translation = &plugin_load('helper','translation');
          if ($translation) echo $translation->showTranslations();
        ?>
		<?php
		}
		?>
          <?php ($notoc) ? tpl_content(false) : tpl_content() ?>
		<?php if (tpl_getConf('translation_bar') == 'bottom' || tpl_getConf('translation_bar') == 'top and bottom') { ?>
          <?php
          $translation = &plugin_load('helper','translation');
          if ($translation) echo $translation->showTranslations();
        ?>
		<?php
		}
		?>
        </div>
        <div class="right_sidebar">
          <?php if(tpl_getConf('search') == 'right') ?>
	          <?php if(tpl_getConf('trace')) {?> 
			      <div class="breadcrumbs">
			        <?php ($conf['youarehere'] != 1) ? tpl_breadcrumbs() : tpl_youarehere();?>
					<?php if (tpl_getConf('translation_bar') == 'breadcrumbs') { ?>
					<div style="float:right;">
					 <?php
			          $translation = &plugin_load('helper','translation');
			          if ($translation) echo $translation->showTranslations();
			         ?>
					</div>
					<?php
					}
					?>
			      </div>
		      <?php } ?>
          	<?php tpl_searchform() ?>
          <?php fmi_tpl_sidebar('right', 'sidebar') ?>
        </div>
      <?php } else { ?>
        <div class="page">
        	<div class="admin_page">
          		<?php tpl_content()?> 
        	</div>
        </div> 
      <?php }?>

	<?php } elseif(tpl_getConf('sidebar') == 'none') { ?>
	<?php if (tpl_getConf('translation_bar') == 'top' || tpl_getConf('translation_bar') == 'top and bottom') { ?>
	<?php
          $translation = &plugin_load('helper','translation');
          if ($translation) echo $translation->showTranslations();
        ?>
		<?php
		}
		?>
      <div class="page">
        <?php tpl_content() ?>
		<?php if (tpl_getConf('translation_bar') == 'bottom' || tpl_getConf('translation_bar') == 'top and bottom') { ?>
		<?php
          $translation = &plugin_load('helper','translation');
          if ($translation) echo $translation->showTranslations();
        ?>
		<?php
		}
		?>
      </div>
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

		<div id="subcancel" style=""></div>
		<script type="text/javascript">
			jQuery('#subcancel').click(function() {
				jQuery('#subcancel').toggle();
				jQuery('.submenu').hide();
			});
		</script>
  </div><!-- close DokuWiki -->
</div> 

<div class="no"><?php /* provide DokuWiki housekeeping, required in all templates */ tpl_indexerWebBug()?></div>
</body>
</html>