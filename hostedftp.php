<?php
/*
Plugin Name: Hosted~FTP~
Plugin URI: http://www.hostedftp.com/hosted-ftp-wordpress-plugin-cloud-file-sharing
Description: Add file sharing to your website with this simple plugin. Website visitors can upload and share files with your Hosted FTP Account. Branding, customization and more. Visit www.hostedftp.com to learn more about FTP in the Cloud!
Author: Hosted FTP
Version: 1.0
Author URI: http://www.hostedftp.com/
*/

/* plugin defaults */
global $pluginhelp;
global $pluginsite;
global $pluginbutton;
global $pluginusername;
global $pluginlinks_attr;

/* edit defaults here */
$pluginhelp			= "http://howto.hostedftp.com/";
$pluginsite			= "ftp.hostedftp.com";
$pluginbutton		= "Share Files";
$pluginusername		= "wordpress";
$pluginlinks_attr	= "target='_blank'";

/* plugin details */
add_filter ( 'plugin_action_links', 'hostedftp_posts_action' , - 10, 2 ); 

function hostedftp_posts_action($links, $file) {
	global $pluginhelp;
	
	$this_plugin = plugin_basename ( __FILE__ );
	if ($file == $this_plugin) {$links [] = "<a target='_blank' href='".$pluginhelp ."'>Help</a>";}
	return $links;
}

function widget_hostedftp() {
	global $pluginhelp;
	global $pluginsite;
	global $pluginbutton;
	global $pluginusername;
	global $pluginlinks_attr;
	
	$options = get_option('widget_hostedftp');
	
	if (!$options['site']) {
		$options['site'] = $pluginsite;
	}

	if (!$options['username']) {
		$options['username'] = $pluginusername;
	}
	
	if (!$options['button']) {
		$options['button'] = $pluginbutton;
	}
	
?>
  <h3 class="widget-title"><?php echo wp_specialchars($options['header'], true)?></h3>
  
  <div>
  	<?php echo wp_specialchars($options['greeting'], true)?>
  </div>
  <div style="padding-top: 4px">
  	<table width="100%" cellpadding=0 cellspacing=0>
  		<tr>
  			<td>
			  <input type="button" value="<?php echo wp_specialchars($options['button'], true) ?>" onclick="javascript:window.open('http://<?php echo wp_specialchars($options['site'], true) ?>/~<?php echo wp_specialchars($options['username'], true)?>/share', 'Title', 'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=640,height=800')">
			</td>
  <?php
  if (empty($options['links'])) {
  ?>
			<td width="100%" align="right">
				<a <?php echo $pluginlinks_attr ?> href="http://<?php echo wp_specialchars($options['site'], true)?>/~<?php echo wp_specialchars($options['username'], true)?>">Options</a> | <a <?php echo $pluginlinks_attr ?> href="<?php echo $pluginhelp?>">Help</a>
			</td>
<?php
  }
  ?>
		</tr>
	</table>
  </div>
  <p/>
<?php
}
 
function hostedftp_init()
{
	register_sidebar_widget(__('Hosted~FTP~'), 'widget_hostedftp');
	register_widget_control(array('Hosted~FTP~', 'widgets'), 'widget_hostedftp_control');
}

add_action("plugins_loaded", "hostedftp_init");

function widget_hostedftp_control() {
	global $pluginsite;
	
	$options = $newoptions = get_option('widget_hostedftp');
	
	if ( $_POST['hostedftp-submit'] ) {
		$newoptions['username'] = $_POST['hostedftp-username'];
		$newoptions['site'] = $_POST['hostedftp-site'];
		$newoptions['header'] = $_POST['hostedftp-header'];
		$newoptions['greeting'] = $_POST['hostedftp-greeting'];
		$newoptions['button'] = $_POST['hostedftp-button'];
		$newoptions['links'] = empty($_POST['hostedftp-links']) ? "true" : "";
	}

	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_hostedftp', $options);
	}
?>

<div style="">
<input type="hidden" name="hostedftp-submit" id="hostedftp-submit" value="1" />
<p>
<label for="hostedftp-username"><?php _e('Username:', 'widgets'); ?> <input style="width: 200px;" type="text" id="hostedftp-username" name="hostedftp-username" value="<?php echo wp_specialchars($options['username'], true) ?>" /></label>
</p>
<p>
<label for="hostedftp-site"><?php _e('Site:', 'widgets'); ?> <input style="width: 200px;" type="text" id="hostedftp-site" name="hostedftp-site" value="<?php echo wp_specialchars($options['site'], true) ?>" /></label>
</p>
<p>
<label for="hostedftp-header"><?php _e('Title:', 'widgets'); ?> <input style="width: 200px;" type="text" id="hostedftp-header" name="hostedftp-header" value="<?php echo wp_specialchars($options['header'], true) ?>" /></label>
</p>
<p>
<label for="hostedftp-greeting"><?php _e('Greeting:', 'widgets'); ?> <input style="width: 200px;" type="text" id="hostedftp-greeting" name="hostedftp-greeting" value="<?php echo wp_specialchars($options['greeting'], true) ?>" /></label>
</p>
<p>
<label for="hostedftp-button"><?php _e('Button:', 'widgets'); ?> <input style="width: 200px;" type="text" id="hostedftp-button" name="hostedftp-button" value="<?php echo wp_specialchars($options['button'], true) ?>" /></label>
</p>
<p>			
<label for="hostedftp-links">
	<nobr><input type="checkbox" name="hostedftp-links" id="hostedftp-links" value="1" <?php if (empty($options['links'])) { echo "CHECKED"; } ?> />
	<?php _e("Add options and help links", "widgets"); ?></nobr>
</label>
</p>
</div>

<?php
}

?>