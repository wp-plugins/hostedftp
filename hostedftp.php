<?php

/*
Plugin Name: Hosted~FTP~
Plugin URI: http://www.hostedftp.com/features/website-plugin
Description: Website visitors can send and receive files with your Hosted~FTP~ account. Branding, custom fields and more to help automate workflows. 100% Hosted in the Amazon Cloud!
Author: Hosted~FTP~
Version: 1.2
Author URI: http://www.hostedftp.com/
*/

/* plugin defaults */
global $pluginhelp;
global $pluginsite;
global $pluginbutton;
global $pluginbrowse;
global $pluginusername;
global $pluginlinks_attr;

/* edit defaults here */
$pluginhelp			= "http://howto.hostedftp.com/";
$pluginsite			= "ftp.hostedftp.com";
$pluginbutton		= "Send Files";
$pluginbrowse		= "Browse";
$pluginusername		= "try-it-right-now";
$pluginlinks_attr	= "target='_blank'";

/**
 * Add function to widgets_init that'll load our widget.
 * @since 1.2
 */
add_action( 'widgets_init', 'hostedftp_load_widgets' );

/**
 * Register our widget.
 * 'Hosted_FTP_Widget' is the widget class used below.
 *
 * @since 1.2
 */
function hostedftp_load_widgets() {
	register_widget( 'Hosted_FTP_Widget' );
}

/**
 * Hosted~FTP~ Widget class.
 *
 * @since 1.2
 */
class Hosted_FTP_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Hosted_FTP_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'hostedftp', 'description' => __('Send and receive files with your website visitors', 'hostedftp') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 240, 'height' => 350, 'id_base' => 'hostedftp-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'hostedftp-widget', __('Hosted~FTP~', 'hostedftp'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		
	global $pluginhelp;
	global $pluginsite;
	global $pluginbutton;
	global $pluginusername;
	global $pluginlinks_attr;
	
	$options = get_option('widget_hostedftp');
	
	if (!$options['site'])
		$options['site'] = $pluginsite;

	if (!$options['username'])
		$options['username'] = $pluginusername;
	
	if (!$options['button'])
		$options['button'] = $pluginbutton;
?>
  <h3 class="widget-title"><?php echo wp_specialchars($options['header'], true)?></h3>
  
  <div>
  	<?php echo wp_specialchars($options['greeting'], true)?>
  </div>
  <div style="padding-top: 4px">
  	<table width="100%" cellpadding=0 cellspacing=0>
  		<tr>
  			<td>
			  <input type="button" value="<?php echo wp_specialchars($options['button'], true) ?>" onclick="javascript:window.open('http://<?php echo wp_specialchars($options['site'], true) ?>/~<?php echo wp_specialchars($options['username'], true)?>/send/', 'Title', 'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=640,height=800')">
			</td>
  <?php
	if (empty($options['links']) || !empty($options['browse'])) {
  ?>
			<td width="100%" align="right">
	  <?php
		if (!empty($options['browse'])) {
	  ?>
	  			<a <?php echo $pluginlinks_attr ?> href="ftp://<?php echo wp_specialchars($options['site'], true)?>/~<?php echo wp_specialchars($options['username'], true)?>"><nobr><?php echo wp_specialchars($options['browse'], true)?></nobr></a>
	<?php
			if (empty($options['links']))
	  			echo " | ";
		}

		if (empty($options['links'])) {
	?>
			 	<a <?php echo $pluginlinks_attr ?> href="http://<?php echo wp_specialchars($options['site'], true)?>/~<?php echo wp_specialchars($options['username'], true)?>">Options</a>
	<?php
		}
	?>
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

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		global $pluginbrowse;
		
		$options = $newoptions = get_option('widget_hostedftp');
	
		if ( $_POST['hostedftp-submit'] ) {
			$newoptions['username'] = $_POST['hostedftp-username'];
			$newoptions['site'] = $_POST['hostedftp-site'];
			$newoptions['header'] = $_POST['hostedftp-header'];
			$newoptions['greeting'] = $_POST['hostedftp-greeting'];
			$newoptions['button'] = $_POST['hostedftp-button'];
			$newoptions['browse'] = $_POST['hostedftp-browse'];
			$newoptions['links'] = empty($_POST['hostedftp-links']) ? "true" : "";
		}
	
		if (empty($options['1.1'])) {
			$newoptions['1.1'] = true;
			$newoptions['browse'] = $pluginbrowse;
		}
		
		if ($options != $newoptions) {
			$options = $newoptions;
			update_option('widget_hostedftp', $options);
		}
	?>
	
	<div style="">
	<input type="hidden" name="hostedftp-submit" id="hostedftp-submit" value="1" />
	<p>
	<label for="hostedftp-username"><?php _e('Username:', 'widgets'); ?> <br><input style="width: 200px;" type="text" id="hostedftp-username" name="hostedftp-username" value="<?php echo wp_specialchars($options['username'], true) ?>" /></label>
	</p>
	<p>
	<label for="hostedftp-site"><?php _e('Site:', 'widgets'); ?> <br><input style="width: 200px;" type="text" id="hostedftp-site" name="hostedftp-site" value="<?php echo wp_specialchars($options['site'], true) ?>" /></label>
	</p>
	<p>
	<label for="hostedftp-header"><?php _e('Title:', 'widgets'); ?> <br><input style="width: 200px;" type="text" id="hostedftp-header" name="hostedftp-header" value="<?php echo wp_specialchars($options['header'], true) ?>" /></label>
	</p>
	<p>
	<label for="hostedftp-greeting"><?php _e('Greeting:', 'widgets'); ?> <br><input style="width: 200px;" type="text" id="hostedftp-greeting" name="hostedftp-greeting" value="<?php echo wp_specialchars($options['greeting'], true) ?>" /></label>
	</p>
	<p>
	<label for="hostedftp-browse"><?php _e('Browse:', 'widgets'); ?> <br><input style="width: 200px;" type="text" id="hostedftp-browse" name="hostedftp-browse" value="<?php echo wp_specialchars($options['browse'], true) ?>" /></label>
	</p>
	<p>
	<label for="hostedftp-button"><?php _e('Button:', 'widgets'); ?> <br><input style="width: 200px;" type="text" id="hostedftp-button" name="hostedftp-button" value="<?php echo wp_specialchars($options['button'], true) ?>" /></label>
	</p>
	<p>			
	<label for="hostedftp-links">
		<nobr><input type="checkbox" name="hostedftp-links" id="hostedftp-links" value="1" <?php if (empty($options['links'])) { echo "CHECKED"; } ?> />
		<?php _e("Add an options link", "widgets"); ?></nobr>
	</label>
	</p>
	</div>
	
	<?php
	}
}

?>