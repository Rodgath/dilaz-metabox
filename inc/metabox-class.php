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
      add_filter('upload_mimes', array(&$this, 'allowSVGUpload'));
      add_filter('wp_handle_upload_prefilter', array(&$this, 'sanitizeSVG'));
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

					wp_enqueue_script('dilaz-mb-color-alpha', DILAZ_MB_URL .'assets/js/wp-color-picker-alpha.min.js', array('wp-color-picker'), $dilaz_mb_color_alpha_js_ver, true);
					wp_enqueue_script('dilaz-mb-color-script', DILAZ_MB_URL .'assets/js/color-script.js', array('wp-color-picker'), $dilaz_mb_color_script_js_ver, true);

          // Localize script for alpha color picker
          wp_localize_script('dilaz-mb-color-alpha', 'wpColorPickerL10n', array(
            'clear'            => __('Clear', 'dilaz-metabox'),
            'clearAriaLabel'   => __('Clear color', 'dilaz-metabox'),
            'defaultString'    => __('Default', 'dilaz-metabox'),
            'defaultAriaLabel' => __('Select default color', 'dilaz-metabox'),
            'pick'             => __('Select Color', 'dilaz-metabox'),
            'defaultLabel'     => __('Color value', 'dilaz-metabox'),
          ));
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

        # Inline style for preloader
        wp_add_inline_style('dilaz-metabox-style', '
          /* When preloader is present - set fixed height */
          .dilaz-metabox:has(.dilaz-metabox-preloader) {
            height: 200px;
            position: relative;
            overflow: hidden;
            transition: height 0.3s ease;
          }

          /* When preloader is hidden - remove height constraint */
          .dilaz-metabox:not(:has(.dilaz-metabox-preloader)) {
            height: auto;
            overflow: visible;
          }

          /* Preloader styles */
          .dilaz-metabox-preloader {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.3s ease;
          }

          .dilaz-metabox-preloader .spinnner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
          }

          /* Hidden state */
          .dilaz-metabox-preloader[hidden],
          .dilaz-metabox-preloader.hidden {
            opacity: 0;
            pointer-events: none;
          }

          @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
          }
        ');

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

                # page template support
                $page_templates = '';
                $page_templates_data_attr = '';
                if (isset($child['args']['page_template'])) {

                  # Get the current screen object
                  $screen = get_current_screen();

                  if ($screen->id == 'page') {
                    # use preg_filter() to add "page-" prefix to every array element in page_template array
                    if (is_array($child['args']['page_template']) && isset($child['args']['page_template'])) {
                      $page_templates = implode(' ', preg_filter('/^/', 'page-', $child['args']['page_template']));
                      if (count($child['args']['page_template']) > 0) {
                        $page_templates_data_attr = 'data-page-templates="' . esc_attr(implode(',', $child['args']['page_template'])) . '"';
                      }
                    }
                  }
                }

								$menu .= '<li id="'. esc_attr($child['id']) .'-tab" class="dilaz-mb-tabs-nav-item ' . esc_attr($page_templates) . '" ' . $page_templates_data_attr . '>'. $icon .''. esc_html($child['title']) .'</li>';
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
    function hasField($field_types)
    {

      $pages = $this->metaBoxPages();
      $box_content = $this->metaBoxContent();

      # Add meta box for multiple post types
      foreach ($pages as $page) {
        foreach ((array)$box_content as $metabox_set_id => $metabox_set) {
          if ($this->checkFieldsForType($metabox_set['fields'], $field_types)) {
            return true;
          }
        }
      }

      return false;
    }

    # Recursive function to check fields for a specific type
    # =============================================================================================
    function checkFieldsForType($fields, $field_types)
    {
      foreach ($fields as $field) {

        if (!isset($field['type'])) continue;

        # Handle 'option_group' fields recursively
        if ($field['type'] == 'option_group' && isset($field['group_options']) && is_array($field['group_options'])) {
          if ($this->checkFieldsForType($field['group_options'], $field_types)) {
            return true;
          }
          continue; // Skip the rest of the loop for the 'option_group' field itself
        }

        # Check if the field type matches
        if (is_array($field_types)) {
          if (in_array($field['type'], $field_types)) {
            return true;
          }
        } else {
          if ($field['type'] == $field_types) {
            return true;
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

    /**
     * Recursively processes an array of fields, setting up default values,
     * handling conditional visibility, and retrieving stored meta values.
     *
     * This function ensures that all required field attributes are initialized,
     * applies conditional logic for visibility, and formats descriptions and prefixes/suffixes.
     * It also manages the rendering of metabox tabs and grouped options while maintaining
     * the necessary data attributes for frontend behavior.
     *
     * @since 3.2.0
     *
     * @param array $fields   The array of fields to process.
     * @param object $post    The current post object.
     * @param int &$counter   A reference to the tab counter, used for metabox tab sequencing.
     *
     * @return array Processed field data with default values and conditional attributes applied.
     */
    function processFields($fields, $post, &$counter)
    {
      foreach ($fields as $key => $field) {

        if (isset($field['type']) && $field['type'] == 'metabox_set') continue;

        # Set up blank or default values for empty fields
        if (!isset($field['id']))         $field['id'] = '';
        if (!isset($field['type']))       $field['type'] = '';
        if (!isset($field['name']))       $field['name'] = '';
        if (!isset($field['std']))        $field['std'] = '';
        if (!isset($field['args']))       $field['args'] = '';
        if (!isset($field['state']))      $field['state'] = '';
        if (!isset($field['class']))      $field['class'] = '';
        if (!isset($field['req_id']))     $field['req_id'] = '';
        if (!isset($field['req_value']))  $field['req_value'] = '';
        if (!isset($field['req_args']))   $field['req_args'] = '';
        if (!isset($field['req_cond']))   $field['req_cond'] = '';
        if (!isset($field['req_action'])) $field['req_action'] = '';
        if (!isset($field['hide_key']))   $field['hide_key'] = '';
        if (!isset($field['hide_val']))   $field['hide_val'] = '';

        # Desc setup
        $field['desc']   = isset($field['desc']) && $field['desc'] !== '' ? '<span class="description">' . wp_kses_post($field['desc']) . '</span>' : '';
        $field['desc2']  = isset($field['desc2']) && $field['desc2'] !== '' ? '<span class="desc2">' . wp_kses_post($field['desc2']) . '</span>' : '';
        $field['prefix'] = isset($field['prefix']) && $field['prefix'] !== '' ? '<span class="prefix">' . esc_html($field['prefix']) . '</span>' : '';
        $field['suffix'] = isset($field['suffix']) && $field['suffix'] !== '' ? '<span class="suffix">' . esc_html($field['suffix']) . '</span>' : '';

        # setup conditional fields
        $cond_fields = '';
        if (!isset($field['req_args']) || $field['req_args'] != '') {
          if (!isset($field['req_cond']) || $field['req_cond'] == '') {
            $cond_fields .= ' data-do-when=\'{';
            $do_when_ = '';
            foreach ($field['req_args'] as $req_id => $req_value) {
              if (is_array($req_value)) {
                foreach ($req_value as $key => $val) {
                  $do_when_ .= ' "' . $req_id . '" : ["' . $val . '"]';
                }
              } else {
                $do_when_ .= ' "' . $req_id . '" : ["' . $req_value . '"]';
              }
            }
            $cond_fields .= $do_when_;
            $cond_fields .= ' }\' data-do-action="' . $field['req_action'] . '"';
          } else if ($field['req_cond'] == 'AND') {
            $cond_fields .= ' data-do-when=\'{';
            $do_when_AND = '';
            foreach ($field['req_args'] as $req_id => $req_value) {
              if (is_array($req_value)) {
                foreach ($req_value as $key => $val) {
                  $do_when_AND .= ' "' . $req_id . '" : ["' . $val . '"],';
                }
              } else {
                $do_when_AND .= ' "' . $req_id . '" : ["' . $req_value . '"],';
              }
            }
            $cond_fields .= rtrim($do_when_AND, ','); # remove last comma
            $cond_fields .= ' }\' data-do-action="' . $field['req_action'] . '"';
          } else if ($field['req_cond'] == 'OR') {
            $cond_fields .= ' data-do-when=\'';
            $do_when_OR = '';
            foreach ($field['req_args'] as $req_id => $req_value) {
              if (is_array($req_value)) {
                foreach ($req_value as $key => $val) {
                  $do_when_OR .= '{ "' . $req_id . '" : ["' . $val . '"] } || ';
                }
              } else {
                $do_when_OR .= '{ "' . $req_id . '" : ["' . $req_value . '"] } || ';
              }
            }
            $cond_fields .= rtrim($do_when_OR, '|| '); # remove dangling "OR" sign
            $cond_fields .= ' \' data-do-action="' . $field['req_action'] . '"';
          }
        }

        # hide specific fields on demand
        $hide = '';
        $post_object = get_post($post->ID, ARRAY_A);
        if ($post_object['post_type'] == 'dilaz_event_txns') {
          if ((!get_post_meta($post->ID, $this->_prefix . 'event_txn_event_id', true) || !get_post_meta($post->ID, $this->_prefix . 'event_txn_event_id', true)) && $field['hide_key'] == 'event_txn' && $field['hide_val'] == 1) {
            $hide = 'data-dilaz-hide="hidden"';
          }
          if ((!get_post_meta($post->ID, $this->_prefix . 'event_txn_pkg_id', true) || !get_post_meta($post->ID, $this->_prefix . 'event_txn_pkg_id', true)) && $field['hide_key'] == 'pkg_txn' && $field['hide_val'] == 1) {
            $hide = 'data-dilaz-hidden="yes"';
          }
        }

        if (isset($field['is_opt_group_field'])) {

          # Get post meta from each option_group metabox
          $meta = get_post_meta($post->ID, $field['group_parent_id'], true);

          # Show value or default value
          $meta = ('' === $meta || !is_array($meta)) ?
          (isset($field['std']) ? $field['std'] : '') :
          (isset($meta[$field['group_id']][$field['id']]) ? $meta[$field['group_id']][$field['id']] : '');

          # integrate variables into $field array
          $field['meta'] = $meta;

        } else {

          # get post meta from each metabox
          $meta = get_post_meta($post->ID, $field['id'], true);

          # show value or default value
          $meta = ('' === $meta || array() === $meta) ? $field['std'] : $meta;

          # integrate variables into $field array
          $field['meta'] = $meta;
        }

        # tab end/start sequence
        if (isset($field['type']) && $field['type'] == 'metabox_tab') {
          if ($counter >= 1) {
            echo '</div><!-- /.dilaz-meta-tab -->';
          }
          echo '<div class="dilaz-meta-tab" id="' . esc_attr(sanitize_key($field['id'])) . '">';
          $counter++; // Increment counter for each tab
        }

        if (isset($field['type']) && $field['type'] != 'metabox_tab' && $field['type'] != 'hidden') {
          if (isset($field['type']) && $field['type'] == 'option_group') {
            $group_saved_data = get_post_meta($post->ID, $field['metabox_set_id'], true);
            $is_open = isset($field['is_open']) ? wp_validate_boolean($field['is_open']) : false;
            $sort_index = isset($group_saved_data[$field['id']]['sort_index']) ? $group_saved_data[$field['id']]['sort_index'] : '';
            echo '<div class="dilaz-mb-opt-group-accordion-item" data-sortable="true">
            <input type="hidden" class="dilaz-mb-input"
              data-sort-index="' . esc_attr($sort_index) . '"
              name="' . esc_attr(sanitize_key($field['metabox_set_id'])) . '_accordion[]"
              value="' . esc_attr(sanitize_key($field['id'])) . '">
            <div class="dilaz-mb-opt-group-accordion-header ' . esc_attr($is_open ? 'open' : '') . '">
              <div>
                <span class="drag-handle">☰</span>
                <span>' . esc_html($field['name']) . '</span>
              </div>
              <div class="toggle-handle">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-down"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 9l6 6l6 -6" /></svg>
              </div>
            </div>
            <div class="dilaz-mb-opt-group-accordion-content" style="' . esc_attr($is_open ? 'display:block' : '') . '">';
          } else {
            if (isset($field['type']) && $field['type'] == 'header') {
              echo '<div class="dilaz-metabox-head row" ' . esc_attr($hide) . '><div>' . esc_html($field['name']) . '</div><div></div>';
            } else {
              $section_id    = 'dilaz-mb-field-' . sanitize_key($field['id']);
              $section_class = 'dilaz-mb-field dilaz-mb-field-' . esc_attr($field['type']) . ' ' . sanitize_html_class($field['class']);

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
              $page_templates = '';
              $page_templates_data_attr = '';
              if ($field['args'] != '') {
                if (isset($field['args']['page_template']) && $screen->id == 'page') {
                  # use preg_filter() to add "page-" prefix to every array element in page_template array
                  if (is_array($field['args']['page_template']) && isset($field['args']['page_template'])) {
                    $page_templates = implode(' ', preg_filter('/^/', 'page-', $field['args']['page_template']));
                    if (count($field['args']['page_template']) > 0) {
                      $page_templates_data_attr = 'data-page-templates="' . implode(',', $field['args']['page_template']) . '"';
                    }
                  }
                }
              }

              # adjacent fields - first field
              if ($field['state'] == 'joined_start') {
                echo '<div id="' . esc_attr($section_id) . '" class="row joined-state joined-start ' . esc_attr($section_class) . '" ' . wp_kses_post($cond_fields ?: '') . '><div class="left"><div class="header"><label for="' . esc_attr($field['id']) . '">' . esc_html($field['name']) . '</label>' . $field['desc'] . '</div></div><div class="right option"><div class="joined-table"><div class="joined-row"><div class="joined-cell">';

                # adjacent fields - middle fields
              } else if ($field['state'] == 'joined_middle') {
                echo '<div class="joined-cell"><div id="' . esc_attr($section_id) . '" class="joined-state joined-middle ' . esc_attr($section_class) . '" ' . wp_kses_post($cond_fields ?: '') . '>';

                # adjacent fields - last field
              } else if ($field['state'] == 'joined_end') {
                echo '<div class="joined-cell"><div id="' . esc_attr($section_id) . '" class="joined-state joined-end ' . esc_attr($section_class) . '" ' . wp_kses_post($cond_fields ?: '') . '>';
              } else {
                if (isset($field['type']) && $field['type'] == 'info') {
                  echo '<div id="'. esc_attr($section_id) .'" class="dilaz-metabox-item row '. esc_attr($section_class) .' ' . $post_formats . ' ' . $page_templates . ' dilaz-mb-info-wrap clearfix" ' . $page_templates_data_attr . '>';
                } else {
                  echo '<div id="' . esc_attr($section_id) . '" class="dilaz-metabox-item row ' . esc_attr($section_class) . ' ' . $post_formats . ' ' . $page_templates . '" ' . $cond_fields . ' ' . $hide . ' ' . $page_templates_data_attr . '>';
                  if ($field['name'] != '') {
                    echo '<div class="left"><div class="header"><label for="' . esc_attr($field['id']) . '">' . esc_html($field['name']) . '</label>' . $field['desc'] . '</div></div>';
                  }
                  echo '<div class="right option clearfix">';
                }
              }
            }
          }
        }

        switch ($field['type']) {
          case 'metabox_tab'       : break;
          case 'info'              : DilazMetaboxFields\DilazMetaboxFields::fieldInfo($field); break;
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
          case 'option_group':
            if (isset($field['group_options']) && is_array($field['group_options'])) {
              foreach ($field['group_options'] as $index => &$option) {
                if (!is_array($option)) {
                  continue;
                }

                $option['is_opt_group_field'] = true;
                $option['group_parent_id'] = $field['metabox_set_id'] ?? null;
                $option['group_id'] = $field['id'] ?? null;
                $option['group_field_index'] = $index;
              }
              unset($option); // Avoid reference issues

              $this->processFields($field['group_options'], $post, $counter);
            }
            break;
          case $field['type']:
            do_action('dilaz_mb_field_' . $field['type'] . '_hook', $field); break; # add custom field types via this hook
        }

        if (isset($field['type']) && $field['type'] != 'metabox_tab' && $field['type'] != 'hidden') {
          if (isset($field['type']) && $field['type'] == 'option_group') {
            echo '</div><!-- /.dilaz-mb-opt-group-accordion-content -->';
            echo '</div><!-- /.dilaz-mb-opt-group-accordion-item -->';
          } else {
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
                if (isset($field['type']) && $field['type'] == 'info') {
                  echo '</div>'; # .dilaz-mb-info-wrap
                } else {
                  echo '</div></div>';
                }
              }
            }
          }
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

      # page template support
      $page_templates_classes = '';
      $page_templates_data_attr = '';

      $metabox_cb_args = isset($id['callback'][0]->_meta_box[1]['args']) ? $id['callback'][0]->_meta_box[1]['args'] : [];
      if (isset($id['callback'][0]->_meta_box[1]['id']) && $id['callback'][0]->_meta_box[1]['id'] === $id['id']) {
        if (isset($metabox_cb_args['page_template'])) {

          # Get the current screen object
          $screen = get_current_screen();

          if ($screen->id == 'page') {
            # use preg_filter() to add "page-" prefix to every array element in page_template array
            if (is_array($metabox_cb_args['page_template']) && isset($metabox_cb_args['page_template'])) {
              $page_templates_classes = implode(' ', preg_filter('/^/', 'page-', $metabox_cb_args['page_template']));
              if (count($metabox_cb_args['page_template']) > 0) {
                $page_templates_data_attr = 'data-page-templates="' . esc_attr(implode(',', $metabox_cb_args['page_template'])) . '"';
              }
            }
          }
        }
      }

			echo '<div class="dilaz-metabox '. $dilaz_mb_wpx_class . ' ' . $page_templates_classes . '" ' . $page_templates_data_attr . '>';
        echo '<div class="dilaz-metabox-preloader"><div class="spinnner"></div></div>';
				# Vertical Tabs
				echo '<div class="dilaz-mb-tabs dilaz-mb-d-none">';

					# Tabs Navigation
					$meta_box_id = isset($id['id']) ? $id['id'] : '';
					if ($meta_box_id != '') {
						echo $this->metaBoxMenuOutput($meta_box_id);
					}

				echo '</div>';

				# Tabs Content
				echo '<div class="dilaz-mb-tabs-content dilaz-mb-d-none">';

					if ($meta_box_id != '') {

						$meta_box_content = $this->metaBoxContent();

						$counter = 0;

						$this->processFields($meta_box_content[$meta_box_id]['fields'], $post, $counter); // Pass $counter by reference
					}

					echo '<script>
						jQuery(document).ready(function($){

              /**
               * Metabox postbox preloader manager
               * @since Dilaz Metabox 3.5.0
               */
              const managePreloader = function () {
                var observer = new MutationObserver(function (mutations) {
                  mutations.forEach(function (mutation) {
                    if ($(".dilaz-mb-tabs-content").length) {
                      observer.disconnect();
                      setTimeout(() => {
                        $(".dilaz-metabox-preloader").fadeOut(300, function () {
                          $(this).addClass("hidden");
                          $(this).closest(".dilaz-metabox").css("height", "auto");
                        });
                      }, 500);
                      $(".dilaz-mb-tabs, .dilaz-mb-tabs-content").removeClass("dilaz-mb-d-none");
                      jQuery(document).doWhen();
                    }
                  });
                });

                observer.observe(document.body, {
                  childList: true,
                  subtree: true
                });
              }

              managePreloader();

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
          $input = $input ?? ''; // Ensure $input is never null
					return sanitize_text_field($input);
					break;

				case 'multitext':
					$output = [];
          if (is_array($input)) {
            foreach ((array)$input as $k => $v) {
              if (isset($field['options'][$k])) {
                $output[$k] = sanitize_text_field($v);
              }
            }
					}
					return !empty($output) ? $output : '';
					break;

				case 'paragraph':
          $input = $input ?? ''; // Ensure $input is never null
					return sanitize_textarea_field($input);
					break;

				case 'email':
          $input = $input ?? ''; // Ensure $input is never null
					$sanitized_email = sanitize_email($input);
					return is_email($sanitized_email) ? $sanitized_email : '';
					break;

				case 'url':
          $input = $input ?? ''; // Ensure $input is never null
					return esc_url_raw($input);
					break;

				case 'code':
				case 'textarea':
          $input = $input ?? ''; // Ensure $input is never null
					return sanitize_textarea_field($input);
					break;

				case 'number':
				case 'integer':
				case 'slider':
				case 'stepper':
          $input = $input ?? ''; // Ensure $input is never null
					return absint($input);
					break;

				case 'select':
				case 'radio':
				case 'radioimage':
				case 'buttonset':
          $input = $input ?? ''; // Ensure $input is never null
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
          if (is_array($input)) {
            foreach ((array)$input as $k => $v) {
              if (isset($field['options'][$k]) && $v == true) {
                $output[$k] = true;
              } else {
                $output[$k] = false;
              }
            }
					}
					return !empty($output) ? $output : '';
					break;

				case 'repeatable':
					$output = [];
          if (is_array($input)) {
            foreach ((array)$input as $key => $value) {
              foreach ($value as $k => $v) {
                $output[$key][$k] = sanitize_text_field($v);
              }
            }
					}
					return !empty($output) ? $output : '';
					break;

				case 'color':
          if (!empty($input)) {
            if ( FALSE !== stripos( $input, 'rgb' ) ) {
              $output = DilazMetaboxFunction\DilazMetaboxFunction::sanitize_rgb_color($input);
            } else if ( FALSE !== stripos( $input, 'hsl' ) ) {
              $output = DilazMetaboxFunction\DilazMetaboxFunction::sanitize_hsl_color($input);
            } else {
              $output = sanitize_hex_color($input);
            }
          } else {
            $output = $input;
          }
					return $output;
					break;

				case 'multicolor':
					$output = [];
          if (is_array($input)) {
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
					}
					return !empty($output) ? $output : '';
					break;

				case 'font':
					$output = array();
          if (is_array($input)) {
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
					}
					return $output;
					break;

				case 'font':
					$output = array();
          if (is_array($input)) {
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
					}
					return !empty($output) ? $output : '';
					break;

				case 'background':
					$output = array();
          if (is_array($input)) {
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
          return !empty($input) ? strtotime($input) : 0;
					break;

				case 'date_from_to':
				case 'month_from_to':
				case 'time_from_to':
				case 'date_time_from_to':
					$output = array();
          if (is_array($input)) {
            foreach ((array)$input as $k => $v) {
              $output[$k] = !empty($v) ? strtotime($v) : 0;
            }
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
    function saveMetaBox($post_id)
    {

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
          $this->saveFields($metabox_set['fields'], $post_id); // Pass the parent key
        }
      }

      $this->deleteRemovedMeta($post_id);
      $this->saveMetaboxFieldIdsOption();

      # after save action hook
      do_action('dilaz_mb_after_save_post', $post_id);
    }

    /**
     * Recursively saves custom fields for a post.
     *
     * This function processes and saves different types of fields, including
     * standard fields, checkbox fields, Google fonts, and nested option groups.
     * It also ensures that 'option_group' fields are handled separately to avoid
     * overwriting data from different groups.
     *
     * @since 3.2.0
     *
     * @param array $fields   The array of custom fields to process.
     * @param int   $post_id  The ID of the post to which the fields belong.
     *
     * Workflow:
     * - Ignores 'codeoutput' fields.
     * - Processes 'option_group' fields recursively, storing nested data properly.
     * - Sanitizes and saves regular fields.
     * - Handles Google font fields separately and updates saved fonts.
     * - Manages and reorders accordion-based group fields before saving them.
     */
    function saveFields($fields, $post_id)
    {
      $group_data = []; // Array to store all option_group data

      # Get the order of the accordion items from the $_POST data
      $accordion_order = [];

      foreach ($fields as $field_key => $field) {

        # ignore 'codeoutput' field
        if ($field['type'] == 'codeoutput') continue;

        # Handle 'option_group' fields recursively
        if ($field['type'] == 'option_group' && isset($field['group_options']) && is_array($field['group_options'])) {
          $parent_key = $field['metabox_set_id']; // Parent key for the metabox set
          $group_key = $field['id']; // Parent key for the group
          $group_values = []; // Array to store child field values

          # Recursively process nested fields
          foreach ($field['group_options'] as $child_field) {
            $child_key = $child_field['id'];
            $child_value = isset($_POST[$child_key]) ? $_POST[$child_key] : '';

            # Sanitize the child field value
            $sanitized_child_value = $this->sanitizeMeta($child_field['type'], $child_value, $child_field);

            # Add the sanitized value to the group array
            $group_values[$child_key] = $sanitized_child_value;
          }

          # Add the group array to the parent group data
          $group_data[$parent_key][$group_key] = $group_values;

          # Prevent different/separate option group fields being bundled together
          continue; // Skip the rest of the loop for the 'option_group' field itself
        }

        # Handle regular fields
        $old = get_post_meta($post_id, $field['id'], true);
        $new = isset($_POST[$field['id']]) ? $_POST[$field['id']] : '';

        # Sanitize the field value
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

        # Save the field value
        if ($new != $old && false !== $new && $field['type'] != 'checkbox') {
          update_post_meta($post_id, $field['id'], $sanitized_meta);
        } else if ($new != $old && $field['type'] == 'checkbox') {
          update_post_meta($post_id, $field['id'], $sanitized_meta);
        } else if ('' == $new && $old) {
          delete_post_meta($post_id, $field['id'], $old);
        }
      }

      foreach ($group_data as $parent_key => $v) {

        # Determine the accordion input name
        $accordion_input_name = $parent_key . '_accordion';

        # Get the order of the accordion items from the $_POST data
        $accordion_order = isset($_POST[$accordion_input_name]) ? $_POST[$accordion_input_name] : [];

        # Reorder the group data based on the accordion order
        $ordered_group_data = [];
        $sort_index = 0;
        foreach ($accordion_order as $group_key) {
          if (isset($v[$group_key])) {
            $v[$group_key]['sort_index'] = $sort_index; // Add sort_index inside each item
            $ordered_group_data[$group_key] = $v[$group_key];
            $sort_index++;
          }
        }

        # Handle regular fields
        $old_ordered_group_data = get_post_meta($post_id, $parent_key, true);
        $new_ordered_group_data = !empty($ordered_group_data) ? $ordered_group_data : '';

        # Save the ordered group data under the parent key
        if ($new_ordered_group_data != $old_ordered_group_data && false !== $new_ordered_group_data) {
          update_post_meta($post_id, $parent_key, $ordered_group_data);
        } else if ($new_ordered_group_data != $old_ordered_group_data) {
          update_post_meta($post_id, $parent_key, $ordered_group_data);
        } else if (('' === $new_ordered_group_data || false === $new_ordered_group_data) && $old_ordered_group_data) {
          delete_post_meta($post_id, $parent_key, $old_ordered_group_data);
        }
      }
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

		/**
		 * Enable SVG MIME Type in WordPress upload
     *
     * @since 3.1.0
     *
     * @see get_allowed_mime_types()
     *
		 * @return string[] Array of mime types with 'svg' included.
		 */
    public function allowSVGUpload($mimes) {
      $mimes['svg'] = 'image/svg+xml';
      return $mimes;
    }

    /**
     * Sanitize SVGs for JavaScript and XML-based exploits before the file is uploaded.
     *
     * @since 3.1.0
     *
     * @see _wp_handle_upload()
     *
     * @param array $file {
     *     Reference to a single element from `$_FILES`.
     *
     *     @type string $name     The original name of the file on the client machine.
     *     @type string $type     The mime type of the file, if the browser provided this information.
     *     @type string $tmp_name The temporary filename of the file in which the uploaded file was stored on the server.
     *     @type int    $size     The size, in bytes, of the uploaded file.
     *     @type int    $error    The error code associated with this file upload.
     * }
     * @return array $file The sanitized file array.
     */
    public function sanitizeSVG($file) {
      // Check if the file is an SVG
      if ($file['type'] === 'image/svg+xml') {
        // Get the temporary file path
        $tmp_name = $file['tmp_name'];

        // Read the file contents
        $contents = file_get_contents($tmp_name);

        // Load the SVG content into a DOMDocument
        $dom = new \DOMDocument();
        @$dom->loadXML($contents);

        // Remove <script> elements
        $scripts = $dom->getElementsByTagName('script');
        while ($scripts->length > 0) {
          $script = $scripts->item(0);
          $script->parentNode->removeChild($script);
        }

        // Remove event handlers from all elements
        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('//@*[starts-with(name(), "on")]');
        foreach ($nodes as $node) {
          /** @var DOMElement $parentNode */
          $parentNode = $node->parentNode;
          $parentNode->removeAttribute($node->nodeName);
        }

        // Whitelist allowed elements and attributes (customize as needed)
        $allowed_elements = ['svg', 'defs', 'style', 'title', 'g', 'rect', 'path', 'circle', 'line', 'text'];
        $allowed_attributes = ['id', 'class', 'x', 'y', 'width', 'height', 'fill', 'stroke', 'd', 'transform', 'viewBox', 'xmlns', 'data-name'];

        // Remove disallowed elements
        foreach ($dom->getElementsByTagName('*') as $element) {
          if (!in_array($element->nodeName, $allowed_elements)) {
            $element->parentNode->removeChild($element);
          } else {
            // Remove disallowed attributes
            foreach ($element->attributes as $attribute) {
              if (!in_array($attribute->nodeName, $allowed_attributes)) {
                $element->removeAttribute($attribute->nodeName);
              }
            }
          }
        }

        // Save the sanitized SVG back to the file
        $sanitized_contents = $dom->saveXML();
        file_put_contents($tmp_name, $sanitized_contents);
      }

      return $file;
    }

	} # end class
}