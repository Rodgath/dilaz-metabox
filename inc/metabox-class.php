<?php
/*
|| --------------------------------------------------------------------------------------------
|| Custom Metabox Class
|| --------------------------------------------------------------------------------------------
||
|| @package		Dilaz Metaboxes
|| @subpackage	Metabox Class
|| @since		Dilaz Metaboxes 1.0
|| @author		WebDilaz Team, http://webdilaz.com
|| @copyright	Copyright (C) 2017, WebDilaz LTD
|| @link		http://webdilaz.com/metaboxes
|| @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
|| 
*/

defined('ABSPATH') || exit;


$dilaz_meta_boxes = array();
$dilaz_meta_boxes = apply_filters('dilaz_meta_boxes_filter', $dilaz_meta_boxes);

$dilaz_box = new Dilaz_Meta_Box($dilaz_meta_boxes);

class Dilaz_Meta_Box {
	
	# Holds meta box object
	protected $_meta_box;
	
	# PHP Contructor method
	function __construct($meta_box) {
		
		# Exit, if we are not in Admin area
		// if ( !is_admin() ) 
		// return;
		
		# Assign meta box values to local variables
		$this->_meta_box = $meta_box;
		
		add_action('admin_init', array( &$this, 'admin_init' ));	
		add_action('add_meta_boxes', array( &$this, 'add_meta_box' )); # Add metaboxes
		add_action('save_post', array( &$this, 'save_meta_box' )); # Save post meta
		add_action('admin_enqueue_scripts', array( &$this, 'load_scripts_and_styles' )); # Enqueue common styles and scripts
	}
	
	# Initialize Metaboxes
	# =============================================================================================
	function admin_init() {
		require_once DILAZ_MB_DIR .'inc/fields.php';
	}
	
