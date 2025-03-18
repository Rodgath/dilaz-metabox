<?php
/*
 * Plugin Name:	Dilaz Metabox
 * Plugin URI:	https://github.com/Rodgath/Dilaz-Metabox
 * Description:	Create custom metaboxes for WordPress themes and plugins.
 * Author:		  Rodgath
 * Version:		  3.3.2
 * Author URI:	https://github.com/Rodgath
 * License:		  GPL-2.0+
 * License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
||
|| --------------------------------------------------------------------------------------------
|| Metabox
|| --------------------------------------------------------------------------------------------
||
|| @package		  Dilaz Metabox
|| @subpackage	Metabox
|| @version		  3.3.2
|| @since		    Dilaz Metabox 2.0
|| @author		  Rodgath, https://github.com/Rodgath
|| @copyright	  Copyright (C) 2017, Rodgath LTD
|| @link		    https://github.com/Rodgath/Dilaz-Metabox
|| @License		  GPL-2.0+
|| @License URI	http://www.gnu.org/licenses/gpl-2.0.txt
||
*/

namespace DilazMetabox;

defined('ABSPATH') || exit;

/**
 * DilazMetabox functions
 */
require_once plugin_dir_path(__FILE__) .'inc/functions.php';

/**
 * DilazMetabox defaults
 */
require_once plugin_dir_path(__FILE__) .'inc/defaults.php';


/**
 * DilazMetabox main class
 */
if (!class_exists('DilazMetabox')) {
	final class DilazMetabox {


		/**
		 * Metabox arguments
		 *
		 * @var array
		 * @since 2.5.82
		 */
		public $args = array();


		/**
		 * Metabox parameters
		 *
		 * @var array
		 * @since 2.0
		 */
		private $_params = array();


		/**
		 * All metaboxes
		 *
		 * @var array
		 * @since 2.5.82
		 */
		public $metaboxes = array();


		/**
		 * Metabox prefix
		 *
		 * @var string
		 * @since 2.0
		 */
		protected $_prefix;


		/**
		 * The single instance of the class
		 *
		 * @var string
		 * @since 2.0
		 */
		protected static $_instance = null;


		/**
		 * Main DilazMetabox instance
		 *
		 * Make sure only only one instance can be loaded
		 *
		 * @since 2.0
		 * @static
		 * @see DilazMetabox()
		 * @return DilazMetabox object - Main instance.
		 */
		public static function instance($metabox_args = array()) {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self($metabox_args);
			}
			return self::$_instance;
		}


		/**
		 * Cloning is forbidden
		 *
		 * @since 2.0
		 * @return void
		 */
		public function __clone() {
			_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'dilaz-metabox'), '2.0');
		}


		/**
		 * Unserializing instances of this class is forbidden
		 *
		 * @since 2.0
		 * @return void
		 */
		public function __wakeup() {
			_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'dilaz-metabox'), '2.0');
		}


		/**
		 * Contructor method
		 *
		 * @since 1.0
		 * @param array	$prefix metabox prefix
		 *
		 */
		function __construct($metabox_args = array()) {

			do_action('dilaz_metabox_before_load');

			$this->args      = $metabox_args;
			$this->_params   = $this->args[0];
			$this->metaboxes = $this->args[1];
			$this->_prefix   = DilazMetaboxFunction\DilazMetaboxFunction::preparePrefix($this->_params['prefix']);

			# Hooks
			add_action('init', array(&$this, 'init'));
			add_action('init', array(&$this, 'metaboxClass'));

			do_action('dilaz_metabox_after_load');

		}


		/**
		 * Initialize the metabox class
		 *
		 * @since	1.0
		 * @return	void
		 */
		public function metaboxClass() {
			if (!class_exists('Dilaz_Meta_Box\Dilaz_Meta_Box'))
				require_once DILAZ_MB_DIR .'inc/metabox-class.php';

			$prefix           = $this->_prefix;
			$parameters       = $this->_params;
			$dilaz_meta_boxes = array();
			$dilaz_meta_boxes = $this->metaboxes;
			$dilaz_meta_boxes = apply_filters('dilaz_meta_box_filter', $dilaz_meta_boxes, $prefix, $parameters);

			new Dilaz_Meta_Box\Dilaz_Meta_Box($prefix, $dilaz_meta_boxes, $parameters);
		}


		/**
		 * Initialize Admin Panel
		 *
		 * @since 1.0
		 * @return void
		 */
		public function init() {

			do_action('dilaz_metabox_before_init');

			add_action('wp_head', array($this, 'loadGoogleFonts'));

			# Load constants
			$this->constants();

			# Load parameters
			$this->parameters();

			# include required files
			$this->includes();

			do_action('dilaz_metabox_after_init');
		}


		/**
		 * Add metabox parameters
		 *
		 * @since	1.0
		 * @return	array
		 */
		public function parameters() {
			return $this->_params;
		}


		/**
		 * Constants
		 *
		 * @since 1.0
		 * @return void
		 */
		public function constants() {
			@define('DILAZ_MB_URL', plugin_dir_url(__FILE__));
			@define('DILAZ_MB_DIR', plugin_dir_path(__FILE__));
			@define('DILAZ_MB_IMAGES', DILAZ_MB_URL .'assets/images/');
		}


		/**
		 * Includes
		 *
		 * @since 1.0
		 * @return void
		 */
		public function includes() {

			do_action('dilaz_metabox_after_includes');

			do_action('dilaz_metabox_before_includes');
		}


		/**
		 * Load Google fonts in frontend
		 *
		 * @since 2.5.7
		 * @return mixed Google fonts head tag code
		 */
		public function loadGoogleFonts() {

			global $wp_query;

			if ($wp_query->queried_object_id == null) return;

			$savedGoogleFonts = get_post_meta($wp_query->queried_object_id, 'saved_google_fonts', true);

			if (empty($savedGoogleFonts)) return FALSE;

			$families   = array();
			$subsets    = array();
			$font_array = array();

			foreach ($savedGoogleFonts as $key => $font) {

				if (isset($font['family']) && $font['family'] != '') {

					$font_array[$font['family']]['family'] = $font['family'];

					if (isset($font['weight']) && in_array($font['weight'], ['100', '200', '300', '400', '500', '600', '700', '800', '900', '100i', '200i', '300i', '400i', '500i', '600i', '700i', '800i', '900i'])) {
						$font_style = (isset($font['style']) && $font['style'] != '') ? ($font['style'] == 'italic' ? 'i' : '') : '';
						$font_array[$font['family']]['weights'][] = $font['weight'] . $font_style;
					}

					$font_family = str_replace(' ', '+', $font_array[$font['family']]['family']);

					if (isset($font_array[$font['family']]['weights'])) {
						asort($font_array[$font['family']]['weights']);
						$families[$font_array[$font['family']]['family']] = $font_family . ':' . implode(',', array_unique(array_values($font_array[$font['family']]['weights'])));
					}

					if (isset($font['subset']) && $font['subset'] != '' && is_array($font['subset'])) {
						$subsets = array_merge($subsets, $font['subset']);
					}
				}

			}

			if (!empty($families)) {

				$query_args = array(
					'family'  => implode('|', $families),
					'display' => 'swap',
				);

				if (!empty($subsets)) {
					$query_args = array_merge($query_args, array('subset' => implode(',', array_values($subsets))));
				}

				$font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

				?>

				<!-- Code snippet to speed up Google Fonts rendering: googlefonts.3perf.com -->
				<link rel="dns-prefetch" href="https://fonts.gstatic.com">
				<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
				<link rel="preload" href="<?php echo $font_url; ?>" as="fetch" crossorigin="anonymous">
				<script type="text/javascript"> !function(e,n,t){"use strict";var o="<?php echo $font_url; ?>",r="__3perf_googleFonts_<?php echo (!empty($this->_prefix) ? $this->_prefix : 'dilaz'); ?>";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
				</script>
				<!-- End of code snippet for Google Fonts -->

				<?php
			}
		}

	}
}

