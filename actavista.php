<?php
/**
*Plugin Name: Actavista
*Plugin URI: http://themeforest.net/user/webinane/
*Description: Supported plugin for Actavista WordPress Theme
*Author: Webinane
*Version: 1.6
*Author URI: https://themeforest.net/user/webinane/
 *
 * @package actavista_Plugin
 */

defined( 'ACTAVISTA_PLUGIN_PATH' ) || define( 'ACTAVISTA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define('ACTAVISTA_PLUGIN_URI', plugins_url('actavista') . '/');
if ( ! class_exists( 'CMB2_Bootstrap_221' ) )
{
	if ( file_exists( dirname(__FILE__ ) . '/metabox/init.php' ) ) {
		require_once dirname( __FILE__ ) . '/metabox/init.php';
		require_once dirname( __FILE__ ) . '/metabox/cmb2-conditionals/cmb2-conditionals.php';
	}
}
if ( ! class_exists( 'Redux' ) ) {
	//require_once ACTAVISTA_PLUGIN_PATH . 'redux-framework/redux-framework.php';
}

require_once plugin_dir_path(__FILE__) . 'file_crop.php';
require_once plugin_dir_path(__FILE__) . 'instagram.php';

class ACTAVISTA_Plugin_Core {

	/**
	 * The instance variable.
	 * @var [type]
	 */
	public static $instance;

	/**
	 * The main constructor
	 */
	function __construct() {

		self::actions();
		self::includes();
		$this->init();

	}

	/**
	 * Load the instance.
	 *
	 * @return [type] [description]
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function actions() {
		add_action( 'admin_enqueue_scripts', 'admin_enqueue' );

	}

	public static function includes() {

		if ( class_exists( 'RWMB_Loader' ) ) {
			require_once ACTAVISTA_PLUGIN_PATH . '/meta-box-group/meta-box-group.php';
		}
		
		require_once ACTAVISTA_PLUGIN_PATH . '/inc/abstracts/class-post-type-abstract.php';
		require_once ACTAVISTA_PLUGIN_PATH . '/inc/abstracts/class-taxonomy-abstract.php';
		require_once ACTAVISTA_PLUGIN_PATH . '/inc/post_types/event.php';
		require_once ACTAVISTA_PLUGIN_PATH . '/inc/post_types/block.php';
		require_once ACTAVISTA_PLUGIN_PATH . '/inc/post_types/video.php';
		require_once ACTAVISTA_PLUGIN_PATH . '/inc/post_types/page.php';
		require_once ACTAVISTA_PLUGIN_PATH . '/inc/taxonomies.php';
	}

	function init() {
		ACTAVISTA\Inc\Post_Types\Event::init();
		ACTAVISTA\Inc\Post_Types\Block::init();
		ACTAVISTA\Inc\Post_Types\Video::init();
		ACTAVISTA\Inc\Post_Types\Page::init();
		add_action( 'init', array( '\ACTAVISTA\Inc\Taxonomies', 'init' ) );
		add_action( 'init', array( '\ACTAVISTA\Inc\Taxonomies', 'init2' ) );
		add_action( 'init', array( '\ACTAVISTA\Inc\Taxonomies', 'init3' ) );
	}

}
require_once ACTAVISTA_PLUGIN_PATH . '/videoCat_field.php';

/**
 * [actavista_get_sidebars description]
 *
 * @param  boolean $multi [description].
 * @return [type]         [description]
 */
if ( ! function_exists( 'actavista_get_sidebars' ) ) {
	function actavista_get_sidebars($multi = false) {
		global $wp_registered_sidebars;

		$sidebars = ! ( $wp_registered_sidebars ) ? get_option( 'wp_registered_sidebars' ) : $wp_registered_sidebars;

		if ( $multi ) {
			$data[] = array( 'value' => '', 'label' => 'No Sidebar' );
		} else {
			$data = array( '' => esc_html__( 'No Sidebar', 'hlc' ) );
		}

		foreach ( ( array ) $sidebars as $sidebar ) {

			if ( $multi ) {

				$data[] = array( 'value' => actavista_set( $sidebar, 'id'), 'label' => actavista_set( $sidebar, 'name' ) );
			} else {

				$data[ actavista_set( $sidebar, 'id' ) ] = actavista_set( $sidebar, 'name' );
			}
		}

		return $data;
	}
}

/**
 * [wpactavista_social_profiler description]
 *
 * @param  [type] $obj [description]
 * @return [type]      [description]
 */
function wpactavista_social_profiler() {
	return array(
		'drupal' => 'fa-drupal',
		'facebook' => 'fa-facebook',
		'google_plus' => 'fa-google-plus',
		'google_plus_square' => 'fa-google-plus-square',
		'instagram' => 'fa-instagram',
		'linkedIn' => 'fa-linkedin',
		'linkedIn_square' => 'fa-linkedin-square',
		'pinterest' => 'fa-pinterest',
		'pinterest_square' => 'fa-pinterest-square',
		'reddit' => 'fa-reddit',
		'reddit_square' => 'fa-reddit-square',
		'share_alt' => 'fa-share-alt',
		'skype' => 'fa-skype',
		'tumblr' => 'fa-tumblr',
		'tumblr_square' => 'fa-tumblr-square',
		'twitter' => 'fa-twitter',
		'twitter_square' => 'fa-twitter-square',
		'vimeo_square' => 'fa-vimeo-square',
		'youTube' => 'fa-youtube',
		'youTube_play' => 'fa-youtube-play',
		'youTube_square' => 'fa-youtube-square',
	);
}
function ACTAVISTA_P() {

	if ( ! isset( $GLOBALS['actavista_Plugin_p' ] ) ) {
		$GLOBALS['ACTAVISTA_Plugin' ] = ACTAVISTA_Plugin_Core::instance();
	}

	return $GLOBALS['ACTAVISTA_Plugin' ];
}

ACTAVISTA_P();

if ( ! function_exists( 'actavista_shortcode' ) ) {

	/**
	 * [actavista_shortcode description]
	 *
	 * @param  [type] $tag  [description]
	 * @param  [type] $func [description]
	 * @return [type]       [description]
	 */
	function actavista_shortcode( $tag, $func ) {

		add_shortcode( $tag, $func );
	}

	function actavista_admin_enqueue_scripts() {

		wp_enqueue_script( 'select2', ACTAVISTA_PLUGIN_URI . '/assets/select2.min.js' );
	}

	add_action( 'admin_enqueue_scripts', 'actavista_admin_enqueue_scripts' );

	function actavista_admin_enqueue_style() {
		wp_enqueue_style( 'fontqwesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css' );
	}

	add_action( 'admin_enqueue_scripts', 'actavista_admin_enqueue_style' );

}
if (!function_exists('actavista_set')) {

	function actavista_set($var, $key, $def = '') {

		if (is_object($var) && isset($var->$key))
			return $var->$key;
		elseif (is_array($var) && isset($var[$key]))
			return $var[$key];
		elseif ($def)
			return $def;
		else
			return false;
	}

}
function actavista_flat_icons() {

	$pattern = '/\.(flaticon-(?:\w+(?:-)?)+):before\s*{\s*content/';

	$subject = wp_remote_get(get_template_directory_uri() . '/assets/css/flaticon.css');

	preg_match_all($pattern, actavista_set($subject, 'body'), $matches, PREG_SET_ORDER);
	$icons = array();
	foreach ($matches as $match) {
		$new_val = ucwords( str_replace( '', '', $match[1] ) );
		$icons[$match[1]] = ucwords( str_replace( '-', ' ', $new_val ) );
	}
	return $icons;


}

function actavista_fontawesome_icons() {


	$pattern = '/\.(fa-(?:\w+(?:-)?)+):before\s*{\s*content/';

	$subject = wp_remote_get(get_template_directory_uri() . '/assets/css/font-awesome.min.css');

	preg_match_all($pattern, actavista_set($subject, 'body'), $matches, PREG_SET_ORDER);
	$icons = array();
	foreach ($matches as $match) {
		$new_val = ucwords( str_replace( 'fa-', '', $match[1] ) );
		$icons[$match[1]] = ucwords(  $new_val );
	}

	return $icons;


}

/**
		 * [admin_enqueue description]
		 *
		 * @return [type] [description]
		 */
function admin_enqueue() {

	wp_enqueue_style( 'actavista-admin-css', ACTAVISTA_PLUGIN_URI . 'assets/admin.css' );
	
	wp_enqueue_style( 'flaticon', get_template_directory_uri() . '/assets/css/flaticon.css' );
	
}

if ( ! function_exists( 'actavista_decrypt' ) ) {
	function actavista_decrypt( $param ) {
		return base64_decode($param);
	}
}

function actavista_form_register($post_data){

	if ( is_email( actavista_set( $post_data, 'email' ) ) ) {

		$random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
		$user_id = wp_create_user( actavista_set( $post_data, 'username' ), $random_password, actavista_set( $post_data, 'user_email' ) );
		if ( is_wp_error( $user_id ) && is_array( $user_id->get_error_messages() ) ) {
			foreach ( $user_id->get_error_messages() as $message ) $return_message .= '<p>' . $message . '</p>';
		} else {
			$return_message .= ( actavista_set( $opt, 'registration_success_message' ) ) ? '<div class="alert alert-success">' . actavista_set( $opt, 'registration_success_message' ) . '</div>' : '<div class="alert alert-success">' . esc_html__( 'Registration Successful - An email is sent', 'actavista' ) . '</div>';

		}

		if ( ! is_wp_error( $user_id ) ) {

			wp_new_user_notification( $user_id, null, 'both' );

			$message =esc_html__( 'Email ','actavista' ). actavista_set( $post_data, 'email' ).'\n';
			
			$message.=esc_html__( 'Password ','actavista' ). $random_password;

			wp_mail(actavista_set( $post_data, 'email' ), esc_html__( 'User Cradentials', 'actavista' ), $message,  '' );
		}
	} else {
		$return_message .= '<div class="alert alert-danger">' . esc_html__('Please enter valid email address', 'actavista' ) . '</div>';
	}


	echo json_encode( array( 'loggedin' => false, 'message' => $return_message ) );
	die();
}

function actavista_encrypt($param) {
	return base64_encode($param);
}


function actavista_decrypt($param) {
	return base64_decode($param);
}
/**
 * [actavista_get_posts_blocks description]
 *
 * @param  string  $post_type [description].
 * @param  boolean $flip      [description].
 * @return [type]             [description]
 */
if ( ! function_exists( 'actavista_get_posts_blocks' ) ) {
	function actavista_get_posts_blocks( $post_type = 'post', $flip = false ) {

		global $wpdb;

		$res = $wpdb->get_results( $wpdb->prepare( "SELECT `ID`, `post_title` FROM `" . $wpdb->prefix . "posts` WHERE `post_type` = %s AND `post_status` = %s ", array($post_type, 'publish' ) ), ARRAY_A );

		$return = array();

		if ( $flip ) {
			//$return[ esc_html__( 'Choose', 'actavista' ) ] = '';
		} else {
			//$return[0] = esc_html__( 'Choose', 'actavista' );
		}

		foreach ( $res as $k => $r ) {

			if ( $flip ) {

				if ( isset( $return[ actavista_set( $r, 'post_title' ) ] ) ) {
					$return[actavista_set( $r, 'post_title') . $k ] = actavista_set( $r, 'ID' );
				} else {
					$return[ actavista_set( $r, 'post_title' ) ] = actavista_set( $r, 'ID' );
				}
			} else {
				$return[ actavista_set( $r, 'ID' ) ] = actavista_set( $r, 'post_title' );
			}
		}
		return $return;
	}

}


function actavista_ajax_array_security_encode( $data = array() ) {
	if ( ! empty( $data ) ) {
		$data = http_build_query( $data );
		$data = actavista_encrypt( $data );

		return $data;
	}

}
function actavista_ajax_array_security_decode( $data ) {
	if ( ! empty( $data ) ) {
		$data = actavista_decrypt( $data );
		parse_str( $data, $data );

		return $data;
	}

}

function actavista_output( $output ) {
	echo $output;
}
if ( ! function_exists( 'actavista_load_template' ) ) {
	/**
	 * actavista_load_template
	 *
	 * @param  mixed $file_path
	 * @return void
	 */
	function actavista_load_template( $file_path ) {
		$theme_location = get_theme_file_path( "actavista/{$file_path}" );
		//print_r($theme_location);
		if ( file_exists( $theme_location ) ) {
			return $theme_location;
		}

		$theme_location = ACTAVISTA_PLUGIN_PATH . "{$file_path}";

		if ( file_exists( $theme_location ) ) {
			return $theme_location;
		}

		return get_theme_file_path( $file_path );
	}
}

if ( ! function_exists( 'actavista_remote_get_json_content' ) ) {
	/**
	 * actavista_remote_get_json_content
	 *
	 * @param  mixed $file_url
	 * @param  mixed $array
	 * @return void
	 */
	function actavista_remote_get_json_content( $file_url, $array = false, $args = array() ) {
		$try = wp_remote_get( $file_url, $args );
		if ( ! is_wp_error( $try ) ) {
			$try = json_decode( $try['body'], $array );
		} else {
			$try = json_decode( file_get_contents( $file_url ), $array );
		}

		if ( ! $try ) {
			return array();
		}

		return $try;
	}
}

if ( ! function_exists( 'lifeline2_remote_get_json_content' ) ) {
	/**
	 * lifeline2_remote_get_json_content
	 *
	 * @param  mixed $file_url
	 * @param  mixed $array
	 * @return void
	 */
	function lifeline2_remote_get_json_content( $file_url, $array = false, $args = array() ) {
		$try = wp_remote_get( $file_url, $args );
		if ( ! is_wp_error( $try ) ) {
			$try = lifeline2_json_dencrypt( $try['body'], $array );
		} else {
			$try = lifeline2_json_dencrypt( file_get_contents( $file_url ), $array );
		}

		if ( ! $try ) {
			return array();
		}

		return $try;
	}
}

if ( ! function_exists( 'lifeline2_json_dencrypt' ) ) {
	/**
	 * lifeline2_json_dencrypt
	 *
	 * @param  mixed $data
	 * @return void
	 */
	function lifeline2_json_dencrypt( $data, $array = false ) {
		return json_decode( $data, $array );
	}
}