	# Load Scripts and Styles
	# =============================================================================================
	function load_scripts_and_styles($hook) {
		
		# only enqueue our scripts/styles specific pages
		if ( $hook == 'post.php' || $hook == 'post-new.php' || $hook == 'page-new.php' || $hook == 'page.php' || $hook == 'edit.php' ) {
			
			do_action('dilaz_mb_before_scripts_enqueue', $this->_meta_box);
		
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
			if ($this->has_field('stepper')) {
				wp_enqueue_script('dilaz-mb-stepper', DILAZ_MB_URL .'assets/js/stepper.min.js', array('jquery'), '', true);
				wp_enqueue_script('dilaz-mb-stepperscript', DILAZ_MB_URL .'assets/js/stepper-script.js', array('dilaz-mb-select2'), '', true);
			}
			
			# select 2 scripts
			if ($this->has_field(array('select', 'queryselect', 'timezone'))) {
				if ($this->has_field_arg('select2', 'select2single') || $this->has_field_arg('select2', 'select2multiple')) {
					wp_enqueue_style('dilaz-mb-select2', DILAZ_MB_URL .'assets/css/select2.min.css', false, '4.0.3', false);
					wp_enqueue_script('dilaz-mb-select2', DILAZ_MB_URL .'assets/js/select2.min.js', array('jquery'), '4.0.3', true);
					wp_enqueue_script('dilaz-mb-select2-sortable', DILAZ_MB_URL .'assets/js/select2.sortable.js', array('dilaz-mb-select2'), '', true);
					wp_enqueue_script('dilaz-mb-select2script', DILAZ_MB_URL .'assets/js/select2-script.js', array('dilaz-mb-select2'), '', true);
				}
			}
			
			# color picker
			if ($this->has_field('color')) {
				wp_enqueue_style('wp-color-picker');
				wp_enqueue_script('wp-color-picker');
				wp_enqueue_script('dilaz-mb-coloe-script', DILAZ_MB_URL .'assets/js/color-script.js', array('wp-color-picker'), '', true);
			}
			
			# datepicker scripts
			if ($this->has_field(array('date', 'date_from_to', 'month', 'month_from_to', 'time', 'time_from_to', 'datetime', 'date_time_from_to'))) {
				wp_enqueue_script('jquery-ui-datepicker');
				wp_enqueue_style('jquery-ui-datepicker');
			}
			
			# date scripts
			if ($this->has_field(array('date', 'date_from_to'))) {
				wp_enqueue_script('dilaz-mb-date-script', DILAZ_MB_URL .'assets/js/date-script.js', array('jquery-ui-datepicker'), '', true);
			}
			
			# monthpicker scripts
			if ($this->has_field(array('month', 'month_from_to'))) {
				wp_enqueue_script('dilaz-mb-monthpicker', DILAZ_MB_URL .'assets/js/jquery-ui-monthpicker.min.js', array('jquery-ui-datepicker'), '', true);
				wp_enqueue_script('dilaz-mb-date-script', DILAZ_MB_URL .'assets/js/date-script.js', array('dilaz-mb-monthpicker'), '', true);
			}
			
			# datepicker & timepicker scripts
			if ($this->has_field(array('time', 'time_from_to', 'datetime', 'date_time_from_to'))) {
				wp_enqueue_style('jquery-ui-datepicker');
				wp_enqueue_script('dilaz-mb-timepicker', DILAZ_MB_URL .'assets/js/jquery-ui-timepicker.min.js', array('jquery-ui-datepicker', 'jquery-ui-slider'), '', true);
				wp_enqueue_script('dilaz-mb-date-script', DILAZ_MB_URL .'assets/js/date-script.js', array('dilaz-mb-timepicker'), '', true);
			}
			
			# doWhen script
			wp_enqueue_script('dilaz-dowhen-script', DILAZ_MB_URL .'assets/js/jquery.dowhen.js');
			
			# metabox scripts
			wp_enqueue_script('dilaz-mb-script', DILAZ_MB_URL .'assets/js/metabox.js', array('jquery-ui-slider'), '', true);
			
			# translation
	 		wp_localize_script('dilaz-mb-script', 'dilaz_mb_lang', apply_filters('dilaz_mb_localized_data', array(
				'dilaz_mb_images' => DILAZ_MB_IMAGES,
				'dilaz_mb_prefix' => DILAZ_MB_PREFIX
			)));
			
			# Webfont styles
			wp_enqueue_style('fontawesome', DILAZ_MB_URL .'assets/css/font-awesome.min.css', false, '4.5.0');
			
			do_action('dilaz_mb_before_main_style_enqueue', $this->_meta_box);
			
			# metabox styles
			wp_enqueue_style('dilaz-metabox-style', DILAZ_MB_URL .'assets/css/metabox.css', array('thickbox'));
			
			do_action('dilaz_mb_after_scripts_enqueue', $this->_meta_box);
		}
	}
	