# Dilaz metabox get use type based on current metabox usage
function dilaz_metabox_get_use_type($filename) {
	if (false !== strpos(dirname($filename), '\plugins\\') || false !== strpos(dirname($filename), '/plugins/')) {
		return 'plugin';
	} else if (false !== strpos(dirname($filename), '\themes\\') || false !== strpos(dirname($filename), '/themes/')) {
		return 'theme';
	} else {
		return false;
	}
}

# Dilaz metabox theme object
function dilaz_metabox_theme_params($theme_object, $filename) {

	$theme_name    = is_child_theme() ? $theme_object['Template'] : $theme_object['Name'];
	$theme_name_lc = strtolower($theme_name);
	$theme_version = $theme_object['Version'];
	$theme_uri     = is_child_theme() ? get_stylesheet_directory_uri() : get_template_directory_uri();
	$theme_folder  = basename($theme_uri);

	/*
	 * If the theme folder name string appears multiple times,
	 * lets split the string as shown below and focus only
	 * on the last theme folder name string
	 */
	$split_1      = explode('includes', dirname($filename));
	$split_2      = explode($theme_folder, $split_1[0]);
	$split_2_last = array_pop($split_2);

	$use_type_parameters = array(
		'item_name'    => $theme_name,
		'item_version' => $theme_version,
		'item_url'     => trailingslashit($theme_uri),
		'dir_url'      => trailingslashit($theme_uri . wp_normalize_path($split_2_last)),
	);

	return $use_type_parameters;
}

# Dilaz metabox plugin object
function dilaz_metabox_plugin_params($filename) {

	if (!function_exists('get_plugin_data')) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$plugin_data = [];

	$plugins_dir     = trailingslashit(WP_PLUGIN_DIR);
	$plugin_basename = plugin_basename($filename);
	$plugin_folder   = strtok($plugin_basename, '/');

	# use global to check plugin data from all PHP files within plugin main folder
	foreach (glob(trailingslashit($plugins_dir . $plugin_folder) . '*.php') as $file) {
		$plugin_data = get_plugin_data($file);

		# lets ensure we don't return empty plugin data
		if (empty($plugin_data['Name'])) continue; else break;
	}

	$plugin_name    = $plugin_data['Name'];
	$plugin_name_lc = strtolower($plugin_name);
	$plugin_version = $plugin_data['Version'];

	/*
	 * If the theme name string multiple times, lets
	 * split the string as show below and focus only
	 * on the last theme name string
	 */
	$split_1      = explode('includes', plugin_dir_url($filename));
	$split_2      = explode($plugin_folder, $split_1[0]);
	$split_2_last = array_pop($split_2);
	$split_3      = array($split_2_last, implode($plugin_folder, $split_2));

	$use_type_parameters = array(
		'item_name'    => $plugin_name,
		'item_version' => $plugin_version,
		'item_url'     => trailingslashit($split_3[1].$plugin_folder),
		'dir_url'      => trailingslashit($split_3[1].$plugin_folder.wp_normalize_path($split_3[0])),
	);

	return $use_type_parameters;
}

/* Add update checker */
require_once plugin_dir_path(__FILE__) . 'inc/update-checker/plugin-update-checker.php';

if (class_exists('Puc_v4_Factory')) {
  $dilazMetaboxUpdateChecker = \Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/Rodgath/Dilaz-Metabox/',
    __FILE__,
    'dilaz-metabox'
  );
  $dilazMetaboxUpdateChecker->setBranch('main');
}
