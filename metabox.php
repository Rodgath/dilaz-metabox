<?php
/*
|| --------------------------------------------------------------------------------------------
|| Metabox
|| --------------------------------------------------------------------------------------------
||
|| @package		Dilaz Metaboxes
|| @subpackage	Metabox
|| @version		1.1
|| @since		Dilaz Metaboxes 1.0
|| @author		WebDilaz Team, http://webdilaz.com
|| @copyright	Copyright (C) 2017, WebDilaz LTD
|| @link		http://webdilaz.com/metaboxes
|| @License		GPL-2.0+
|| @License URI	http://www.gnu.org/licenses/gpl-2.0.txt
|| 
*/

defined('ABSPATH') || exit;


/**
 * Add metabox parameters
 *
 * @since	1.0
 *
 * @return	array
 */
function dilaz_metabox_parameters() {
	
	$metabox_parameters = apply_filters('dilaz_metabox_parameters', []);
	
	return $metabox_parameters;
}


/**
 * Config
 *
 * @since	1.1
 */
require_once file_exists(dirname(__FILE__) .'/inc/config.php') ? dirname(__FILE__) .'/inc/config.php' : dirname(__FILE__) .'/inc/config-sample.php';


/**
 * Globalize parameters
 */
$GLOBALS['dilaz_mb_params'] = dilaz_metabox_parameters();


/**
 * Functions
 */
require_once dirname(__FILE__) .'/inc/functions.php';


/**
 * Get URL from file
 *
 * @global	array	$dilaz_mb_params
 * @param	string	$file
 * @since	1.0
 *
 * @return	string|url
 */
function dilaz_mb_get_url($file) {
	
	global $dilaz_mb_params; 
	
	$parentTheme = wp_normalize_path(trailingslashit(get_template_directory()));
	$childTheme  = wp_normalize_path(trailingslashit(get_stylesheet_directory()));
	$file        = wp_normalize_path(trailingslashit((isset($dilaz_mb_params['use_type']) && $dilaz_mb_params['use_type'] == 'plugin') ? $file : dirname($file)));
	
	# if in a parent theme
	if (false !== stripos($file, $parentTheme)) {
		$dir = trailingslashit(str_replace($parentTheme, '', $file));
		$dir = $dir == './' ? '' : $dir;
		return trailingslashit(get_template_directory_uri()) . $dir;
	}
	
	# if in a child theme
	if (false !== stripos($file, $childTheme)) {
		$dir = trailingslashit(str_replace($childTheme, '', $file));
		$dir = $dir == './' ? '' : $dir;
		return trailingslashit(get_stylesheet_directory_uri()) . $dir;
	}
	
	# if in plugin
	return plugin_dir_url($file);
}


/**
 * Definitions
 */
@define('DILAZ_MB_URL', dilaz_mb_get_url(__FILE__));
@define('DILAZ_MB_DIR', plugin_dir_path(__FILE__));
@define('DILAZ_MB_IMAGES', DILAZ_MB_URL .'assets/images/');
@define('DILAZ_MB_PREFIX', (isset($GLOBALS['dilaz_mb_params']['prefix']) && $GLOBALS['dilaz_mb_params']['prefix'] != '') ? $GLOBALS['dilaz_mb_params']['prefix'] .'_' : 'dilaz_mb_');


/**
 * Include options
 */
if (isset($GLOBALS['dilaz_mb_params']['default_options']) && $GLOBALS['dilaz_mb_params']['default_options'] == false) {
	require_once file_exists(DILAZ_MB_DIR .'options/options.php') ? DILAZ_MB_DIR .'options/options.php' : DILAZ_MB_DIR .'options/options-sample.php';
}

if (isset($GLOBALS['dilaz_mb_params']['default_options']) && $GLOBALS['dilaz_mb_params']['default_options'] == true) {
	file_exists(DILAZ_MB_DIR .'options/default-options.php') ? require_once DILAZ_MB_DIR .'options/default-options.php' : '';
}

if (isset($GLOBALS['dilaz_mb_params']['custom_options']) && $GLOBALS['dilaz_mb_params']['custom_options'] == true) {
	require_once file_exists(DILAZ_MB_DIR .'options/custom-options.php') ? DILAZ_MB_DIR .'options/custom-options.php' : DILAZ_MB_DIR .'options/custom-options-sample.php';
}


/**
 * Initialize the metabox class
 *
 * @since	1.0
 *
 * @return	void
 */
add_action('init', 'dilaz_metaboxes_init', 9999);
function dilaz_metaboxes_init() {
	if (!class_exists('Dilaz_Meta_Box')) {
		require_once DILAZ_MB_DIR .'inc/metabox-class.php';
	}
}