	# metabox sets and metabox tabs array
	# =============================================================================================
	function meta_box_groups() {
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
	
	# All metabox content
	# =============================================================================================
	function meta_box_content() {
		
		$parent = 0;
		$tab = 0;
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
	function meta_box_menu() {
		
		$parent = 0;
		$menu_items = array();
		$meta_box_groups = $this->meta_box_groups();
		
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
	function meta_box_menu_output($meta_box_id) {
		
		$menu_items = $this->meta_box_menu();
		
		$menu = '';
		
		if (!empty($menu_items) && sizeof($menu_items) > 0) {		
			foreach ($menu_items as $key => $val) {				
				if ($meta_box_id == $val['id']) {
					
					if (isset($val['children']) && sizeof($val['children']) > 0) {
						$menu .= '<ul class="dilaz-mb-tabs-nav">';
						foreach ($val['children'] as $child) {
							
							if (isset($child['icon']) && ($child['icon'] != '')) {
								$icon = '<i class="fa '. esc_attr($child['icon']) .'"></i>';
							} else {
								$icon = '<i class="fa fa-cog"></i>';
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
	function metabox_sets() {
		
		$meta_groups = $this->meta_box_groups();
		
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
	function meta_box_pages() {
		
		$metabox_sets = $this->metabox_sets();
		
		$pages = array();
		
		foreach ($metabox_sets as $metabox_set_id => $metabox_set) {
			$pages[] = $metabox_set['pages'];
		}
		
		return $pages;
	}
	
	# Check if metabox has fields
	# =============================================================================================
	function has_field($field_types) {
		
		$pages = $this->meta_box_pages();
		$box_content = $this->meta_box_content();
		
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
	function has_field_arg($field_arg_key, $field_arg_val) {
		
		$pages = $this->meta_box_pages();
		$box_content = $this->meta_box_content();
		
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
	function add_meta_box() {
		
		$pages = $this->meta_box_pages();
		$box_items = $this->metabox_sets();
		
		foreach ($box_items as $box_item_key => $box_item) {
			
			$box_item['context']  = empty( $box_item['context'] ) ? 'normal' : $box_item['context'];
			$box_item['priority'] = empty( $box_item['priority'] ) ? 'high' : $box_item['priority'];
			
			# Add meta box for multiple post types
			foreach ((array)$box_item['pages'] as $page_key => $page) {
				add_meta_box( $box_item['id'], $box_item['title'], array(&$this, 'show_meta_box'), $page, $box_item['context'], $box_item['priority'], array($box_item['id']) );
			}
		}
	}
	
	# Show meta boxes
	# =============================================================================================
	function show_meta_box($page, $id) {
		
		global $post, $pages;
		
		$image_path = DILAZ_MB_DIR .'images/';
		
		# Add nonce for security
		echo '<input type="hidden" name="wp_meta_box_nonce" value="'. wp_create_nonce(basename(__FILE__)) .'" />';
		echo '<div class="dilaz-metabox">';
		
			# Vertical Tabs
			echo '<div class="dilaz-mb-tabs">';
			
				# Tabs Navigation
				$meta_box_id = isset($id['id']) ? $id['id'] : '';
				if ($meta_box_id != '') {
					echo $this->meta_box_menu_output($meta_box_id);
				}
				
			echo '</div>';	
		
			# Tabs Content
			echo '<div class="dilaz-mb-tabs-content">';
			
				if ($meta_box_id != '') {
					
					$meta_box_content = $this->meta_box_content();
					$counter = 0;
					
					foreach ($meta_box_content[$meta_box_id]['fields'] as $key => $field) {
						
						$counter++;
						
						if (isset($field['type']) && $field['type'] == 'metabox_set') continue;
						
						# Set up blank or default values for empty fields
						if ( !isset( $field['id'] ) ) $field['id'] = '';
						if ( !isset( $field['type'] ) ) $field['type'] = '';
						if ( !isset( $field['name'] ) ) $field['name'] = '';
						if ( !isset( $field['std'] ) ) $field['std'] = '';
						if ( !isset( $field['args'] ) ) $field['args'] = '';
						if ( !isset( $field['state'] ) ) $field['state'] = '';
						if ( !isset( $field['class'] ) ) $field['class'] = '';
						if ( !isset( $field['req_id'] ) ) $field['req_id'] = '';
						if ( !isset( $field['req_value'] ) ) $field['req_value'] = '';
						if ( !isset( $field['req_args'] ) ) $field['req_args'] = '';
						if ( !isset( $field['req_cond'] ) ) $field['req_cond'] = '';
						if ( !isset( $field['req_action'] ) ) $field['req_action'] = '';
						if ( !isset( $field['hide_key'] ) ) $field['hide_key'] = '';
						if ( !isset( $field['hide_val'] ) ) $field['hide_val'] = '';
						
						# Desc setup
						$field['desc']   = isset($field['desc']) ? '<span class="description">'. esc_html($field['desc']) .'</span>' : '';
						$field['desc2']  = isset($field['desc2']) ? '<span class="desc2">'. esc_html($field['desc2']) .'</span>' : '';
						$field['prefix'] = isset($field['prefix']) ? '<span class="prefix">'. esc_html($field['prefix']) .'</span>' : '';
						$field['suffix'] = isset($field['suffix']) ? '<span class="suffix">'. esc_html($field['suffix']) .'</span>' : '';
						
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
							
							if ((!get_post_meta($post->ID, DILAZ_MB_PREFIX .'event_txn_event_id', true) || !get_post_meta($post->ID, DILAZ_MB_PREFIX .'event_txn_event_id', true)) && $field['hide_key'] == 'event_txn' && $field['hide_val'] == 1) {
								$hide = 'data-dilaz-hide="hidden"';
							}
							
							if ((!get_post_meta($post->ID, DILAZ_MB_PREFIX .'event_txn_pkg_id', true) || !get_post_meta($post->ID, DILAZ_MB_PREFIX .'event_txn_pkg_id', true)) && $field['hide_key'] == 'pkg_txn' && $field['hide_val'] == 1) {
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
							case 'text'              : dilaz_mb_field_text($field); break;
							case 'password'          : dilaz_mb_field_password($field); break;
							case 'hidden'            : dilaz_mb_field_hidden($field); break;
							case 'paragraph'         : dilaz_mb_field_paragraph($field); break;
							case 'url'               : dilaz_mb_field_url($field); break;
							case 'email'             : dilaz_mb_field_email($field); break;
							case 'number'            : dilaz_mb_field_number($field); break;
							case 'stepper'           : dilaz_mb_field_stepper($field); break;
							case 'code'              : dilaz_mb_field_code($field); break;
							case 'textarea'          : dilaz_mb_field_textarea($field); break;
							case 'editor'            : dilaz_mb_field_editor($field); break;
							case 'radio'             : dilaz_mb_field_radio($field); break;
							case 'checkbox'          : dilaz_mb_field_checkbox($field); break;
							case 'multicheck'        : dilaz_mb_field_multicheck($field); break;
							case 'select'            : dilaz_mb_field_select($field); break;
							case 'multiselect'       : dilaz_mb_field_multiselect($field); break;
							case 'queryselect'       : dilaz_mb_field_queryselect($field); break;
							case 'timezone'          : dilaz_mb_field_timezone($field); break;
							case 'radioimage'        : dilaz_mb_field_radioimage($field); break;
							case 'color'             : dilaz_mb_field_color($field); break;
							case 'multicolor'        : dilaz_mb_field_multicolor($field); break;
							case 'date'              : dilaz_mb_field_date($field); break;
							case 'date_from_to'      : dilaz_mb_field_date_from_to($field); break;
							case 'month'             : dilaz_mb_field_month($field); break;
							case 'month_from_to'     : dilaz_mb_field_month_from_to($field); break;
							case 'time'              : dilaz_mb_field_time($field); break;
							case 'time_from_to'      : dilaz_mb_field_time_from_to($field); break;
							case 'date_time'         : dilaz_mb_field_date_time($field); break;
							case 'date_time_from_to' : dilaz_mb_field_date_time_from_to($field); break;
							case 'slider'            : dilaz_mb_field_slider($field); break;
							case 'range'             : dilaz_mb_field_range($field); break;
							case 'upload'            : dilaz_mb_field_upload($field); break;
							case 'buttonset'         : dilaz_mb_field_buttonset($field); break;
							case 'switch'            : dilaz_mb_field_switch($field); break;
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
	
	# Sanitize meta field values
	# =============================================================================================
	function sanitize_meta($type, $input, $field = '') {
		
		switch ($type) {
		
			case 'text':
			case 'hidden':
			case 'switch':
				return sanitize_text_field($input);
				break;
				
			case 'password':
				return wp_hash_password(sanitize_text_field($input));
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
				$output = '';
				foreach ((array)$input as $k => $v) {
					if (isset($field['options'][$v])) {
						$output[] = $v;
					}
				}
				return $output;
				break;
				
			case 'queryselect':
			case 'range':
				$output = '';
				foreach ((array)$input as $k => $v) {
					$output[$k] = absint($v);
				}
				return $output;
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
				$output = '';
				foreach ((array)$input as $k => $v) {
					if (isset($field['options'][$k]) && $v == true) {
						$output[$k] = true;
					} else {
						$output[$k] = false;
					}
				}
				return $output;
				break;
				
			case 'color':
				return sanitize_hex_color($input);
				break;
				
			case 'multicolor':
				$output = '';
				foreach ((array)$input as $k => $v) {
					if (isset($field['options'][$k])) {
						$output[$k] = sanitize_hex_color($v);
					}
				}
				return $output;
				break;
				
			case 'font':
				$output = array();
				foreach ((array)$input as $k => $v) {
					if (isset($field['options'][$k]) && $k == 'color') {
						$output[$k] = sanitize_hex_color($v);
					} else {
						$output[$k] = $v;
					}
				}
				return $output;
				break;
				
			case 'background':
				$output = array();
				foreach ((array)$input as $k => $v) {
					if (isset($field['options'][$k]) && $k == 'image') {
						$output[$k] = absint($v);
					} else if (isset($field['options'][$k]) && $k == 'color') {
						$output[$k] = sanitize_hex_color($v);
					} else if (isset($field['options'][$k]) && ($k == 'repeat' || $k == 'size' || $k == 'position' || $k == 'attachment' || $k == 'origin')) {
						$output[$k] = is_array($v) ? array_map('sanitize_text_field', $v) : sanitize_text_field($v);
					}
				}
				return $output;
				break;
				
			case 'upload':
				$output = '';
				foreach ((array)$input as $k => $v) {
					$output[] = absint($v);
				}
				return is_array($output) ? array_unique($output) : $output;
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
				return $output;
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
	
	# Delete removed meta options from DB
	# =============================================================================================
	function delete_removed_meta($post_id) {
		
		global $wpdb;
		
		# filter all meta keys that have the unique meta prefix identifier
		$where = $wpdb->prepare("pm.meta_key LIKE '%s' AND pm.post_id = '%s'", '%'. DILAZ_MB_PREFIX .'%', $post_id);
		
		# return all filtered meta data
		$saved_meta = $wpdb->get_results("SELECT * FROM {$wpdb->postmeta} pm WHERE {$where}", ARRAY_A);
		
		# build all meta keys into an array
		$saved_meta_array = array();
		if (!empty($saved_meta)) {
			foreach ($saved_meta as $key => $meta) {
				$saved_meta_array[] = $meta['meta_key'];
			}
		}
		
		# get all meta box option fields
		$meta_box_content = $this->meta_box_content();
		
		# build all meta keys into an array
		$field_meta_array = array();
		if (!empty($meta_box_content)) {
			foreach ($meta_box_content as $key => $metabox_set) {
				foreach ($metabox_set['fields'] as $field_key => $field) {
					$field_meta_array[] = $field['id'];
				}
			}
		}
		
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
	function save_meta_box($post_id) {
		
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
		$meta_box_content = $this->meta_box_content();
		if (!empty($meta_box_content)) {
			foreach ($meta_box_content as $key => $metabox_set) {
				foreach ($metabox_set['fields'] as $field_key => $field) {
					
					$old = get_post_meta($post_id, $field['id'], true);
					$new = isset( $_POST[$field['id']] ) ? $_POST[$field['id']] : null;
					
					# sanitized option
					$sanitized_meta = $this->sanitize_meta($field['type'], $new, $field);
					
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
		
		$this->delete_removed_meta($post_id);
		
		# after save action hook
		do_action('dilaz_mb_after_save_post', $post_id);
	}
	
} # end class