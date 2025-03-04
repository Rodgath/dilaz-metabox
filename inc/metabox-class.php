<?php
/*
|| --------------------------------------------------------------------------------------------
|| Metabox Class
|| --------------------------------------------------------------------------------------------
||
|| @package    Dilaz Metabox
|| @subpackage Metabox Class
|| @since      Dilaz Metabox 1.0
|| @author     Rodgath, https://github.com/Rodgath
|| @copyright  Copyright (C) 2017, Rodgath LTD
|| @link       https://github.com/Rodgath/Dilaz-Metabox
|| @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
||
*/

namespace DilazMetabox\Dilaz_Meta_Box;

defined('ABSPATH') || exit;

use DilazMetabox\DilazMetaboxFields;
use DilazMetabox\DilazMetaboxFunction;
use DilazMetabox\DilazMetaboxDefaults;

/**
 * Metabox class
 */
if (!class_exists('Dilaz_Meta_Box')) {
	class Dilaz_Meta_Box {

		# Holds meta box prefix
		protected $_prefix;

		# Holds meta box object
		protected $_meta_box;

		# Holds meta box parameters
		protected $_params;

		# PHP Contructor method
		function __construct($prefix, $meta_box, $parameters) {

			# bail if we are not in Admin area
			if (!is_admin()) return;

			# metabox prefix
			$this->_prefix = $prefix;

			# metabox parameters
			$this->_params = $parameters;

			# Assign meta box values to local variables
			$this->_meta_box = $meta_box;

			add_action('admin_init', array(&$this, 'adminInit'));
			add_action('add_meta_boxes', array(&$this, 'addMetaBox')); # Add metaboxes
			add_action('save_post', array(&$this, 'saveMetaBox')); # Save post meta
			add_action('admin_enqueue_scripts', array(&$this, 'loadScriptsAndStyles')); # Enqueue common styles and scripts
			add_action('admin_body_class', array(&$this, 'adminBodyClass')); # Append body class
      add_filter('ext2type', array(&$this, 'addSVGToExtTypes'));
		}


		/**
		 * Initialize Metaboxes
		 *
		 * @since 1.0
		 *
		 * @return mixed
		 */
		function adminInit() {
			require_once DILAZ_MB_DIR .'inc/fields.php';
			require_once DILAZ_MB_DIR .'inc/defaults.php';
		}


		/**
		 * Append custom classe to admin body tag.
		 *
		 * @since 2.5.83
		 * @param   string $classes
		 * @return  string
		 */
		public function adminBodyClass( $classes ) {

			$classes .= ' dilaz-metabox-ui';
			return $classes;
		}

		/**
		 * Load Scripts and Styles
		 *
		 * @since 1.0
		 * @param string $hook The hook name (also known as the hook suffix) used to determine the screen.
		 *
		 * @return void
		 */
		function loadScriptsAndStyles($hook) {

			# only enqueue our scripts/styles specific pages
			if ( $hook == 'post.php' || $hook == 'post-new.php' || $hook == 'page-new.php' || $hook == 'page.php' || $hook == 'edit.php' ) {

				do_action('dilaz_mb_before_scripts_enqueue', $this->_prefix, $this->_meta_box, $this->_params);

				# scripts included with WordPress
				if (function_exists('wp_enqueue_media')) {
					wp_enqueue_media();
				} else {
					wp_enqueue_style('thickbox');
					wp_enqueue_script('thickbox');
					wp_enqueue_script('media-upload');
				}
				wp_enqueue_script('jquery-ui-slider');
				wp_enqueue_script('jquery-ui-sortable');
				wp_enqueue_script('jquery-ui-datepicker');

				# stepper scripts
				if ($this->hasField('stepper')) {

					# file version based on last update
					$dilaz_mb_stepper_js_ver       = date('ymd-Gis', filemtime( DILAZ_MB_DIR .'assets/js/stepper.min.js' ));
					$dilaz_mb_stepperscript_js_ver = date('ymd-Gis', filemtime( DILAZ_MB_DIR .'assets/js/stepper-script.js' ));

					wp_enqueue_script('dilaz-mb-stepper', DILAZ_MB_URL .'assets/js/stepper.min.js', array('jquery'), $dilaz_mb_stepper_js_ver, true);
					wp_enqueue_script('dilaz-mb-stepperscript', DILAZ_MB_URL .'assets/js/stepper-script.js', array('dilaz-mb-select2'), $dilaz_mb_stepperscript_js_ver, true);
				}

				# select 2 scripts
				if ($this->hasField(array('select', 'queryselect', 'timezone'))) {
					if ($this->hasFieldArg('select2', 'select2single') || $this->hasFieldArg('select2', 'select2multiple')) {

						# file version based on last update
						$dilaz_mb_select2_css_ver         = date('ymd-Gis', filemtime( DILAZ_MB_DIR .'assets/css/select2.min.css' ));
						$dilaz_mb_select2_js_ver          = date('ymd-Gis', filemtime( DILAZ_MB_DIR .'assets/js/select2/select2.min.js' ));
						$dilaz_mb_select2_sortable_js_ver = date('ymd-Gis', filemtime( DILAZ_MB_DIR .'assets/js/select2/select2.sortable.js' ));
						$dilaz_mb_select2script_js_ver    = date('ymd-Gis', filemtime( DILAZ_MB_DIR .'assets/js/select2-script.js' ));

						wp_enqueue_style('dilaz-mb-select2', DILAZ_MB_URL .'assets/css/select2.min.css', false, $dilaz_mb_select2_css_ver, false);
						wp_enqueue_script('dilaz-mb-select2', DILAZ_MB_URL .'assets/js/select2/select2.min.js', array('jquery'), $dilaz_mb_select2_js_ver, true);
						wp_enqueue_script('dilaz-mb-select2-sortable', DILAZ_MB_URL .'assets/js/select2/select2.sortable.js', array('dilaz-mb-select2'), $dilaz_mb_select2_sortable_js_ver, true);
						wp_enqueue_script('dilaz-mb-select2script', DILAZ_MB_URL .'assets/js/select2-script.js', array('dilaz-mb-select2'), $dilaz_mb_select2script_js_ver, true);
					}
				}

				# color picker
				if ($this->hasField('color')) {
					wp_enqueue_style('wp-color-picker');
					wp_enqueue_script('wp-color-picker');

					# file version based on last update
					$dilaz_mb_color_script_js_ver = date('ymd-Gis', filemtime( DILAZ_MB_DIR .'assets/js/color-script.js' ));
					$dilaz_mb_color_alpha_js_ver  = date('ymd-Gis', filemtime( DILAZ_MB_DIR .'assets/js/wp-color-picker-alpha.min.js' ));

					wp_enqueue_script('dilaz-ma-color-alpha', DILAZ_MB_URL .'assets/js/wp-color-picker-alpha.min.js', array('wp-color-picker'), $dilaz_mb_color_alpha_js_ver, true);
					wp_enqueue_script('dilaz-mb-color-script', DILAZ_MB_URL .'assets/js/color-script.js', array('wp-color-picker'), $dilaz_mb_color_script_js_ver, true);
				}

				# datepicker scripts
				if ($this->hasField(array('date', 'date_from_to', 'month', 'month_from_to', 'time', 'time_from_to', 'date_time', 'date_time_from_to'))) {
					wp_enqueue_script('jquery-ui-datepicker');
					wp_enqueue_style('jquery-ui-datepicker');
				}

				# date scripts
				if ($this->hasField(array('date', 'date_from_to'))) {

					# file version based on last update
					$dilaz_mb_date_script_js_ver = date('ymd-Gis', filemtime( DILAZ_MB_DIR .'assets/js/date-script.js' ));

					wp_enqueue_script('dilaz-mb-date-script', DILAZ_MB_URL .'assets/js/date-script.js', array('jquery-ui-datepicker'), $dilaz_mb_date_script_js_ver, true);
				}

				# monthpicker scripts
				if ($this->hasField(array('month', 'month_from_to'))) {

					# file version based on last update
					$dilaz_mb_monthpicker_js_ver = date('ymd-Gis', filemtime( DILAZ_MB_DIR .'assets/js/jquery-ui-monthpicker.min.js' ));
					$dilaz_mb_date_script_js_ver = date('ymd-Gis', filemtime( DILAZ_MB_DIR .'assets/js/date-script.js' ));

					wp_enqueue_script('dilaz-mb-monthpicker', DILAZ_MB_URL .'assets/js/jquery-ui-monthpicker.min.js', array('jquery-ui-datepicker'), $dilaz_mb_monthpicker_js_ver, true);
					wp_enqueue_script('dilaz-mb-date-script', DILAZ_MB_URL .'assets/js/date-script.js', array('dilaz-mb-monthpicker'), $dilaz_mb_date_script_js_ver, true);
				}

				# datepicker & timepicker scripts
				if ($this->hasField(array('time', 'time_from_to', 'date_time', 'date_time_from_to'))) {
					wp_enqueue_style('jquery-ui-datepicker');

					# file version based on last update
					$dilaz_mb_timepicker_js_ver  = date('ymd-Gis', filemtime( DILAZ_MB_DIR .'assets/js/jquery-ui-timepicker.min.js' ));
					$dilaz_mb_date_script_js_ver = date('ymd-Gis', filemtime( DILAZ_MB_DIR .'assets/js/date-script.js' ));

					wp_enqueue_script('dilaz-mb-timepicker', DILAZ_MB_URL .'assets/js/jquery-ui-timepicker.min.js', array('jquery-ui-datepicker', 'jquery-ui-slider'), $dilaz_mb_timepicker_js_ver, true);
					wp_enqueue_script('dilaz-mb-date-script', DILAZ_MB_URL .'assets/js/date-script.js', array('dilaz-mb-timepicker'), $dilaz_mb_date_script_js_ver, true);
				}

				# doWhen script
				wp_enqueue_script('dilaz-dowhen-script', DILAZ_MB_URL .'assets/js/jquery.dowhen.js');

				# metabox scripts
				$dilaz_mb_script_js_ver = date('ymd-Gis', filemtime( DILAZ_MB_DIR .'assets/js/metabox.js' )); # file version based on last update
				wp_enqueue_script('dilaz-mb-script', DILAZ_MB_URL .'assets/js/metabox.js', array('jquery-ui-slider'), $dilaz_mb_script_js_ver, true);

				# translation
				wp_localize_script('dilaz-mb-script', 'dilaz_mb_lang', apply_filters('dilaz_mb_localized_data', array(
					'dilaz_mb_url' => DILAZ_MB_URL,
					'dilaz_mb_images' => $this->_params['dir_url'] .'assets/images/',
					'dilaz_mb_prefix' => $this->_prefix
				)));

				# Webfont styles
				$meterial_css_ver = date('ymd-Gis', filemtime( DILAZ_MB_DIR .'assets/css/materialdesignicons.min.css' )); # file version based on last update
				wp_enqueue_style('material-webfont', DILAZ_MB_URL .'assets/css/materialdesignicons.min.css', false, $meterial_css_ver);

				do_action('dilaz_mb_before_main_style_enqueue', $this->_prefix, $this->_meta_box, $this->_params);

				# metabox styles
				$dilaz_mb_style_css_ver = date('ymd-Gis', filemtime( DILAZ_MB_DIR .'assets/css/metabox.min.css' )); # file version based on last update
				wp_enqueue_style('dilaz-metabox-style', DILAZ_MB_URL .'assets/css/metabox.min.css', array('thickbox'), $dilaz_mb_style_css_ver);

				do_action('dilaz_mb_after_scripts_enqueue', $this->_prefix, $this->_meta_box);
			}
		}


		/**
		 * metabox sets and metabox tabs array
		 *
		 * @since 1.0
		 *
		 * @return array
		 */
		function metaBoxGroups() {
			$meta_groups = array();
			foreach ($this->_meta_box as $key => $val) {
				if (isset($val['type'])) {
					if ($val['type'] == 'metabox_set' || $val['type'] == 'metabox_tab') {
						$meta_groups[] = $val;
					}
				}
			}

			return $meta_groups;
		}


		/**
		 * All metabox content
		 *
		 * @since 1.0
		 *
		 * @return array
		 */
		function metaBoxContent() {

			$parent       = 0;
			$tab          = 0;
			$box_contents = array();

			foreach ($this->_meta_box as $key => $val) {

				if (!isset($val['type'])) continue;

				$metabox_set_id = sanitize_key($val['id']);

				if (isset($val['type'])) {

					if ($val['type'] == 'metabox_set') {
						$box_contents[$metabox_set_id] = $val;
						$parent = $metabox_set_id;
					}

					if ($val['type'] == 'metabox_tab') {
						$tab = $metabox_set_id;
					}

					$val['metabox_set_id'] = $tab;
				}

				$box_contents[$parent]['fields'][] = $val;
			}

			return $box_contents;
		}

		# Metabox tab menu items
		# =============================================================================================
		function metaBoxMenu() {

			$parent          = 0;
			$menu_items      = array();
			$meta_box_groups = $this->metaBoxGroups();

			if (!empty($meta_box_groups)) {
				foreach ($meta_box_groups as $key => $val) {

					$metabox_set_id = sanitize_key($val['id']);

					if ($val['type'] == 'metabox_set') {
						$menu_items[$metabox_set_id] = $val;
						$parent = $metabox_set_id;
					}

					if ($val['type'] == 'metabox_tab') {
						$menu_items[$parent]['children'][] = $val;
					}
				}
			}

			return $menu_items;
		}

		# Metabox tab menu items output
		# =============================================================================================
		function metaBoxMenuOutput($meta_box_id) {

			$menu_items = $this->metaBoxMenu();

			$menu = '';

			if (!empty($menu_items) && sizeof($menu_items) > 0) {
				foreach ($menu_items as $key => $val) {
					if ($meta_box_id == $val['id']) {
						if (isset($val['children']) && sizeof($val['children']) > 0) {
							$menu .= '<ul class="dilaz-mb-tabs-nav">';
							foreach ($val['children'] as $child) {

								if (isset($child['icon']) && ($child['icon'] != '')) {
									$icon = '<span class="mdi '. esc_attr($child['icon']) .'"></span>';
								} else {
									$icon = '<span class="mdi mdi-settings"></span>';
								}

								$menu .= '<li id="" class="dilaz-mb-tabs-nav-item">'. $icon .''. esc_html($child['title']) .'</li>';
							}
							$menu .= '</ul>';
						}
					}
				}
			}

			return $menu;
		}

		# Metabox option sets array
		# =============================================================================================
		function metaboxSets() {

			$meta_groups = $this->metaBoxGroups();

			$box_items = array();

			if (!empty($meta_groups)) {
				foreach ($meta_groups as $key => $val) {

					$metabox_set_id = sanitize_key($val['id']);

					if ($val['type'] == 'metabox_set') {
						$box_items[$metabox_set_id] = $val;
					}
				}
			}

			return $box_items;
		}

		# Metabox pages - pages where metaboxes should be shown
		# =============================================================================================
		function metaBoxPages() {

			$metabox_sets = $this->metaboxSets();

			$pages = array();

			foreach ($metabox_sets as $metabox_set_id => $metabox_set) {
				$pages[] = $metabox_set['pages'];
			}

			return $pages;
		}

		# Check if metabox has fields
		# =============================================================================================
		function hasField($field_types) {

			$pages = $this->metaBoxPages();
			$box_content = $this->metaBoxContent();

			# Add meta box for multiple post types
			foreach ($pages as $page) {
				foreach ((array)$box_content as $metabox_set_id => $metabox_set) {
					foreach ((array)$metabox_set['fields'] as $key => $field) {

						if (!isset($field['type'])) continue;

						if (is_array($field_types)) {
							if (!in_array($field['type'], $field_types)) continue;
							if (in_array($field['type'], $field_types)) return true;
						} else {
							if ($field['type'] != $field_types) continue;
							if ($field['type'] == $field_types) return true;
						}
					}
				}
			}

			return false;
		}

		# Check if metabox has field args
		# =============================================================================================
		function hasFieldArg($field_arg_key, $field_arg_val) {

			$pages       = $this->metaBoxPages();
			$box_content = $this->metaBoxContent();

			# Add meta box for multiple post types
			foreach ($pages as $page) {
				foreach ((array)$box_content as $metabox_set_id => $metabox_set) {
					foreach ((array)$metabox_set['fields'] as $key => $field) {

						if (!isset($field['args'])) continue;
						if (empty($field['args'])) continue;
						if (!isset($field['args'][$field_arg_key])) continue;

						if (is_array($field['args'][$field_arg_key])) {
							if (!in_array($field_arg_val, $field['args'][$field_arg_key])) continue;
							if (in_array($field_arg_val, $field['args'][$field_arg_key])) return true;
						} else {
							if ($field['args'][$field_arg_key] != $field_arg_val) continue;
							if ($field['args'][$field_arg_key] == $field_arg_val) return true;
						}
					}
				}
			}

			return false;
		}

		# Add metabox fields to a page
		# =============================================================================================
		function addMetaBox() {

			$pages     = $this->metaBoxPages();
			$box_items = $this->metaboxSets();

			foreach ($box_items as $box_item_key => $box_item) {

				$box_item['context']  = empty( $box_item['context'] ) ? 'normal' : $box_item['context'];
				$box_item['priority'] = empty( $box_item['priority'] ) ? 'high' : $box_item['priority'];

				# Add meta box for multiple post types
				foreach ((array)$box_item['pages'] as $page_key => $page) {
					add_meta_box( $box_item['id'], $box_item['title'], array(&$this, 'showMetaBox'), $page, $box_item['context'], $box_item['priority'], array($box_item['id']) );
				}
			}
		}

		# Show meta boxes
		# =============================================================================================
		function showMetaBox($page, $id) {

			global $post, $pages;

			$image_path = DILAZ_MB_DIR .'images/';

			# Add nonce for security
			echo '<input type="hidden" name="wp_meta_box_nonce" value="'. wp_create_nonce(basename(__FILE__)) .'" />';

			$dilaz_mb_wpx_class = '';

			if ( version_compare( $GLOBALS['wp_version'], '5', '>' ) && version_compare( $GLOBALS['wp_version'], '6', '<' ) ) {
				$dilaz_mb_wpx_class = 'dilaz-mb-wp5';
			}

			if ( version_compare( $GLOBALS['wp_version'], '6', '>' ) && version_compare( $GLOBALS['wp_version'], '7', '<' ) ) {
				$dilaz_mb_wpx_class = 'dilaz-mb-wp6';
			}

			echo '<div class="dilaz-metabox '. $dilaz_mb_wpx_class .'">';

				# Vertical Tabs
				echo '<div class="dilaz-mb-tabs">';

					# Tabs Navigation
					$meta_box_id = isset($id['id']) ? $id['id'] : '';
					if ($meta_box_id != '') {
						echo $this->metaBoxMenuOutput($meta_box_id);
					}

				echo '</div>';

				# Tabs Content
				echo '<div class="dilaz-mb-tabs-content">';

					if ($meta_box_id != '') {

						$meta_box_content = $this->metaBoxContent();

						$counter = 0;

						foreach ($meta_box_content[$meta_box_id]['fields'] as $key => $field) {

							$counter++;

							if (isset($field['type']) && $field['type'] == 'metabox_set') continue;

							# Set up blank or default values for empty fields
							if ( !isset( $field['id'] ) )         $field['id'] = '';
							if ( !isset( $field['type'] ) )       $field['type'] = '';
							if ( !isset( $field['name'] ) )       $field['name'] = '';
							if ( !isset( $field['std'] ) )        $field['std'] = '';
							if ( !isset( $field['args'] ) )       $field['args'] = '';
							if ( !isset( $field['state'] ) )      $field['state'] = '';
							if ( !isset( $field['class'] ) )      $field['class'] = '';
							if ( !isset( $field['req_id'] ) )     $field['req_id'] = '';
							if ( !isset( $field['req_value'] ) )  $field['req_value'] = '';
							if ( !isset( $field['req_args'] ) )   $field['req_args'] = '';
							if ( !isset( $field['req_cond'] ) )   $field['req_cond'] = '';
							if ( !isset( $field['req_action'] ) ) $field['req_action'] = '';
							if ( !isset( $field['hide_key'] ) )   $field['hide_key'] = '';
							if ( !isset( $field['hide_val'] ) )   $field['hide_val'] = '';

							# Desc setup
							$field['desc']   = isset($field['desc']) && $field['desc'] !== '' ? '<span class="description">'. esc_html($field['desc']) .'</span>' : '';
							$field['desc2']  = isset($field['desc2']) && $field['desc2'] !== '' ? '<span class="desc2">'. esc_html($field['desc2']) .'</span>' : '';
							$field['prefix'] = isset($field['prefix']) && $field['prefix'] !== '' ? '<span class="prefix">'. esc_html($field['prefix']) .'</span>' : '';
							$field['suffix'] = isset($field['suffix']) && $field['suffix'] !== '' ? '<span class="suffix">'. esc_html($field['suffix']) .'</span>' : '';

							# setup conditional fields
							$cond_fields = '';
							if ( !isset( $field['req_args'] ) || $field['req_args'] != '' ) {
								if ( !isset( $field['req_cond'] ) || $field['req_cond'] == '' ) {

									$cond_fields .= ' data-do-when=\'{';
										$do_when_ = '';
										foreach ( $field['req_args'] as $req_id => $req_value ) {
											if (is_array($req_value)) {
												foreach ($req_value as $key => $val) {
													$do_when_ .= ' "'. $req_id .'" : ["'. $val .'"]';
												}
											} else {
												$do_when_ .= ' "'. $req_id .'" : ["'. $req_value .'"]';
											}
										}
										$cond_fields .= $do_when_;
									$cond_fields .= ' }\' data-do-action="'. $field['req_action'] .'"';

								} else if ( $field['req_cond'] == 'AND' ) {

									$cond_fields .= ' data-do-when=\'{';
										$do_when_AND = '';
										foreach ( $field['req_args'] as $req_id => $req_value ) {
											if (is_array($req_value)) {
												foreach ($req_value as $key => $val) {
													$do_when_AND .= ' "'. $req_id .'" : ["'. $val .'"],';
												}
											} else {
												$do_when_AND .= ' "'. $req_id .'" : ["'. $req_value .'"],';
											}
										}
										$cond_fields .= rtrim( $do_when_AND, ',' ); # remove last comma
									$cond_fields .= ' }\' data-do-action="'. $field['req_action'] .'"';

								} else if ( $field['req_cond'] == 'OR' ) {

									$cond_fields .= ' data-do-when=\'';
										$do_when_OR = '';
										foreach ( $field['req_args'] as $req_id => $req_value ) {
											if (is_array($req_value)) {
												foreach ($req_value as $key => $val) {
													$do_when_OR .= '{ "'. $req_id .'" : ["'. $val .'"] } || ';
												}
											} else {
												$do_when_OR .= '{ "'. $req_id .'" : ["'. $req_value .'"] } || ';
											}
										}
										$cond_fields .= rtrim( $do_when_OR, '|| ' ); # remove dangling "OR" sign
									$cond_fields .= ' \' data-do-action="'. $field['req_action'] .'"';

								}
							}

							# hide specific fields on demand
							$hide = '';
							$post_object = get_post($post->ID, ARRAY_A);
							if ($post_object['post_type'] == 'dilaz_event_txns') {

								if ((!get_post_meta($post->ID, $this->_prefix .'event_txn_event_id', true) || !get_post_meta($post->ID, $this->_prefix .'event_txn_event_id', true)) && $field['hide_key'] == 'event_txn' && $field['hide_val'] == 1) {
									$hide = 'data-dilaz-hide="hidden"';
								}

								if ((!get_post_meta($post->ID, $this->_prefix .'event_txn_pkg_id', true) || !get_post_meta($post->ID, $this->_prefix .'event_txn_pkg_id', true)) && $field['hide_key'] == 'pkg_txn' && $field['hide_val'] == 1) {
									$hide = 'data-dilaz-hidden="yes"';
								}
							}

							# get post meta from each metabox
							$meta = get_post_meta($post->ID, $field['id'], true);

							# show value or default value
							$meta = ('' === $meta || array() === $meta) ? $field['std'] : $meta;

							# integrate variables into $field array
							$field['meta'] = $meta;

							# tab end/start sequence
							if (isset($field['type']) && $field['type'] == 'metabox_tab') {
								if ($counter >= 3) { echo '</div><!-- /.dilaz-meta-tab -->'; }
								echo '<div class="dilaz-meta-tab" id="'. esc_attr(sanitize_key($field['id'])) .'">';
							}

							if (isset($field['type']) && $field['type'] != 'metabox_tab' && $field['type'] != 'hidden') {

								if (isset($field['type']) && $field['type'] == 'header') {
									echo '<div class="dilaz-metabox-head row" '. esc_attr($hide) .'><div>'. $field['name'] .'</div><div></div>';
								} else {

									$section_id    = 'dilaz-mb-field-'. sanitize_key($field['id']);
									$section_class = 'dilaz-mb-field dilaz-mb-field-'. esc_attr($field['type']) .' '. sanitize_html_class($field['class']);

									# Get the current screen object
									$screen = get_current_screen();

									# post format support
									if ($field['args'] != '') {
										if (isset($field['args']['post_format']) && $screen->id == 'post') {
											$post_formats = is_array($field['args']['post_format']) ? implode(' ', $field['args']['post_format']) : '';
										} else {
											$post_formats = '';
										}
									} else {
										$post_formats = '';
									}

									# page template support
									if ($field['args'] != '') {
										if (isset($field['args']['page_template']) && $screen->id == 'page') {
											# use preg_filter() to add "page-" prefix to every array element in page_template array
											$page_templates = is_array($field['args']['page_template']) ? implode(' ', preg_filter('/^/', 'page-', $field['args']['page_template'])) : '';
										} else {
											$page_templates = '';
										}
									} else {
										$page_templates = '';
									}

									# adjacent fields - first field
									if ($field['state'] == 'joined_start') {
										echo '<div id="'. esc_attr($section_id) .'" class="row joined-state joined-start '. esc_attr($section_class) .'" '. $cond_fields .'><div class="left"><div class="header"><label for="'. esc_attr($field['id']) .'">'. esc_html($field['name']) .'</label>'. $field['desc'] .'</div></div><div class="right option"><div class="joined-table"><div class="joined-row"><div class="joined-cell">';

									# adjacent fields - middle fields
									} else if ($field['state'] == 'joined_middle') {
										echo '<div class="joined-cell"><div id="'. esc_attr($section_id) .'" class="joined-state joined-middle '. esc_attr($section_class) .'" '. $cond_fields .'>';

									# adjacent fields - last field
									} else if ($field['state'] == 'joined_end') {
										echo '<div class="joined-cell"><div id="'. esc_attr($section_id) .'" class="joined-state joined-end '. esc_attr($section_class) .'" '. $cond_fields .'>';
									} else {
										echo '<div id="'. esc_attr($section_id) .'" class="dilaz-metabox-item row '. esc_attr($section_class) .' '. $post_formats .' '. $page_templates .'" '. $cond_fields .' '. $hide .'>';
										if ($field['name'] != '') {
											echo '<div class="left"><div class="header"><label for="'. esc_attr($field['id']) .'">'. esc_html($field['name']) .'</label>'. $field['desc'] .'</div></div>';
										}
										echo '<div class="right option clearfix">';
									}
								}
							}

							switch ($field['type']) {

								case 'metabox_tab'       : break;
								case 'text'              : DilazMetaboxFields\DilazMetaboxFields::fieldText($field); break;
								case 'multitext'         : DilazMetaboxFields\DilazMetaboxFields::fieldMultiText($field); break;
								case 'password'          : DilazMetaboxFields\DilazMetaboxFields::fieldPassword($field); break;
								case 'hidden'            : DilazMetaboxFields\DilazMetaboxFields::fieldHidden($field); break;
								case 'paragraph'         : DilazMetaboxFields\DilazMetaboxFields::fieldParagraph($field); break;
								case 'codeoutput'        : DilazMetaboxFields\DilazMetaboxFields::fieldCodeOutput($field); break;
								case 'url'               : DilazMetaboxFields\DilazMetaboxFields::fieldUrl($field); break;
								case 'email'             : DilazMetaboxFields\DilazMetaboxFields::fieldEmail($field); break;
								case 'number'            : DilazMetaboxFields\DilazMetaboxFields::fieldNumber($field); break;
								case 'repeatable'        : DilazMetaboxFields\DilazMetaboxFields::fieldRepeatable($field); break;
								case 'stepper'           : DilazMetaboxFields\DilazMetaboxFields::fieldStepper($field); break;
								case 'code'              : DilazMetaboxFields\DilazMetaboxFields::fieldCode($field); break;
								case 'textarea'          : DilazMetaboxFields\DilazMetaboxFields::fieldTextarea($field); break;
								case 'editor'            : DilazMetaboxFields\DilazMetaboxFields::fieldEditor($field); break;
								case 'radio'             : DilazMetaboxFields\DilazMetaboxFields::fieldRadio($field); break;
								case 'checkbox'          : DilazMetaboxFields\DilazMetaboxFields::fieldCheckbox($field); break;
								case 'multicheck'        : DilazMetaboxFields\DilazMetaboxFields::fieldMultiCheck($field); break;
								case 'select'            : DilazMetaboxFields\DilazMetaboxFields::fieldSelect($field); break;
								case 'multiselect'       : DilazMetaboxFields\DilazMetaboxFields::fieldMultiSelect($field); break;
								case 'queryselect'       : DilazMetaboxFields\DilazMetaboxFields::fieldQuerySelect($field); break;
								case 'timezone'          : DilazMetaboxFields\DilazMetaboxFields::fieldTimezone($field); break;
								case 'radioimage'        : DilazMetaboxFields\DilazMetaboxFields::fieldRadioImage($field); break;
								case 'color'             : DilazMetaboxFields\DilazMetaboxFields::fieldColor($field); break;
								case 'multicolor'        : DilazMetaboxFields\DilazMetaboxFields::fieldMultiColor($field); break;
								case 'font'              : DilazMetaboxFields\DilazMetaboxFields::fieldFont($field); break;
								case 'date'              : DilazMetaboxFields\DilazMetaboxFields::fieldDate($field); break;
								case 'date_from_to'      : DilazMetaboxFields\DilazMetaboxFields::fieldDateFromTo($field); break;
								case 'month'             : DilazMetaboxFields\DilazMetaboxFields::fieldMonth($field); break;
								case 'month_from_to'     : DilazMetaboxFields\DilazMetaboxFields::fieldMonthFromTo($field); break;
								case 'time'              : DilazMetaboxFields\DilazMetaboxFields::fieldtime($field); break;
								case 'time_from_to'      : DilazMetaboxFields\DilazMetaboxFields::fieldTimeFromTo($field); break;
								case 'date_time'         : DilazMetaboxFields\DilazMetaboxFields::fieldDateTime($field); break;
								case 'date_time_from_to' : DilazMetaboxFields\DilazMetaboxFields::fieldDateTimeFromTo($field); break;
								case 'slider'            : DilazMetaboxFields\DilazMetaboxFields::fieldSlideRange($field); break;
								case 'range'             : DilazMetaboxFields\DilazMetaboxFields::fieldRange($field); break;
								case 'upload'            : DilazMetaboxFields\DilazMetaboxFields::fieldUpload($field); break;
								case 'buttonset'         : DilazMetaboxFields\DilazMetaboxFields::fieldButtonset($field); break;
								case 'switch'            : DilazMetaboxFields\DilazMetaboxFields::fieldSwitch($field); break;
								case $field['type']      : do_action('dilaz_mb_field_'. $field['type'] .'_hook', $field); break; # add custom field types via this hook

							}

							if (isset($field['type']) && $field['type'] != 'metabox_tab' && $field['type'] != 'hidden') {

								if (isset($field['type']) && $field['type'] == 'header') {
									echo '</div>';
								} else {
									if ($field['state'] == 'joined_start') {

										echo '</div><!-- /.joined-cell -->'; # .joined-cell for .joined_start

									} else if ($field['state'] == 'joined_middle') {

										echo '</div><!-- /.joined-middle -->'; # .joined-middle
										echo '</div><!-- /.joined-cell -->';   # .joined-cell covering .joined-middle

									} else if ($field['state'] == 'joined_end') {

										echo '</div><!-- /.joined-cell -->';  # .joined-cell covering .joined-end
										echo '</div><!-- /.joined-end -->';   # .joined-end
										echo '</div><!-- /.joined-row -->';   # .joined-row
										echo '</div><!-- /.joined-table -->'; # .joined-table
										echo '</div><!-- /.right -->';        # .right
										echo '</div><!-- /.joined-start -->'; # .joined-start

									} else {
										echo '</div></div>';
									}
								}
							}
						}
					}

					echo '<script>
						jQuery(document).ready(function(){
							jQuery(document).doWhen();
						});
						</script>';
					echo '</div><!-- /.dilaz-meta-tab last item -->';
				echo '</div><!-- /.dilaz-mb-tabs-content -->';
			echo '</div><!-- /.dilaz-metabox -->';
		}


		/**
		 * Sanitize meta field values
		 *
		 * @since 1.0
		 * @since 2.5.3 Sanitize RGB/RGBA/HSL/HSLA colors
		 *
		 * @param string $type  field type
		 * @param mixed  $input field entry value
		 * @param string $field $dilaz_meta_boxes field arguments keys
		 *
		 * @return mixed|void
		 */
		function sanitizeMeta($type, $input, $field = '') {

			switch ($type) {

				case 'text':
				case 'hidden':
				case 'switch':
				case 'password':
					return sanitize_text_field($input);
					break;

				case 'multitext':
					$output = [];
					foreach ((array)$input as $k => $v) {
						if (isset($field['options'][$k])) {
							$output[$k] = sanitize_text_field($v);
						}
					}
					return !empty($output) ? $output : '';
					break;

				case 'paragraph':
					return sanitize_textarea_field($input);
					break;

				case 'email':
					$sanitized_email = sanitize_email($input);
					return is_email($sanitized_email) ? $sanitized_email : '';
					break;

				case 'url':
					return esc_url_raw($input);
					break;

				case 'code':
				case 'textarea':
					return sanitize_textarea_field($input);
					break;

				case 'number':
				case 'integer':
				case 'slider':
				case 'stepper':
					return absint($input);
					break;

				case 'select':
				case 'radio':
				case 'radioimage':
				case 'buttonset':
					$output = '';
					$options = isset($field['options']) ? $field['options'] : '';
					if (isset($options[$input])) {
						$output = sanitize_text_field($input);
					}
					return $output;
					break;

				case 'multiselect':
					$output = [];
					foreach ((array)$input as $k => $v) {
						if (isset($field['options'][$v])) {
							$output[] = $v;
						}
					}
					return !empty($output) ? $output : '';
					break;

				case 'queryselect':
				case 'range':
					$output = [];
					foreach ((array)$input as $k => $v) {

						/*
						 * Save as strin using "sanitize_text_field"; makes it easy for meta queries
						 *
						 * Example 1 - Hard to run meta queries using SQL LIKE operator because both array keys and values are numeric
						 * a:1:{i:0;i:3;}  -- 'i' for 'integer', no quotes
						 *
						 * Example 2 - Easy to run meta queries using SQL LIKE operator  because array values are strings
						 * a:1:{i:0;s:1:"3";} // 's' for 'string', also note the double quotes
						 *
						 * Ex. 2 meta query example
						 *	'meta_query' => array(
						 * 		array(
						 *			'key'     => 'my_meta_key',
						 *			'value'   =>  '"3"',
						 *			'compare' => 'LIKE'
						 *		)
						 *	)
						 *
						 */
						$output[$k] = sanitize_text_field($v); //
					}
					return !empty($output) ? $output : '';
					break;

				case 'timezone':
					return ($input == '') ? '' : $input;
					break;

				case 'editor':
					return ($input == '') ? '' : $input;
					break;

				case 'checkbox':
					return ($input == '') ? false : (bool)$input;
					break;

				case 'multicheck':
					$output = [];
					foreach ((array)$input as $k => $v) {
						if (isset($field['options'][$k]) && $v == true) {
							$output[$k] = true;
						} else {
							$output[$k] = false;
						}
					}
					return !empty($output) ? $output : '';
					break;

				case 'repeatable':
					$output = [];
					foreach ((array)$input as $key => $value) {
						foreach ($value as $k => $v) {
							$output[$key][$k] = sanitize_text_field($v);
						}
					}
					return !empty($output) ? $output : '';
					break;

				case 'color':
					if ( FALSE !== stripos( $input, 'rgb' ) ) {
						$output = DilazMetaboxFunction\DilazMetaboxFunction::sanitize_rgb_color($input);
					} else if ( FALSE !== stripos( $input, 'hsl' ) ) {
						$output = DilazMetaboxFunction\DilazMetaboxFunction::sanitize_hsl_color($input);
					} else {
						$output = sanitize_hex_color($input);
					}
					return $output;
					break;

				case 'multicolor':
					$output = [];
					foreach ((array)$input as $k => $v) {
						if (isset($field['options'][$k])) {
							if ( FALSE !== stripos( $v, 'rgb' ) ) {
								$output[$k] = DilazMetaboxFunction\DilazMetaboxFunction::sanitize_rgb_color($v);
							} else if ( FALSE !== stripos( $v, 'hsl' ) ) {
								$output[$k] = DilazMetaboxFunction\DilazMetaboxFunction::sanitize_hsl_color($v);
							} else {
								$output[$k] = sanitize_hex_color($v);
							}
						}
					}
					return !empty($output) ? $output : '';
					break;

				case 'font':
					$output = array();
					foreach ((array)$input as $k => $v) {
						if (isset($field['options'][$k]) && $k == 'color') {
							if ( FALSE !== stripos( $v, 'rgb' ) ) {
								$output[$k] = DilazMetaboxFunction\DilazMetaboxFunction::sanitize_rgb_color($v);
							} else if ( FALSE !== stripos( $v, 'hsl' ) ) {
								$output[$k] = DilazMetaboxFunction\DilazMetaboxFunction::sanitize_hsl_color($v);
							} else {
								$output[$k] = sanitize_hex_color($v);
							}
						} else {
							$output[$k] = $v;
						}
					}
					return $output;
					break;

				case 'font':
					$output = array();
					foreach ((array)$input as $k => $v) {
						if ( ( isset($field['options'][$k]) && ($k == 'size' || $k == 'height') ) /* || $set_option */ ) {
							$output[$k] = is_int($v) ? absint($v) : '';
						} else if (isset($field['options'][$k]) && $k == 'color') {
							$output[$k] = sanitize_hex_color($v);
						} else if (isset($field['options'][$k]) && $k == 'subset') {
							$output[$k] = is_array($v) ? array_map('sanitize_text_field', $v) : sanitize_text_field($v);
						} else {
							$output[$k] = sanitize_text_field($v);
						}
					}
					return !empty($output) ? $output : '';
					break;

				case 'background':
					$output = array();
					foreach ((array)$input as $k => $v) {
						if (isset($field['options'][$k]) && $k == 'image') {
							$output[$k] = absint($v);
						} else if (isset($field['options'][$k]) && $k == 'color') {
							if ( FALSE !== stripos( $v, 'rgb' ) ) {
								$output[$k] = DilazMetaboxFunction\DilazMetaboxFunction::sanitize_rgb_color($v);
							} else if ( FALSE !== stripos( $v, 'hsl' ) ) {
								$output[$k] = DilazMetaboxFunction\DilazMetaboxFunction::sanitize_hsl_color($v);
							} else {
								$output[$k] = sanitize_hex_color($v);
							}
						} else if (isset($field['options'][$k]) && ($k == 'repeat' || $k == 'size' || $k == 'position' || $k == 'attachment' || $k == 'origin')) {
							$output[$k] = is_array($v) ? array_map('sanitize_text_field', $v) : sanitize_text_field($v);
						}
					}
					return !empty($output) ? $output : '';
					break;

				case 'upload':
					$output = array();
					$file_data = array();

					if (is_array($input)) {
						foreach ((array)$input as $key => $value) {
							foreach ((array)$value as $k => $v) {
								$file_data[$k][$key] = $v;
							}
						}

						foreach ($file_data as $k => $v) {
							$file_data[$k]['id'] = (empty($v['id']) && !empty($v['url'])) ? attachment_url_to_postid($v['url']) : absint($v['id']);
							$file_data[$k]['url'] = (empty($v['url']) && !empty($v['id'])) ? wp_get_attachment_url($v['id']) : esc_url($v['url']);
						}

						// if (sizeof($file_data) > 1) {

						// 	/* Lets delete the first item because its always empty for multiple files upload */
						// 	unset($file_data[sizeof($file_data)-1]);

						// 	/**
						// 	 * 'array_filter' used to remove zero-value entries
						// 	 * 'array_values' used to reindex the array and start from zero
						// 	 */
						// 	$file_data = array_values(array_filter($file_data));
						// } else {
						// 	return $file_data;
						// }

					}
          // else if (!is_array($input)) {
					// 	$file_data[0]['id']  = !empty($input) ? attachment_url_to_postid($input) : '';
					// 	$file_data[0]['url'] = !empty($input) ? esc_url($input) : '';
					// }

					$output = $file_data;
					return $output;
					break;

				case 'date':
				case 'month':
				case 'time':
				case 'date_time':
					return strtotime($input);
					break;

				case 'date_from_to':
				case 'month_from_to':
				case 'time_from_to':
				case 'date_time_from_to':
					$output = array();
					foreach ((array)$input as $k => $v) {
						$output[$k] = strtotime($v);
					}
					return !empty($output) ? $output : '';
					break;

				# sanitize custom field types via this filter hook
				case $type:
					$output = apply_filters('dilaz_mb_sanitize_field_'. $type .'_hook', $input, $field);
					return $output;
					break;

				default:
					return $input;
					break;
			}

		}

		/**
		 * Get metabox field ids from options file
		 *
		 * @since 2.4
		 *
		 * @return array|bool false if option is not set or option file does not exist
		 */
		public function getMetaboxFieldIdsFromFile() {

			$option_file = $this->_params['files'][2];

			if (is_file($option_file)) {

				$prefix = $this->_prefix;

				include $option_file;

				$field_ids = array();

				foreach ($dilaz_meta_boxes as $key => $val) {

					if (!isset($val['type'])) continue;

					$metabox_set_id = sanitize_key($val['id']);

					if (isset($val['type'])) {
						if ($val['type'] == 'metabox_set') continue;
						if ($val['type'] == 'metabox_tab') continue;
					}
					$field_ids[] = $metabox_set_id;
				}

				return array_unique($field_ids);
			} else {
				return false;
			}
		}

		/**
		 * Save/Update metabox ids in options table
		 * The 'ids' are used to delete removed metabox fields
		 *
		 * @since 2.4
		 *
		 * @return void
		 */
		public function saveMetaboxFieldIdsOption() {
			$option_fields = $this->getMetaboxFieldIds();
			update_option($this->_prefix.'metabox_fields', $option_fields);
		}

		/**
		 * Get saved metabox ids
		 * The 'ids' are used to delete removed metabox fields
		 *
		 * @since 2.4
		 *
		 * @return array|bool false if nothing found
		 */
		public function getMetaboxFieldIdsOption() {
			$fieldIdsOption = get_option($this->_prefix.'metabox_fields');
			return !empty($fieldIdsOption) && $fieldIdsOption ? $fieldIdsOption : '';
		}

		/**
		 * Get metabox ids
		 *
		 * @since 2.4
		 *
		 * @return array metabox field ids
		 */
		public function getMetaboxFieldIds() {

			# get all meta box option fields
			$meta_box_content = $this->metaBoxContent();

			# build all meta keys into an array
			$field_meta_array = array();
			if (!empty($meta_box_content)) {
				foreach ($meta_box_content as $key => $metabox_set) {
					foreach ($metabox_set['fields'] as $field_key => $field) {
						$field_meta_array[] = $field['id'];
					}
				}
			}

			return array_unique($field_meta_array);
		}

		# Delete removed meta options from DB
		# =============================================================================================
		function deleteRemovedMeta($post_id) {

			global $wpdb;

			$meta_box_fields = $this->getMetaboxFieldIdsOption();
			$saved_meta_array = isset($meta_box_fields) && $meta_box_fields != '' ? $meta_box_fields : array();

			# get all meta box option fields
			$meta_box_content = $this->metaBoxContent();

			# build all meta keys into an array
			$field_meta_array = array();
			if (!empty($meta_box_content)) {
				foreach ($meta_box_content as $key => $metabox_set) {
					foreach ($metabox_set['fields'] as $field_key => $field) {
						$field_meta_array[] = $field['id'];
					}
				}
			}
			$field_meta_array = $this->getMetaboxFieldIds();

			# get removed meta option fields
			$removed_data = array_diff($saved_meta_array, $field_meta_array);

			# delete the removed meta option fields from DB
			if (!empty($removed_data)) {
				foreach ($removed_data as $k => $field_id) {
					delete_post_meta($post_id, $field_id);
				}
			}
		}

		# Save data when post is edited
		# =============================================================================================
		function saveMetaBox($post_id) {

			# verify nonce - Security
			if (!isset($_POST['wp_meta_box_nonce']) || !wp_verify_nonce($_POST['wp_meta_box_nonce'], basename(__FILE__))) {
				return $post_id;
			}

			# check autosave
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return $post_id;
			}

			# add meta to post and not revision
			if ($the_post = wp_is_post_revision($post_id))
				$post_id = $the_post;

			# check permissions
			if ('page' == $_POST['post_type']) {
				if (!current_user_can('edit_page', $post_id)) {
					return $post_id;
				} else if (!current_user_can('edit_post', $post_id)) {
					return $post_id;
				}
			}

			# before save action hook
			do_action('dilaz_mb_before_save_post', $post_id);

			# save metabox data
			$meta_box_content = $this->metaBoxContent();
			if (!empty($meta_box_content)) {
				foreach ($meta_box_content as $key => $metabox_set) {
					foreach ($metabox_set['fields'] as $field_key => $field) {

						# ignore 'codeoutput' field
						if ($field['type'] == 'codeoutput') continue;

						$old = get_post_meta($post_id, $field['id'], true);
						$new = isset( $_POST[$field['id']] ) ? $_POST[$field['id']] : null;

						# sanitized option
						$sanitized_meta = $this->sanitizeMeta($field['type'], $new, $field);

						# Set any saved Google fonts to be loaded
						if ('font' == $field['type']) {

              $g_fonts = DilazMetaboxDefaults\DilazMetaboxDefaults::_getGoogleFonts();

              # Save Google fonts only, ignore other fonts
              if (isset($sanitized_meta['family'])) {
                if (isset($g_fonts[$sanitized_meta['family']])) {
                  $google_arr = get_post_meta($post_id, 'saved_google_fonts', true);
                  $google_arr = is_array($google_arr) ? $google_arr : [];

                  $google_arr[$field['id']] = $sanitized_meta;

                  update_post_meta($post_id, 'saved_google_fonts', $google_arr);
                }
              }
						}

						if ($new != $old && false !== $new && $field['type'] != 'checkbox') {
							update_post_meta($post_id, $field['id'], $sanitized_meta);
						} else if ($new != $old && $field['type'] == 'checkbox') {
							update_post_meta($post_id, $field['id'], $sanitized_meta);
						} else if ('' == $new && $old) {
							delete_post_meta($post_id, $field['id'], $old);
						}
					}
				}
			}

			$this->deleteRemovedMeta($post_id);
			$this->saveMetaboxFieldIdsOption();

			# after save action hook
			do_action('dilaz_mb_after_save_post', $post_id);
		}

		/**
		 * Add SVG to allowed extension types
     *
     * @since 3.1.0
     *
     * @see wp_get_ext_types()
     *
		 * @return array[] Multi-dimensional array of file extensions types including 'svg' type.
		 */
    public function addSVGToExtTypes($types)
    {
      if (!in_array('svg', $types['image'])) {
        $types['image'][] = 'svg';
      }
      return $types;
    }

	} # end class
}