<?php
/*
|| --------------------------------------------------------------------------------------------
|| Dilaz Metabox Fields
|| --------------------------------------------------------------------------------------------
||
|| @package    Dilaz Metabox
|| @subpackage Fields
|| @since      Dilaz Metabox 1.0
|| @author     Rodgath, https://github.com/Rodgath
|| @copyright  Copyright (C) 2017, Rodgath LTD
|| @link       https://github.com/Rodgath/Dilaz-Metabox
|| @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
||
*/

namespace DilazMetabox\DilazMetaboxFields;

defined('ABSPATH') || exit;

use DilazMetabox\DilazMetaboxDefaults;

/**
 * Fields class
 */
if (!class_exists('DilazMetaboxFields')) {
	class DilazMetaboxFields {

		/**
		 * Text
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldText($field) {

			extract($field);

			$size  = isset($args['size']) ? intval($args['size']) : '30';
			$class = isset($class) ? sanitize_html_class($class) : '';

			$output = $prefix .'<input type="text" name="'. esc_attr($id) .'" id="'. esc_attr($id) .'" class="dilaz-mb-input-style '. esc_attr($class) .'" value="'. $meta .'" size="'. esc_attr($size) .'"  />'. $suffix .''. $desc2 .'';

			echo $output;
		}

		/**
		 * Multiple Text Input
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldMultiText($field) {

			extract($field);

			$class = isset($class) ? sanitize_html_class($class) : '';

			$output = '';

			if (isset($options)) {
				foreach ($options as $key => $value) {

					$text_name    = isset($value['name']) ? $value['name'] : '';
					$default_text = isset($value['default']) ? $value['default'] : '';
					$saved_text   = isset($meta[$key]) ? $meta[$key] : $default_text;
					$inline       = isset($args['inline']) && $args['inline'] == true ? 'inline' : '';

					if ($inline == '') {
						$cols = 'style="width:100%;display:block"'; # set width to 100% if fields are not inline
					} else {
						$cols = isset($args['cols']) ? 'style="width:'. (100/intval($args['cols'])) .'%"' : 'style="width:30%"';
					}

					$output .= '<div class="dilaz-mb-multi-text '. $inline .'" '. $cols .'>';
						$output .= '<div class="dilaz-mb-multi-text-wrap">';
							$output .= '<strong>'. $text_name .'</strong><br />';
							$output .= '<input class="dilaz-mb-text dilaz-mb-input-style '. $class .'" type="text" name="'. esc_attr($id) .'['. esc_attr($key) .']" id="'. esc_attr($id) .'" value="'. $saved_text .'" />';
						$output .= '</div>';
					$output .= '</div>';
				}
				$output .= ''. $suffix .' '. $desc2 .'';
			}

			echo $output;
		}

		/**
		 * Password
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldPassword($field) {

			extract($field);

			$size  = isset($args['size']) ? intval($args['size']) : '30';
			$class = isset($class) ? sanitize_html_class($class) : '';

			$output = $prefix .'<input type="password" name="'. esc_attr($id) .'" id="'. esc_attr($id) .'" class="dilaz-mb-input-style '. esc_attr($class) .'" value="'. $meta .'" size="'. esc_attr($size) .'"  />'. $suffix .''. $desc2 .'';

			echo $output;
		}

		/**
		 * Hidden
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldHidden($field) {

			extract($field);

			$size  = isset($args['size']) ? intval($args['size']) : '30';
			$class = isset($class) ? sanitize_html_class($class) : '';
			$value = $value != '' && $value != $meta ? $value : $meta;

			$output = $prefix .'<input type="hidden" name="'. esc_attr($id) .'" id="'. esc_attr($id) .'" class="'. esc_attr($class) .'" value="'. $value .'" size="'. esc_attr($size) .'"  />';

			echo $output;
		}

		/**
		 * Paragraph
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldParagraph($field) {

			extract($field);

			$size  = isset($args['size']) ? $args['size'] : '30';
			$value = $value != '' && $value != $meta ? $value : $meta;
			$class = isset($class) ? sanitize_html_class($class) : '';

			$output = '<div class="dilaz-mb-paragraph '. $class .'">'. wpautop($value) .'</div>'.$desc2;

			echo $output;
		}

		/**
		 * Code Output
		 *
		 * @since  2.3.1
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldCodeOutput($field) {

			extract($field);

			$size  = isset($args['size']) ? $args['size'] : '30';
			$value = $value != '' && $value != $meta ? $value : $meta;
			$class = isset($class) ? sanitize_html_class($class) : '';

			$output = '<p class="dilaz-mb-codeoutput '. $class .'">'. htmlspecialchars($value) .'</p>'.$desc2;

			echo $output;
		}

		/**
		 * URL
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldUrl($field) {

			extract($field);

			$size  = isset($args['size']) ? intval($args['size']) : '30';
			$class = isset($class) ? sanitize_html_class($class) : '';

			$output = $prefix .'<input type="text" name="'. esc_attr($id) .'" id="'. esc_attr($id) .'" class="dilaz-mb-input-style '. esc_attr($class) .'" value="'. esc_url($meta). '" size="'. esc_attr($size) .'"  />'. $suffix .''. $desc2 .'';

			echo $output;
		}

		/**
		 * Email
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldEmail($field) {

			extract($field);

			$size  = isset($args['size']) ? intval($args['size']) : '30';
			$class = isset($class) ? sanitize_html_class($class) : '';

			$output = $prefix .'<input type="email" name="'. esc_attr($id) .'" id="'. esc_attr($id) .'" class="dilaz-mb-input-style '. esc_attr($class) .'" value="'. esc_attr($meta). '" size="'. esc_attr($size) .'"  />'. $suffix .''. $desc2 .'';

			echo $output;
		}

		/**
		 * Number
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldNumber($field) {

			extract($field);

			$size  = isset($args['size']) ? intval($args['size']) : '5';
			$class = isset($class) ? sanitize_html_class($class) : '';

			$output = $prefix .'<input type="text" name="'. esc_attr($id) .'" id="'. esc_attr($id) .'" class="dilaz-mb-input-style '. esc_attr($class) .'" value="'. $meta .'" size="'. esc_attr($size) .'" />'. $suffix .''. $desc2 .'';

			echo $output;
		}

		/**
		 * Repeatable
		 *
		 * @since  2.3
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldRepeatable($field) {

			extract($field);

			$sortable = isset($args['sortable']) ? wp_validate_boolean($args['sortable']) : true;
			$sorter = $sortable ? '<span class="sort-repeatable"><i class="dashicons dashicons-move"></i></span>' : '';
			$not_sortable = isset($args['not_sortable']) ? intval($args['not_sortable']) : 0;
			$removable = isset($args['removable']) ? wp_validate_boolean($args['removable']) : true;
			$remover = $removable ? '<span class="repeatable-remove button"><i class="dashicons dashicons-no-alt"></i></span>' : '';
			$not_removable = isset($args['not_removable']) ? intval($args['not_removable']) : 0;
			$add_more = isset($args['add_more']) ? wp_validate_boolean($args['add_more']) : true;
			$add_text = isset($args['add_text']) ? sanitize_text_field($args['add_text']) : __('Add New', 'dilaz-metabox');
			$class = isset($class) ? sanitize_html_class($class) : '';
			$inline = isset($args['inline']) && $args['inline'] == true ? 'inline' : '';

			$output = '';
			$output .= '<ul id="'. esc_attr($id) .'" class="dilaz-mb-repeatable '.$class.'" data-ns="'.$not_sortable.'" data-s="'.$sortable.'" data-nr="'.$not_removable.'" data-r="'.$removable.'">';
				$i = 0;
				if ($meta != '' && is_array($meta)) {
					foreach($meta as $key => $value) {
						$output .= '<li class="dilaz-mb-repeatable-item">'.($not_sortable > $i ? '' : $sorter);
							if (is_array($value)) {
								foreach($value as $k => $v) {
									$label = isset($options[0][$k]['label']) ? $options[0][$k]['label'] : '';
									$field_size = isset($options[0][$k]['size']) ? intval($options[0][$k]['size']) : 30;
									$output .= '<div class="dilaz-mb-repeatable-item-wrap inline">';
									if ($label != '') {
										$output .= '<label for="'.esc_attr($id).'"><strong>'.$label.'</strong></label>';
									}
									$output .= '<input type="text" class="dilaz-mb-input-style '.$k.$i.'" name="'.esc_attr($id).'['.$i.'][]" value="'.$v.'" size="'.$field_size.'" />
									</div>';
								}
							} else {
								$output .= '<input type="text" class="dilaz-mb-input-style" name="'.esc_attr($id).'['.$i.']" value="'.$value.'" size="30" />';
							}
						$output .= ($not_removable > $i || $i < 1 ? '' : $remover).'</li>';
						$i++;
					}
				} else {
					foreach ((array)$options as $option_key => $option_value) {
						$output .= '<li class="dilaz-mb-repeatable-item">'.($not_sortable > $i ? '' : $sorter);
							if (is_array($option_value)) {
								foreach($option_value as $k => $v) {
									$label = isset($v['label']) ? $v['label'] : '';
									$field_size = isset($options[0][$k]['size']) ? intval($options[0][$k]['size']) : 30;
									$output .= '<div class="dilaz-mb-repeatable-item-wrap inline">';
									if ($label != '') {
										$output .= '<label for="'.esc_attr($id).'"><strong>'.$v['label'].'</strong></label>';
									}
									$output .= '<input type="text" class="'.$k.$i.'" name="'.esc_attr($id).'['.$i.'][]" value="'.$v['value'].'" size="'.$field_size.'" />
									</div>';
								}
							} else {
								$output .= '<input type="text" name="'.esc_attr($id).'['.$i.']" value="'.$option_value.'" size="30" />';
							}
						$output .= ($not_removable > $i || $i < 1 ? '' : $remover).'</li>';
						$i++;
					}
				}
			$output .= '</ul>';
			if ($add_more) {
				$output .= '<span class="dilaz-mb-add-repeatable-item button">'.$add_text.'</span>';
			}

			echo $output;
		}

		/**
		 * Stepper
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldStepper($field) {

			extract($field);

			$size       = isset($args['size']) ? intval($args['size']) : '3';
			$class      = isset($class) ? sanitize_html_class($class) : '';
			$wheel_step = isset($args['wheel_step']) ? 'data-wheel-step="'. intval($args['wheel_step']) .'"' : '';
			$arrow_step = isset($args['arrow_step']) ? 'data-arrow-step="'. intval($args['arrow_step']) .'"' : '';
			$step_limit = isset($args['step_limit']) ? 'data-limit="['. $args['step_limit'] .']"' : '';

			$output = $prefix .'<input type="text" name="'. esc_attr($id) .'" id="'. esc_attr($id) .'" value="'. $meta .'" size="'. esc_attr($size) .'" class="dilaz-stepper dilaz-mb-input-style '. $class .'" '. $wheel_step .' '. $arrow_step .' '. $step_limit .'  />'. $suffix .''. $desc2 .'';

			echo $output;
		}

		/**
		 * Code
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldCode($field) {

			extract($field);

			$class = isset($class) ? sanitize_html_class($class) : '';
			$cols  = isset($args['cols']) ? intval($args['cols']) : '50';
			$rows  = isset($args['rows']) ? intval($args['rows']) : '5';

			$output = $prefix .'<textarea name="'. esc_attr($id) .'" id="'. esc_attr($id) .'" class="dilaz-mb-input-style '. esc_attr($class) .'" cols="'. esc_attr($cols) .'" rows="'. esc_attr($rows) .'">'. esc_textarea($meta) .'</textarea>'. $suffix .''. $desc2 .'';

			echo $output;
		}

		/**
		 * Textarea
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldTextarea($field) {

			extract($field);

			$class = isset($class) ? sanitize_html_class($class) : '';
			$cols  = isset($args['cols']) ? intval($args['cols']) : '50';
			$rows  = isset($args['rows']) ? intval($args['rows']) : '5';

			$output = $prefix .'<textarea name="'. esc_attr($id) .'" id="'. esc_attr($id) .'" class="dilaz-mb-input-style '. esc_attr($class) .'" cols="'. esc_attr($cols) .'" rows="'. esc_attr($rows) .'">'. esc_textarea($meta) .'</textarea>'. $suffix .''. $desc2 .'';

			echo $output;
		}

		/**
		 * Editor
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldEditor($field) {

			extract($field);

			$rows  = isset($args['rows']) ? intval($args['rows']) : '10';
			$class = isset($class) ? sanitize_html_class($class) : '';

			$settings = array(
				'textarea_name' => esc_attr($id),
				'textarea_rows' => $rows,
				'editor_class'  => $class,
				'tinymce'       => array('plugins' => 'wordpress')
			);

			$output = wp_editor($meta, $id, $settings);

			echo $output;
		}

		/**
		 * Radio
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldRadio($field) {

			extract($field);

			$output = '';

			$class  = isset($class) ? sanitize_html_class($class) : '';
			$inline = isset($args['inline']) && $args['inline'] == true ? 'inline' : '';

			if ($inline == '') {
				$cols = 'style="width:100%;display:block"'; # set width to 100% if fields are not inline
			} else {
				$cols = isset($args['cols']) ? 'style="width:'. ceil(100/intval($args['cols'])) .'%"' : 'style="width:30%"';
			}

			foreach ( (array)$options as $key => $val ) {
				$checked = checked($meta, $key, false);
				$state = $checked ? 'focus' : '';
				$output .= '<label for="'. esc_attr($id .'-'. $key) .'" class="dilaz-mb-option '. $inline .' '. $class .'" '. $cols .'><input type="radio" name="'. esc_attr($id) .'" id="'. esc_attr($id .'-'. $key) .'" class="dilaz-mb-input dilaz-mb-radio '. $state .'" value="'. $key .'" '. $checked .'  /><span class="radio"></span><span>'. $val .'</span></label>';
			}

			$output .= ''. $suffix .''. $desc2 .'';

			echo $output;
		}

		/**
		 * Checkbox
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldCheckbox($field) {

			extract($field);

			$class = isset($class) ? sanitize_html_class($class) : '';

			$state = checked($meta, true, false) ? 'focus' : '';
			$output  = $prefix .'<label for="'. esc_attr($id) .'" class="dilaz-mb-option '. $class .'"><input type="checkbox" name="'. esc_attr($id) .'" id="'. esc_attr($id) .'" class="dilaz-mb-checkbox '. $class .' '. $state .'" '. checked($meta, true, false) .' /><span class="checkbox"></span>'. $suffix .'</label>'. $desc2 .'';

			echo $output;
		}

		/**
		 * Multicheckbox
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldMultiCheck($field) {

			extract($field);

			$class  = isset($class) ? sanitize_html_class($class) : '';
			$std    = isset($std) && is_array($std) ? array_map('sanitize_text_field', $std) : array();
			$inline = isset($args['inline']) && $args['inline'] == true ? 'inline' : '';

			if ($inline == '') {
				$cols = 'style="width:100%;display:block"'; # set width to 100% if fields are not inline
			} else {
				$cols = isset($args['cols']) ? 'style="width:'. ceil(100/intval($args['cols'])) .'%"' : 'style="width:30%"';
			}

			$output = '';

			foreach ((array)$options as $option_value => $options_name) {

				$option_value = sanitize_key($option_value);

				$checked = isset($meta[$option_value]) ? checked($meta[$option_value], true, false) : '';

				$state = $checked ? 'focus' : '';
				$output .= '<label for="'. esc_attr($id .'-'. $option_value) .'" class="dilaz-mb-option '. $inline .' '. $class .'" '. $cols .'><input type="checkbox" value="'. $option_value .'" name="'. esc_attr($id .'['. $option_value .']') .'" id="'. esc_attr($id .'-'. $option_value) .'" class="dilaz-mb-input dilaz-mb-checkbox '. $state .'" '. $checked .'  /><span class="checkbox"></span><span>'. $options_name .'</span></label>';
			}

			echo $output;
		}

		/**
		 * Select
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldSelect($field) {

			extract($field);

			$output = '';

			$class         = isset($class) ? sanitize_html_class($class) : '';
			$select2_class = isset($args['select2']) ? sanitize_html_class($args['select2']) : '';
			$select2_width = isset($args['select2width']) ? 'data-width="'. sanitize_text_field($args['select2width']) .'"' : 'data-width="100px"';

			$output .= '<select id="'. esc_attr($id) .'" class="dilaz-mb-select dilaz-mb-input-style '. $select2_class .' '. $class .'" name="'. esc_attr($id) .'" '. $select2_width .'>';
			foreach ((array)$options as $key => $val) {
				// $selected = $meta == $key ? ' selected="selected"' : '';
				$selected = selected($meta == $key, true, false);
				$output .= '<option '. $selected .' value="'. $key .'">'. $val .'</option>';
			}
			$output .= '</select>'. $suffix .''. $desc2 .'';

			echo $output;
		}

		/**
		 * Multielect
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldMultiSelect($field) {

			extract($field);

			$output = '';

			$class         = isset($class) ? sanitize_html_class($class) : '';
			$select2_class = isset($args['select2']) ? sanitize_html_class($args['select2']) : '';
			$select2_width = isset($args['select2width']) ? 'data-width="'. sanitize_text_field($args['select2width']) .'"' : 'data-width="100px"';

			$output .= '<select id="'. esc_attr($id) .'" class="dilaz-mb-select dilaz-mb-input-style '. $select2_class .' '. $class .'" multiple="multiple" name="'. esc_attr($id) .'[]" '. $select2_width .'>';
				$selected_data = is_array($meta) ? $meta : array();
				foreach ($options as $key => $option) {
					// $selected = (in_array($key, $selected_data)) ? 'selected="selected"' : '';
					$selected = selected(in_array($key, $selected_data), true, false);
					$output .= '<option '. $selected .' value="'. esc_attr($key) .'">'. esc_html($option) .'</option>';
				}
			$output .= '</select>';

			echo $output;
		}

		/**
		 * Query select - 'post', 'term', 'user'
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldQuerySelect($field) {

			extract($field);

			$output = '';

			$query_type    = isset($args['query_type']) ? sanitize_text_field($args['query_type']) : '';
			$query_args    = isset($args['query_args']) ? (array)$args['query_args'] : array();
			$placeholder   = isset($args['placeholder']) ? sanitize_text_field($args['placeholder']) : __('Select a post', 'dilaz-metabox');
			$min_input     = isset($args['min_input']) ? intval($args['min_input']) : 3;
			$max_input     = isset($args['max_input']) ? intval($args['max_input']) : 0;
			$max_options   = isset($args['max_options']) ? intval($args['max_options']) : 0;
			$select2_width = isset($args['select2width']) ? sanitize_text_field($args['select2width']) : '100px';
			$select2       = isset($args['select2']) ? $args['select2'] : '';
			$multiple_attr = $select2 == 'select2multiple' ? 'multiple="multiple"' : '';
			$multiple_bool = $select2 == 'select2multiple' ? 'true' : 'false';
			$class         = isset($class) ? sanitize_html_class($class) : '';

			// if (wp_script_is('select2script', 'enqueued')) {
				// wp_localize_script('select2script', 'dilaz_mb_post_select_lang', array(
					// 'dilaz_mb_pref' => $query_args,
				// ));
			// }

			$output .= '<select style="" name="'. esc_attr($id) .'[]" id="'. esc_attr($id) .'" '. $multiple_attr .' class="dilaz-mb-query-select '. $class .'"
			data-placeholder="'. esc_attr($placeholder) .'"
			data-min-input="'. esc_attr($min_input) .'"
			data-max-input="'. esc_attr($max_input) .'"
			data-max-options="'. esc_attr($max_options) .'"
			data-query-args="'. esc_attr(base64_encode(serialize($query_args))) .'"
			data-query-type="'. esc_attr($query_type) .'"
			data-multiple="'. esc_attr($multiple_bool) .'"
			data-width="'. esc_attr($select2_width) .'">';

			$selected_data = is_array($meta) ? $meta : array();

			foreach ($selected_data as $key => $item_id) {

				if ($query_type == 'post') {
					$name = get_post_field('post_title', $item_id);
				} else if ($query_type == 'user') {
					$user_data = get_userdata($item_id);
					$name = ($user_data && !is_wp_error($user_data)) ? $user_data->nickname : '';
				} else if ($query_type == 'term') {
					$term_data = get_term($item_id);
					$name = ($term_data && !is_wp_error($term_data)) ? $term_data->name : '';
				} else {
					$name = 'Add query type';
				}

				$output .= '<option selected="selected" value="'. esc_attr($item_id) .'">'. $name .'</option>';
			}

			$output .= '</select>';

			echo $output;
		}

		/**
		 * Timezone
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldTimezone($field) {

			extract($field);

			$output = '';

			$class         = isset($class) ? sanitize_html_class($class) : '';
			$select2_class = isset($args['select2']) ? $args['select2'] : '';
			$select2_width = isset($args['select2width']) ? 'data-width="'. sanitize_text_field($args['select2width']) .'"' : 'data-width="100px"';

			$output .= '<select name="'. esc_attr($id) .'" id="'. esc_attr($id) .'" class="dilaz-mb-timezone dilaz-mb-input-style '. $select2_class .' '. $class .'" '. $select2_width .'>';
			$output .= '<option value="">Select timezone</option>';
			foreach ((array)$options as $t) {
				$selected = $meta == $t['zone'] ? 'selected="selected"' : '';
				$output .= '<option '. $selected .' value="'. $t['zone'] .'">'. $t['diff_from_GMT'] .' - '. $t['zone'] .'</option>';
			}
			$output .= '</select>';

			echo $output;
		}

		/**
		 * Radio image
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldRadioImage($field) {

			extract($field);

			$class = isset($class) ? sanitize_html_class($class) : '';

			$output = '';

			foreach ( (array)$options as $key => $val ) {
				$selected = $meta == $key ? ' dilaz-image-selector-img-selected' : '';
				$checked = $meta == $key ? ' checked="checked"' : '';
				$output .= '<div class="dilaz-image-select-wrapper">';
					$output .= '<input class="dilaz-image-selector '. $class .'" type="radio" name="'. esc_attr($id) .'" id="'. esc_attr($id .'_'. $key) .'" value="'. $key .'" '. $checked .' />';
					$output .= '<img src="'. esc_url($val) .'" alt="'. $key .'" class="dilaz-image-selector-img '. $selected .'" onclick="document.getElementById(\''. esc_attr($id .'_'. $key) .'\').checked=true;" />';
					$output .= '<span class="inset"></span>';
					$output .= '<span class="check"><i class="fa fa-check"></i></span>';
				$output .= '</div>';
			}

			echo $output;
		}

		/**
		 * Color
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldColor($field) {

			extract($field);

			$class = isset($class) ? sanitize_html_class($class) : '';

			$output = '';

			$default_color = isset($std) ?  $std : '';

			$output .= '<input class="dilaz-mb-color '. $class .'" type="text" name="'.  esc_attr($id) .'" id="'.  esc_attr($id) .'" value="'. $meta .'" size="8" data-default-color="'. $default_color .'" data-alpha="true" />'. $suffix .' '. $desc2 .'';

			echo $output;
		}

		/**
		 * Multiple Colors
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldMultiColor($field) {

			extract($field);

			$class = isset($class) ? sanitize_html_class($class) : '';

			$output = '';

			if (isset($options)) {
				foreach ($options as $key => $value) {

					$color_name    = isset($value['name']) ? $value['name'] : '';
					$default_color = isset($value['color']) ? $value['color'] : '';
					$saved_color   = isset($meta[$key]) ? $meta[$key] : $default_color;

					$output .= '<div class="dilaz-mb-multi-color">';
					$output .= '<strong>'. $color_name .'</strong><br />';
					$output .= '<input class="dilaz-mb-color '. $class .'" type="text" name="'.  esc_attr($id) .'['. esc_attr($key) .']" id="'.  esc_attr($id) .'" value="'. $saved_color .'" data-default-color="'. $default_color .'" data-alpha="true" />';
					$output .= '</div>';
				}
				$output .= '<br />'. $suffix .' '. $desc2 .'';
			}

			echo $output;
		}

		/**
		 * Font
		 *
		 * @since  2.5.7
		 * @access public
		 *
		 * @param  array $field Field arguments
		 * @return html  $output
		 */
		public static function fieldFont($field) {

			extract($field);

			$output = '';

			$font_defaults = DilazMetaboxDefaults\DilazMetaboxDefaults::_font();
			$saved_fonts   = wp_parse_args($meta, $font_defaults);

			$fontUnit = isset($args['unit']) ? (string)$args['unit'] : 'px';
			$std      = isset($std) && is_array($std) ? array_map('sanitize_text_field', $std) : array();

			/* font family */
			if (isset($options['family']) && $options['family'] !== FALSE) {
				$output .= '<div class="dilaz-mb-font">';
					$output .= '<strong>'. __('Font Family', 'dilaz-metabox') .'</strong><br />';
					$output .= '<select id="'. esc_attr($id) .'-family" name="'. esc_attr($id) .'[family]" class="family select2single" data-width="230px">';
					$font_families = is_array($options['family']) ? $options['family'] : DilazMetaboxDefaults\DilazMetaboxDefaults::_font_family();
					foreach ($font_families as $key => $font_family) {
						if (isset($saved_fonts['family']) && !empty($saved_fonts['family']) && $saved_fonts['family'] !== FALSE) {
							$selected_family = selected(strtolower($saved_fonts['family']), strtolower($key), FALSE);
						} else {
							$selected_family = isset($std['family']) && stripos($key, $std['family']) !== FALSE ? selected(strtolower($std['family']), strtolower($key), FALSE) : '';
						}
						$output .= '<option value="'. $key .'" '. $selected_family .'>'. $font_family .'</option>';
					}
					$output .= '</select>';
				$output .= '</div>';
			}

			/* font weight */
			if (isset($options['weight']) && $options['weight'] !== FALSE) {
				$output .= '<div class="dilaz-mb-font">';
					$output .= '<strong>'. __('Font Weight', 'dilaz-metabox') .'</strong><br />';
					$output .= '<select id="'. esc_attr($id) .'-weight" name="'. esc_attr($id) .'[weight]" class="weight select2single" data-width="130px">';
					$font_weights = is_array($options['weight']) ? $options['weight'] : DilazMetaboxDefaults\DilazMetaboxDefaults::_font_weights();
					foreach ($font_weights as $key => $font_weight) {
						if (isset($saved_fonts['weight']) && !empty($saved_fonts['weight']) && $saved_fonts['weight'] !== FALSE) {
							$selected_weight = selected(strtolower($saved_fonts['weight']), strtolower($key), FALSE);
						} else {
							$selected_weight = isset($std['weight']) && stripos($key, $std['weight']) !== FALSE ? selected(strtolower($std['weight']), strtolower($key), FALSE) : '';
						}
						$output .= '<option value="'. $key .'" '. $selected_weight .'>'. $font_weight .'</option>';
					}
					$output .= '</select>';
				$output .= '</div>';
			}

			/* font style */
			if (isset($options['style']) && $options['style'] !== FALSE) {
				$output .= '<div class="dilaz-mb-font">';
					$output .= '<strong>'. __('Font Style', 'dilaz-metabox') .'</strong><br />';
					$output .= '<select id="'. esc_attr($id) .'-style" name="'. esc_attr($id) .'[style]" class="style select2single" data-width="110px">';
					$font_styles = is_array($options['style']) ? $options['style'] : DilazMetaboxDefaults\DilazMetaboxDefaults::_font_styles();
					foreach ($font_styles as $key => $font_style) {
						if (isset($saved_fonts['style']) && !empty($saved_fonts['style']) && $saved_fonts['style'] !== FALSE) {
							$selected_style = selected(strtolower($saved_fonts['style']), strtolower($key), FALSE);
						} else {
							$selected_style = isset($std['style']) && stripos($key, $std['style']) !== FALSE ? selected(strtolower($std['style']), strtolower($key), FALSE) : '';
						}
						$output .= '<option value="'. $key .'" '. $selected_style .'>'. $font_style .'</option>';
					}
					$output .= '</select>';
				$output .= '</div>';
			}

			/* font case - text transform */
			if (isset($options['case']) && $options['case'] !== FALSE) {
				$output .= '<div class="dilaz-mb-font">';
					$output .= '<strong>'. __('Font Case', 'dilaz-metabox') .'</strong><br />';
					$output .= '<select id="'. esc_attr($id) .'-case" name="'. esc_attr($id) .'[case]" class="case select2single" data-width="110px">';
					$font_cases = is_array($options['case']) ? $options['case'] : DilazMetaboxDefaults\DilazMetaboxDefaults::_font_cases();
					foreach ($font_cases as $key => $font_case) {
						if (isset($saved_fonts['case']) && !empty($saved_fonts['case']) && $saved_fonts['case'] !== FALSE) {
							$selected_case = selected(strtolower($saved_fonts['case']), strtolower($key), FALSE);
						} else {
							$selected_case = isset($std['case']) && stripos($key, $std['case']) !== FALSE ? selected(strtolower($std['case']), strtolower($key), FALSE) : '';
						}
						$output .= '<option value="'. $key .'" '. $selected_case .'>'. $font_case .'</option>';
					}
					$output .= '</select>';
				$output .= '</div>';
			}

			/* font stack backup */
			if (isset($options['backup']) && $options['backup'] !== FALSE) {
				$output .= '<div class="dilaz-mb-font">';
					$output .= '<strong>'. __('Font Backup Stack', 'dilaz-metabox') .'</strong><br />';
					$output .= '<select id="'. esc_attr($id) .'-backup" name="'. esc_attr($id) .'[backup]" class="backup select2single" data-width="230px">';
					$font_backups = is_array($options['backup']) ? $options['backup'] : DilazMetaboxDefaults\DilazMetaboxDefaults::_font_family_defaults_stacks();
					foreach ($font_backups as $key => $font_backup) {
						if (isset($saved_fonts['backup']) && !empty($saved_fonts['backup']) && $saved_fonts['backup'] !== FALSE) {
							$selected_backup = selected($saved_fonts['backup'], $key, FALSE);
						} else {
							$selected_backup = isset($std['backup']) && stripos($key, $std['backup']) !== FALSE ? selected($std['backup'], $key, FALSE) : '';
						}
						$output .= '<option value="'. $key .'" '. $selected_backup .'>'. $font_backup .'</option>';
					}
					$output .= '</select>';
				$output .= '</div>';
			}

			/* font size */
			if (isset($options['size']) && $options['size'] !== FALSE) {
				$output .= '<div class="dilaz-mb-font">';
					$output .= '<strong>'. __('Font Size', 'dilaz-metabox') .'</strong><br />';
					$output .= '<div id="'. esc_attr($id) .'-size">';
						if (isset($saved_fonts['size']) && $saved_fonts['size'] > 0) {
							$font_size = intval($saved_fonts['size']);
						} else if (isset($std['size']) && $std['size'] > 0) {
							$font_size = intval($std['size']);
						} else if (isset($font_defaults['size']) && $font_defaults['size'] > 0) {
							$font_size = intval($font_defaults['size']);
						} else {
							$font_size = 14;
						}
						$output .= '<input type="text" class="f-size '. esc_attr($id) .'-size dilaz-mb-input-style" name="'. esc_attr($id) .'[size]" value="'. $font_size .'" size="3" />';
						$output .= '<span class="unit">'. $fontUnit .'</span>';
					$output .= '</div>';
				$output .= '</div>';
			}

			/* line height */
			if (isset($options['height']) && $options['height'] !== FALSE) {
				$output .= '<div class="dilaz-mb-font">';
					$output .= '<strong>'. __('Line Height', 'dilaz-metabox') .'</strong><br />';
					$output .= '<div id="'. esc_attr($id) .'-height">';
						if (isset($saved_fonts['height']) && $saved_fonts['height'] > 0 && $saved_fonts['height'] !== FALSE) {
							$font_height = intval($saved_fonts['height']);
						} else if (isset($std['height']) && $std['height'] > 0) {
							$font_height = intval($std['height']);
						} else if (isset($font_defaults['height']) && $font_defaults['height'] > 0) {
							$font_height = intval($font_defaults['height']);
						} else {
							$font_height = 16;
						}
						$output .= '<input type="text" class="f-height '. esc_attr($id) .'-height dilaz-mb-input-style" name="'. esc_attr($id) .'[height]" value="'. $font_height .'" size="3" />';
						$output .= '<span class="unit">'. $fontUnit .'</span>';
					$output .= '</div>';
				$output .= '</div>';
			}

			/* font color */
			if (isset($options['color']) && $options['color'] !== FALSE) {
				$output .= '<div class="dilaz-mb-font">';
					$output .= '<strong>'. __('Color', 'dilaz-metabox') .'</strong><br />';
					if (isset($saved_fonts['color']) && $saved_fonts['color'] != '' && $saved_fonts['color'] !== FALSE) {
						$font_color = sanitize_hex_color($saved_fonts['color']);
					} else if (isset($std['color']) && $std['color'] != '') {
						$font_color = sanitize_hex_color($std['color']);
					} else if (isset($font_defaults['color']) && $font_defaults['color'] > 0) {
						$font_color = sanitize_hex_color($font_defaults['color']);
					} else {
						$font_color = '#333';
					}
					$output .= '<input id="'. esc_attr($id) .'-color" name='. esc_attr($id) .'[color]" class="dilaz-mb-color color" type="text" value="'. $font_color .'" data-default-color="'. $font_color .'" />';
				$output .= '</div>';
			}

			/* font subset */
			if (isset($options['subset']) && $options['subset'] !== FALSE) {
				$output .= '<div class="dilaz-mb-font">';
					$output .= '<strong>'. __('Font Subset', 'dilaz-metabox') .'</strong><br />';
					$output .= '<select id="'. esc_attr($id) .'-subset" name="'. esc_attr($id) .'[subset][]" class="subset select2multiple" data-width="320px" multiple="multiple">';
					$font_subsets = is_array($options['subset']) ? $options['subset'] : DilazMetaboxDefaults\DilazMetaboxDefaults::_font_subset();
					foreach ($font_subsets as $key => $font_subset) {
						$selected_subset = is_array($saved_fonts['subset']) ? (isset($std['subset']) && in_array($key, $saved_fonts['subset']) ? 'selected="selected"' : '') : '';
						$output .= '<option value="'. $key .'" '. $selected_subset .'>'. $font_subset .'</option>';
					}
					$output .= '</select>';
				$output .= '</div>';
			}

			$output .= '<div class="dilaz-mb-font font-preview" style="display:none">';
				$output .= '<div class="content">1 2 3 4 5 6 7 8 9 0 A B C D E F G H I J K L M N O P Q R S T U V W X Y Z a b c d e f g h i j k l m n o p q r s t u v w x y z</div>';
			$output .= '</div>';

			echo $output;
		}

		/**
		 * Date
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldDate($field) {

			extract($field);

			$class    = isset($class) ? sanitize_html_class($class) : '';
			$size     = isset($args['size']) ? intval($args['size']) : '20';
			$format   = isset($args['format']) ? $args['format'] : 'l, d F, Y';
			$selected = $meta ? date($format, $meta) : '';

			$output = '<input type="text" class="dilaz-mb-date dilaz-mb-input-style '. $class .'" name="'. esc_attr($id) .'" id="'. esc_attr($id) .'" value="'. $selected .'" size="'. $size .'" />'. $suffix .''. $desc2 .'';

			echo $output;
		}

		/**
		 * Date - (From - to)
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldDateFromTo($field) {

			extract($field);

			$output = '';

			$size  = isset($args['size']) ? intval($args['size']) : '30';
			$class = isset($class) ? sanitize_html_class($class) : '';

			$from_args   = isset($args['from_args']) ? $args['from_args'] : array();
			$from_format = isset($from_args['format']) ? $from_args['format'] : 'l, d F, Y';
			$from_prefix = isset($from_args['prefix']) ? $from_args['prefix'] : '';
			$from_suffix = isset($from_args['suffix']) ? $from_args['suffix'] : '';
			$from_date   = isset($from_args['date']) && !empty($from_args['date']) ?  $from_args['date'] : '';
			$from_date   = isset($meta['from']) && is_numeric($meta['from']) ? date($from_format, $meta['from']) : $from_date;

			$to_args   = isset($args['to_args']) ? $args['to_args'] : array();
			$to_format = isset($to_arg['format']) ? $to_arg['format'] : 'l, d F, Y';
			$to_prefix = isset($to_args['prefix']) ? $to_args['prefix'] : '';
			$to_suffix = isset($to_args['suffix']) ? $to_args['suffix'] : '';
			$to_date   = isset($to_args['date']) && !empty($to_args['date']) ? $to_args['date'] : '';
			$to_date   = isset($meta['to']) && is_numeric($meta['to']) ? date($to_format, $meta['to']) : $to_date;

			$output .= '<div class="dilaz-mb-date-from-to '. $class .'">';
			$output .= '<table>';
				$output .= '<tr>';
					$output .= '<td>'. $from_prefix .'</td>';
					$output .= '<td><input type="text" class="from-date dilaz-mb-input-style" name="'. esc_attr($id) .'[from]" id="'. esc_attr($id) .'[from]" value="'. $from_date .'" size="'. $size .'" /></td>';
					$output .= '<td>'. $from_suffix .'</td>';
				$output .= '</tr>';
				$output .= '<tr>';
					$output .= '<td>'. $to_prefix .'</td>';
					$output .= '<td><input type="text" class="to-date dilaz-mb-input-style" name="'. esc_attr($id) .'[to]" id="'. esc_attr($id) .'[to]" value="'. $to_date .'" size="'. $size .'" /></td>';
					$output .= '<td>'. $to_suffix .'</td>';
				$output .= '</tr>';
			$output .= '</table>';

			$output .= $desc2;

			$output .= '</div>';

			echo $output;
		}

		/**
		 * Month
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldMonth($field) {

			extract($field);

			$size     = isset($args['size']) ? intval($args['size']) : '20';
			$class    = isset($class) ? sanitize_html_class($class) : '';
			$format   = isset($args['format']) ? $args['format'] : 'F, Y';
			$selected = $meta ? date($format, $meta) : '';

			$output = '<input type="text" class="dilaz-mb-month dilaz-mb-input-style '. $class .'" name="'. esc_attr($id) .'" id="'. esc_attr($id) .'" value="'. $selected .'" size="'. $size .'" />'. $suffix .''. $desc2 .'';

			echo $output;

		}

		/**
		 * Month - (From - To)
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldMonthFromTo($field) {

			extract($field);

			$output = '';

			$size  = isset($args['size']) ? intval($args['size']) : '20';
			$class = isset($class) ? sanitize_html_class($class) : '';

			$from_args   = isset($args['from_args']) ? $args['from_args'] : array();
			$from_format = isset($from_args['format']) ? $from_args['format'] : 'F, Y';
			$from_prefix = isset($from_args['prefix']) ? $from_args['prefix'] : '';
			$from_suffix = isset($from_args['suffix']) ? $from_args['suffix'] : '';
			$from_month  = isset($from_args['month']) && !empty($from_args['month']) ?  $from_args['month'] : '';
			$from_month  = isset($meta['from']) && is_numeric($meta['from']) ? date($from_format, $meta['from']) : $from_month;

			$to_args   = isset($args['to_args']) ? $args['to_args'] : array();
			$to_format = isset($to_arg['format']) ? $to_arg['format'] : 'F, Y';
			$to_prefix = isset($to_args['prefix']) ? $to_args['prefix'] : '';
			$to_suffix = isset($to_args['suffix']) ? $to_args['suffix'] : '';
			$to_month  = isset($to_args['month']) && !empty($to_args['month']) ? $to_args['month'] : '';
			$to_month  = isset($meta['to']) && is_numeric($meta['to']) ? date($to_format, $meta['to']) : $to_month;

			$output .= '<div class="dilaz-mb-month-from-to '. $class .'">';
			$output .= '<table>';
				$output .= '<tr>';
					$output .= '<td>'. $from_prefix .'</td>';
					$output .= '<td><input type="text" class="from-month dilaz-mb-input-style" name="'. esc_attr($id) .'[from]" id="'. esc_attr($id) .'[from]" value="'. $from_month .'" size="'. $size .'" /></td>';
					$output .= '<td>'. $from_suffix .'</td>';
				$output .= '</tr>';
				$output .= '<tr>';
					$output .= '<td>'. $to_prefix .'</td>';
					$output .= '<td><input type="text" class="to-month dilaz-mb-input-style" name="'. esc_attr($id) .'[to]" id="'. esc_attr($id) .'[to]" value="'. $to_month .'" size="'. $size .'" /></td>';
					$output .= '<td>'. $to_suffix .'</td>';
				$output .= '</tr>';
			$output .= '</table>';

			$output .= $desc2;

			$output .= '</div>';

			echo $output;
		}

		/**
		 * Time
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldtime($field) {

			extract($field);

			$size     = isset($args['size']) ? intval($args['size']) : '20';
			$class    = isset($class) ? sanitize_html_class($class) : '';
			$format   = isset($args['format']) ? $args['format'] : 'h:i:s A';
			$selected = isset($meta) && is_numeric($meta) ? date($format, $meta) : '';

			$output = '<input type="text" class="dilaz-mb-time dilaz-mb-input-style '. $class .'" name="'. esc_attr($id) .'" id="'. esc_attr($id) .'" value="'. $selected .'" size="'. $size .'" />'. $suffix .''. $desc2 .'';

			echo $output;
		}

		/**
		 * Time - (From - To)
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldTimeFromTo($field) {

			extract($field);

			$output = '';

			$size  = isset($args['size']) ? intval($args['size']) : '20';
			$class = isset($class) ? sanitize_html_class($class) : '';

			$from_args   = isset($args['from_args']) ? $args['from_args'] : array();
			$from_format = isset($from_args['format']) ? $from_args['format'] : 'h:i:s A';
			$from_prefix = isset($from_args['prefix']) ? $from_args['prefix'] : '';
			$from_suffix = isset($from_args['suffix']) ? $from_args['suffix'] : '';
			$from_time   = isset($from_args['time']) && !empty($from_args['time']) ?  $from_args['time'] : '';
			$from_time   = isset($meta['from']) && is_numeric($meta['from']) ? date($from_format, $meta['from']) : $from_time;

			$to_args   = isset($args['to_args']) ? $args['to_args'] : array();
			$to_format = isset($to_arg['format']) ? $to_arg['format'] : 'h:i:s A';
			$to_prefix = isset($to_args['prefix']) ? $to_args['prefix'] : '';
			$to_suffix = isset($to_args['suffix']) ? $to_args['suffix'] : '';
			$to_time   = isset($to_args['time']) && !empty($to_args['time']) ? $to_args['time'] : '';
			$to_time   = isset($meta['to']) && is_numeric($meta['to']) ? date($to_format, $meta['to']) : $to_time;

			$output .= '<div class="dilaz-mb-time-from-to '. $class .'">';
			$output .= '<table>';
				$output .= '<tr>';
					$output .= '<td>'. $from_prefix .'</td>';
					$output .= '<td><input type="text" class="from-time dilaz-mb-input-style" name="'. esc_attr($id) .'[from]" id="'. esc_attr($id) .'[from]" value="'. $from_time .'" size="'. $size .'" /></td>';
					$output .= '<td>'. $from_suffix .'</td>';
				$output .= '</tr>';
				$output .= '<tr>';
					$output .= '<td>'. $to_prefix .'</td>';
					$output .= '<td><input type="text" class="to-time dilaz-mb-input-style" name="'. esc_attr($id) .'[to]" id="'. esc_attr($id) .'[to]" value="'. $to_time .'" size="'. $size .'" /></td>';
					$output .= '<td>'. $to_suffix .'</td>';
				$output .= '</tr>';
			$output .= '</table>';

			$output .= $desc2;

			$output .= '</div>';

			echo $output;
		}

		/**
		 * Date Time
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldDateTime($field) {

			extract($field);

			$size     = isset($args['size']) ? intval($args['size']) : '40';
			$class    = isset($class) ? sanitize_html_class($class) : '';
			$format   = isset($args['format']) ? $args['format'] : 'l, d F Y h:i:s A';
			$selected = $meta ? date($format, $meta) : '';

			$output = '<input type="text" class="dilaz-mb-date-time dilaz-mb-input-style '. $class .'" name="'. esc_attr($id) .'" id="'. esc_attr($id) .'" value="'. $selected .'" size="'. $size .'" />'. $suffix .''. $desc2 .'';

			echo $output;
		}

		/**
		 * Date Time - (From - To)
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldDateTimeFromTo($field) {

			extract($field);

			$output = '';

			$size  = isset($args['size']) ? intval($args['size']) : '40';
			$class = isset($class) ? sanitize_html_class($class) : '';

			$from_args      = isset($args['from_args']) ? $args['from_args'] : array();
			$from_format    = isset($from_args['format']) ? $from_args['format'] : 'l, d F Y h:i:s A';
			$from_prefix    = isset($from_args['prefix']) ? $from_args['prefix'] : '';
			$from_suffix    = isset($from_args['suffix']) ? $from_args['suffix'] : '';
			$from_date_time = isset($from_args['date_time']) && !empty($from_args['date_time']) ? $from_args['date_time'] : '';
			$from_date_time = isset($meta['from']) && is_numeric($meta['from']) ? date($from_format, $meta['from']) : $from_date_time;

			$to_args      = isset($args['to_args']) ? $args['to_args'] : array();
			$to_format    = isset($to_arg['format']) ? $to_arg['format'] : 'l, d F Y h:i:s A';
			$to_prefix    = isset($to_args['prefix']) ? $to_args['prefix'] : '';
			$to_suffix    = isset($to_args['suffix']) ? $to_args['suffix'] : '';
			$to_date_time = isset($to_args['date_time']) && !empty($to_args['date_time']) ? $to_args['date_time'] : '';
			$to_date_time = isset($meta['to']) && is_numeric($meta['to']) ? date($to_format, $meta['to']) : $to_date_time;

			$output .= '<div class="dilaz-mb-date-time-from-to '. $class .'">';
			$output .= '<table>';
				$output .= '<tr>';
					$output .= '<td>'. $from_prefix .'</td>';
					$output .= '<td><input type="text" class="from-date-time dilaz-mb-input-style" name="'. esc_attr($id) .'[from]" id="'. esc_attr($id) .'[from]" value="'. $from_date_time .'" size="'. $size .'" /></td>';
					$output .= '<td>'. $from_suffix .'</td>';
				$output .= '</tr>';
				$output .= '<tr>';
					$output .= '<td>'. $to_prefix .'</td>';
					$output .= '<td><input type="text" class="to-date-time dilaz-mb-input-style" name="'. esc_attr($id) .'[to]" id="'. esc_attr($id) .'[to]" value="'. $to_date_time .'" size="'. $size .'" /></td>';
					$output .= '<td>'. $to_suffix .'</td>';
				$output .= '</tr>';
			$output .= '</table>';

			$output .= $desc2;

			$output .= '</div>';

			echo $output;
		}

		/**
		 * Slider
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldSlideRange($field) {

			extract($field);

			$output = '';

			$meta   = $meta != '' ? (int)$meta : '0';
			$min    = isset($args['min']) ? (int)$args['min'] : '';
			$max    = isset($args['max']) ? (int)$args['max'] : '';
			$step   = isset($args['step']) ? (int)$args['step'] : '';
			$prefix = isset($args['prefix']) ? sanitize_text_field($args['prefix']) : '';
			$suffix = isset($args['suffix']) ? sanitize_text_field($args['suffix']) : '';
			$class  = isset($class) ? sanitize_html_class($class) : '';

			$output .= '<input type="hidden" id="'. esc_attr($id) .'" name="'. esc_attr($id) .'" value="'. esc_attr($meta) .'" />';
			$output .= '<div class="dilaz-mb-slider '. $class .'" data-val="'. esc_attr($meta) .'" data-min="'. esc_attr($min) .'" data-max="'. esc_attr($max) .'" data-step="'. esc_attr($step) .'"></div>';
			$output .= '<div class="dilaz-mb-slider-val">'. $prefix .'<span>'. esc_attr($meta) .'</span>'. $suffix .'</div>';

			echo $output;
		}

		/**
		 * Range
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldRange($field) {

			extract($field);

			$output = '';

			$minStd  = isset($std['min_std']) ? (int)$std['min_std'] : 0;
			$maxStd  = isset($std['max_std']) ? (int)$std['max_std'] : 0;
			$meta    = $meta != '' ? (array)$meta : '0';
			$min_val = is_array($meta) && isset($meta['min']) ? (int)$meta['min'] : $minStd;
			$max_val = is_array($meta) && isset($meta['max']) ? (int)$meta['max'] : $maxStd;
			$min     = isset($args['min'][0]) ? (int)$args['min'][0] : 0;
			$max     = isset($args['max'][0]) ? (int)$args['max'][0] : 0;
			$minName = isset($args['min'][1]) ? (string)$args['min'][1] : '';
			$maxName = isset($args['max'][1]) ? (string)$args['max'][1] : '';
			$step    = isset($args['step']) ? (int)$args['step'] : '';
			$prefix  = isset($args['prefix']) && $args['prefix'] != '' ? sanitize_text_field($args['prefix']) : '';
			$suffix  = isset($args['suffix']) && $args['suffix'] != '' ? sanitize_text_field($args['suffix']) : '';
			$class   = isset($class) ? sanitize_html_class($class) : '';

			$output .= '<div class="dilaz-mb-range '. $class .'" data-min-val="'. esc_attr($min_val) .'" data-max-val="'. esc_attr($max_val) .'" data-min="'. esc_attr($min) .'" data-max="'. esc_attr($max) .'" data-step="'. esc_attr($step) .'">';
				$output .= '<div class="dilaz-mb-slider-range"></div>';
				$output .= '<input type="hidden" class="" name="'. esc_attr($id) .'[min]" id="option-min" value="'. esc_attr($min_val) .'" placeholder="" size="7">';
				$output .= '<div class="dilaz-mb-min-val"><span class="min">'. $minName .'</span>'. $prefix .'<span class="val">'. esc_attr($min_val) .'</span>'. $suffix .'</div>';
				$output .= '<input type="hidden" class="" name="'. esc_attr($id) .'[max]" id="option-max" value="'. esc_attr($max_val) .'" placeholder="" size="7">';
				$output .= '<div class="dilaz-mb-max-val"><span class="max">'. $maxName .'</span>'. $prefix .'<span class="val">'. esc_attr($max_val) .'</span>'. $suffix .'</div>';
			$output .= '</div>';


			echo $output;
		}

		/**
		 * File Upload
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldUpload($field) {

			global $post;

			extract($field);

			$output = '';

			$class = isset($class) ? sanitize_html_class($class) : '';

			$show_thumb         = isset($args['show_thumb']) && $args['show_thumb'] == FALSE ? 'false' : 'true';
			$data_file_thumb    = 'data-file-thumb="'. $show_thumb .'"';
			$is_file_multiple   = isset($args['multiple']) && $args['multiple'] == TRUE ? TRUE : FALSE;
			$data_file_multiple = $is_file_multiple ? 'data-file-multiple="true"' : '';
			$file_type          = (isset($args['file_type']) && $args['file_type'] != '') ? strtolower($args['file_type']) : 'image';
			$data_file_type     = $file_type != '' ? 'data-file-type="'. $file_type .'"' : 'data-file-type="image"';
			$data_file_specific = (isset($args['file_specific']) && $args['file_specific'] == true) ? 'data-file-specific="true"' : '';
			$frame_title        = (isset($args['frame_title']) && $args['frame_title'] != '') ? sanitize_text_field($args['frame_title']) : '';
			$frame_button_text  = (isset($args['frame_button_text']) && $args['frame_button_text'] != '') ? sanitize_text_field($args['frame_button_text']) : '';

      // Frame title and button text based on file type
      $file_type_labels = [
        'image' => [__('Choose Image', 'dilaz-metabox'), __('Use Selected Image', 'dilaz-metabox')],
        'audio' => [__('Choose Audio', 'dilaz-metabox'), __('Use Selected Audio', 'dilaz-metabox')],
        'video' => [__('Choose Video', 'dilaz-metabox'), __('Use Selected Video', 'dilaz-metabox')],
        'document' => [__('Choose Document', 'dilaz-metabox'), __('Use Selected Document', 'dilaz-metabox')],
        'spreadsheet' => [__('Choose Spreadsheet', 'dilaz-metabox'), __('Use Selected Spreadsheet', 'dilaz-metabox')],
        'interactive' => [__('Choose Interactive File', 'dilaz-metabox'), __('Use Selected Interactive File', 'dilaz-metabox')],
        'text' => [__('Choose Text File', 'dilaz-metabox'), __('Use Selected Text File', 'dilaz-metabox')],
        'archive' => [__('Choose Archive File', 'dilaz-metabox'), __('Use Selected Archive File', 'dilaz-metabox')],
        'code' => [__('Choose Code File', 'dilaz-metabox'), __('Use Selected Code File', 'dilaz-metabox')],
      ];

      $default_labels = $file_type_labels[$file_type] ?? [__('Choose File', 'dilaz-metabox'), __('Use Selected File', 'dilaz-metabox')];
      $data_frame_title = 'data-frame-title="' . esc_attr($frame_title ?: $default_labels[0]) . '"';
      $data_frame_b_txt = 'data-frame-button-text="' . esc_attr($frame_button_text ?: $default_labels[1]) . '"';

			$output .= '<div class="dilaz-mb-file-upload '. $class .'">';

			// $output .= '<input type="'. (!$is_file_multiple ? "text" : "hidden") .'" name="'. esc_attr($id) .'[url][]" id="file_url_'. esc_attr($id) .'" class="dilaz-mb-input dilaz-mb-text dilaz-mb-file-url upload" value="'. $the_file_url .'" size="0" rel="" placeholder="Choose file" />';

			$upload_button_text  = (isset($args['upload_button_text']) && $args['upload_button_text'] != '') ? sanitize_text_field($args['upload_button_text']) : sprintf(__('Upload %s file', 'dilaz-metabox'), $file_type);

			$output .= '<input type="button" id="upload-'. esc_attr($id) .'" class="dilaz-mb-file-upload-button button" value="'. $upload_button_text .'" rel="'. $post->ID .'" '. $data_file_type.' '. $data_file_specific .' '. $data_file_multiple .' '. $data_frame_title .' '. $data_frame_b_txt .' '. $data_file_thumb .' />';

				$output .= '<div class="dilaz-mb-file-wrapper" data-file-id="'. esc_attr($id) .'" '. $data_file_multiple .'>';

				// $output .= '<input type="hidden" name="'. esc_attr($id) .'[id][]" id="file_id_'. esc_attr($id) .'" class="dilaz-mb-file-id upload" value="" size="0" rel="" />';

				if ($meta != '' && is_array($meta)) {
					foreach ($meta as $key => $file_data) {

						if ( $key == 'url' || (isset($file_data['url']) && $file_data['url'] != '') ) {

              $attachment_url = is_array($file_data) && isset($file_data['url'])
                  ? $file_data['url']
                  : (is_string($file_data) && !empty($file_data) ? $file_data : '');

              $attachment_id = isset($file_data['id']) && !empty($file_data['id'])
                  ? (int) $file_data['id']
                  : (!empty($attachment_url) ? attachment_url_to_postid($attachment_url) : '');

							// if ($attachment_id) {
								// $file = wp_get_attachment_image_src($attachment_id, 'thumbnail'); $file = $file[0];
								// $file_full = wp_get_attachment_image_src($attachment_id, 'full'); $file_full = $file_full[0];
							// } else {
								// $file = '';
								// $file_full = '';
							// }

							if (!empty($attachment_url)) {

                if (FALSE !== get_post_status($attachment_id)) {
                  $image_size = $show_thumb == 'true' ? 'thumbnail' : 'large';
                  $file = wp_get_attachment_image_src($attachment_id, $image_size); $file = $file[0];
                } else {
                  $file = $attachment_url;
                }

								$output .= '<div class="dilaz-mb-media-file '. $file_type .' '. ($attachment_id != '' ? '' : 'empty') .'" id="file-'. esc_attr($id) .'">';

								$output .= '<input type="hidden" name="'. esc_attr($id) .'[url][]" id="file_url_'. esc_attr($id) .'" class="dilaz-mb-input dilaz-mb-text dilaz-mb-file-url upload" value="'. $attachment_url .'" size="0" rel="" placeholder="Choose file" />';

								$output .= '<input type="hidden" name="'. esc_attr($id) .'[id][]" id="file_id_'. esc_attr($id) .'" class="dilaz-mb-file-id upload" value="'. $attachment_id .'" size="30" rel"" />';

								$output .= sizeof($meta) > 1 ? '<span class="sort"></span>' : '';

								/* get attachment data */
								$attachment = get_post($attachment_id);

								/* get file extension */
								$file_ext = is_object($attachment) ? pathinfo($attachment->guid, PATHINFO_EXTENSION) : pathinfo($attachment_url, PATHINFO_EXTENSION);

								/* get file title */
								$file_title = is_object($attachment) ? $attachment->post_title : pathinfo($attachment_url, PATHINFO_FILENAME);

								/* get file type */
								$file_type = wp_ext2type($file_ext);

								$output .= '<div class="filename '. $file_type .'">'. $file_title .'</div>';

								$media_remove = '<a href="#" class="dilaz-mb-remove-file" title="'. __('Remove', 'dilaz-metabox') .'"><span class="mdi mdi-window-close"></span></a>';

                $file_type_images = [
                  'image' => $file,
                  'audio' => DILAZ_MB_IMAGES . 'media/audio.png',
                  'video' => DILAZ_MB_IMAGES . 'media/video.png',
                  'document' => DILAZ_MB_IMAGES . 'media/document.png',
                  'spreadsheet' => DILAZ_MB_IMAGES . 'media/spreadsheet.png',
                  'interactive' => DILAZ_MB_IMAGES . 'media/interactive.png',
                  'text' => DILAZ_MB_IMAGES . 'media/text.png',
                  'archive' => DILAZ_MB_IMAGES . 'media/archive.png',
                  'code' => DILAZ_MB_IMAGES . 'media/code.png',
                ];

                $output .= $file_ext ? '<img src="' . esc_url($file_type_images[$file_type] ?? '') . '" class="dilaz-mb-file-preview file-' . esc_attr($file_type) . '" alt="" />' . $media_remove : '';
								$output .= '</div><!-- .dilaz-mb-media-file -->'; // end .dilaz-mb-media-file
							}
						}
					}
				}
				$output .= '</div><!-- .dilaz-mb-file-wrapper -->'; // end .dilaz-mb-file-wrapper
				$output .= '<div class="clear"></div>';
			$output .= '</div><!-- .dilaz-mb-file-upload -->'; // end .dilaz-mb-file-upload
			echo $output;
		}

		/**
		 * Buttonset
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldButtonset($field) {

			extract($field);

			$output = '';

			$class = isset($class) ? sanitize_html_class($class) : '';

			$value = isset($meta) ? $meta : '';
			foreach ($options as $key => $option) {
				$checked  = '';
				$selected = '';
				if (null != checked($value, $key, false)) {
					$checked  = checked($value, $key, false);
					$selected = 'selected';
				}
				$output .= '<label for="'. esc_attr($id .'-'. $key) .'" class="dilaz-mb-button-set-button '. $selected .' '. $class .'"><input type="radio" class="dilaz-mb-input dilaz-mb-button-set" name="'. esc_attr($id) .'" id="'. esc_attr($id .'-'. $key) .'" value="'. esc_attr($key) .'" '. $checked .' /><span>'. esc_html($option) .'</span></label>';
			}

			echo $output;
		}

		/**
		 * Switch
		 *
		 * @since  1.0
		 * @access public
		 *
		 * @param array $field Field arguments
		 * @echo  html  $output
		 */
		public static function fieldSwitch($field) {

			extract($field);

			$output = '';

			$class = isset($class) ? sanitize_html_class($class) : '';

			$value = isset($meta) ? $meta : '';
			$i = 0;
			foreach ($options as $key => $option) {
				$i++;
				$checked = '';
				$selected = '';
				if (null != checked($value, $key, false)) {
					$checked = checked($value, $key, false);
					$selected = 'selected';
				}
				$state = ($i == 1) ? 'switch-on' : 'switch-off';
				$output .= '<label for="'. esc_attr($id .'-'. $key) .'" class="dilaz-mb-switch-button '. $selected .' '. $state .' '. $class .'"><input type="radio" class="dilaz-mb-input dilaz-mb-switch" name="'. esc_attr($id) .'" id="'. esc_attr($id .'-'. $key) .'" value="'. esc_attr($key) .'" '. $checked .' /><span>'. esc_html($option) .'</span></label>';
			}

			echo $output;
		}
	}
}