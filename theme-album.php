<?php
/**
 * Plugin Name: Theme Album
 * Plugin URI: http://www.superblogme.com/theme-album/
 * Description: Have your theme display posts as a list or grid album.
 * Version: 1.1.1
 * Released: 11/17/2014
 * Author: Super Blog Me
 * Author URI: http://www.superblogme.com
 * Copyright: Super Blog Me
 **/

define('THEME_ALBUM_VERSION', '1.1.1');
define('THEME_ALBUM_DIR', plugin_dir_path( __FILE__ ));
define('THEME_ALBUM_URL', plugin_dir_url( __FILE__ ));

defined('ABSPATH') or die ("Oops! This is a WordPress plugin and should not be called directly.\n");

////////////////////////////////////////////////////////////////////////////////////////////

if(!class_exists('Theme_Album'))
{
    class Theme_Album
    {

	private $ta_format;
	private $ta_width;
	private $ta_height;
	private $ta_default;

////////////////////////////////////////////////////////////////////////////////////////////

        public function __construct()
        {
            	// register actions
		add_action('admin_init', array($this, 'admin_init'));
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_settings_styles' ));
    		$plugin = plugin_basename(__FILE__); 
    		add_filter("plugin_action_links_$plugin", array($this,'theme_album_settings_link'));
		add_action('wp_head', array($this, 'custom_css'));

		add_shortcode('themeAlbumTest', array($this,'ta_albumFormat'));
        }

	public function enqueue_settings_styles() 
	{
		wp_register_style('taSettingsSheet', plugins_url('theme-album.css', __FILE__) );
        	wp_enqueue_style('taSettingsSheet');
	}

        public static function custom_css()
	{
		echo "\n<style type='text/css' id='theme-album-css'>\n";
        	echo get_option( 'ta_custom_css' );
		echo "\n</style>\n";
	}

        public static function activate()
        {
        }

        public static function deactivate()
        {
        }

	public function admin_menu()
	{
		$page = add_options_page('Theme Album Options', 'Theme Album', 'manage_options', 'theme-album', array($this, 'ta_settings'));
	}

	public function theme_album_settings_link($links)
    	{
		$settings_link = '<a href="admin.php?page=theme-album">Settings</a>'; 
		array_unshift($links, $settings_link); 
		return $links; 
    	}

	public function admin_init()
	{
		// register the settings for this plugin
		register_setting('theme-album-group', 'ta_format');	
		register_setting('theme-album-group', 'ta_width');	
		register_setting('theme-album-group', 'ta_height');	
		register_setting('theme-album-group', 'ta_default');	
		register_setting('theme-album-group', 'ta_custom_css');	

		// set option defaults
        	add_option('ta_format', "grid");
        	add_option('ta_width', 230);
        	add_option('ta_height', 175);
        	add_option('ta_default', THEME_ALBUM_URL . "images/default.png");
        	add_option('ta_custom_css', $this->defaultStyle());
	}

	private function defaultStyle()
	{
		$defaultStyle = <<<EOT
.theme-album { width:100%; height:100%; margin: 20px 0px 20px 0px; overflow: hidden; }
/* THEME ALBUM GRID FORMAT === */
.theme-album-grid-box { margin:5px 5px 15px 5px; float:left; }
.theme-album-grid-thumb { }
.theme-album-grid-box:hover { }
.theme-album-grid-title { font-size: 14px !important; margin-top: 0px; margin-bottom: 0px; }
.theme-album-grid-title a { font-weight: bold;}
.theme-album-grid-details { font-size: 10px; }
/* THEME ALBUM LIST FORMAT === */
.theme-album-list-box { margin:5px 0px 15px 0px; clear:both; float: left; text-align:left; }
.theme-album-list-box h3 { display:inline; }
.theme-album-list-thumb { margin: 0 5px 0 5px; float:left; }
.theme-album-list-title { }
.theme-album-list-title a { }
.theme-album-list-details{ text-align:left; }

EOT;
		return $defaultStyle;
	}

////////////////////////////////////////////////////////////////////////////////////////////

	public function ta_settings()
	{
		if(!current_user_can('manage_options'))
			wp_die(__('You do not have sufficient permissions to access this page.'));

    		// show the settings template
		include(sprintf("%s/templates/main-settings.php", dirname(__FILE__)));
	}

////////////////////////////////////////////////////////////////////////////////////////////

	private function ta_fetch_image($post='',$imgWidth=0,$imgHeight=0)
	{
		global $wpdb;

		if (empty($post)) $post = get_post();
		$img = "";

		// check for featured image:
        	if ( function_exists('has_post_thumbnail') && has_post_thumbnail($post->ID) ) 
		{
                	$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full');
                	if ($thumbnail[0]) $img = $thumbnail[0];
        	}

		if (empty($img))
		{
			// check for 1st image in content:
       	        	$first_img = '';
       	        	ob_start();
       	        	ob_end_clean();
       	        	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
			if (isset($matches[1][0])) $first_img = $matches[1][0];
       	        	if (!empty($first_img)) $img = $first_img;

			// Check if this is the URL of an auto-generated thumbnail and get the URL of the original image
			$check_img = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $img );
			$query = $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE guid='%s'", $check_img);
			if ( $wpdb->get_results( $query ) )
				$img = $check_img;
		}

		if (empty($img))
		{
			// check for any attached images
			$args = array(
				'numberposts' 		=> 1,
				'order'			=> 'ASC',
				'post_type' 		=> 'attachment',
				'post_status' 		=> null,
				'post_mime_type' 	=> 'image',
				'post_parent' 		=> $post->ID
				);
			$images = get_children( $args );

			foreach($images as $image):
				$img = wp_get_attachment_thumb_url( $image->ID );
				if ($img) break;
			endforeach;
		}

		if (empty($img))
        		$img = $this->ta_default;

		// try to resize local images
                $imgWidth = ( $imgWidth )  ? $imgWidth : $this->ta_width;
                $imgHeight = ( $imgHeight )  ? $imgHeight : $this->ta_height;

		include_once(sprintf("%s/resize.php", dirname(__FILE__)));
		$image = matthewruddy_image_resize( $img, $imgWidth, $imgHeight, false );

		if (is_wp_error($image)) // if we cant resize it just return original image url
			return $img;
		else
			return $image['url'];
	}

////////////////////////////////////////////////////////////////////////////////////////////

	public function ta_albumFormat($format='',$imgWidth=0,$imgHeight=0)
	{
		// set private variables once, instead of getting them once per thumb in loop
        	$this->ta_format = get_option( 'ta_format' );
        	$this->ta_width = get_option( 'ta_width' );
        	$this->ta_height = get_option( 'ta_height' );
        	$this->ta_default = get_option( 'ta_default' );

                $format = ( $format )  ? $format : $this->ta_format;
		if ($format == "list")
			include(sprintf("%s/loop-list.php", dirname(__FILE__)));
		else	// default "grid"
			include(sprintf("%s/loop-grid.php", dirname(__FILE__)));
	}

////////////////////////////////////////////////////////////////////////////////////////////

    } // END class Theme_Album
} // END if(!class_exists('Theme_Album'))

////////////////////////////////////////////////////////////////////////////////////////////

if(class_exists('Theme_Album'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('Theme_Album', 'activate'));
	register_deactivation_hook(__FILE__, array('Theme_Album', 'deactivate'));

	// instantiate the plugin class
	$theme_album = new Theme_Album();
}

////////////////////////////////////////////////////////////////////////////////////////////
