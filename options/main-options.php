<?php
/*
|| --------------------------------------------------------------------------------------------
|| Theme Metaboxes Fields
|| --------------------------------------------------------------------------------------------
||
|| @package		Dilaz Metaboxes
|| @subpackage	Main Options
|| @since		Dilaz Metaboxes 1.0
|| @author		WebDilaz Team, http://webdilaz.com
|| @copyright	Copyright (C) 2017, WebDilaz LTD
|| @link		http://webdilaz.com/metaboxes
|| @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
|| 
|| NOTE: Add all your theme/plugin options in this file
|| 
*/

defined('ABSPATH') || exit;

/**
 * Define the metaboxes and field configurations
 *
 * @param	array $dilaz_meta_boxes
 * @return	array
 */
add_filter( 'dilaz_meta_boxes_filter', 'dilaz_meta_boxes' );
function dilaz_meta_boxes( array $dilaz_meta_boxes ) {
	
	# BOX - Sample Set 1
	# =============================================================================================
	$dilaz_meta_boxes[] = array(
		'id'	   => DILAZ_MB_PREFIX .'samp_set_1',
		'title'	   => __('Sample Set 1', 'dilaz-metabox'),
		'pages'    => array('post', 'page'),
		'context'  => 'normal',
		'priority' => 'high',
		'type'     => 'metabox_set'
	);
		
		# TAB - Sample 1 Tab 1
		# *****************************************************************************************
		$dilaz_meta_boxes[] = array(
			'id'    => DILAZ_MB_PREFIX .'samp_1_tab_1',
			'title' => __('Sample 1 - Tab 1', 'dilaz-metabox'),
			'icon'  => 'fa-bank',
			'type'  => 'metabox_tab'
		);
			
			# FIELDS - Sample 1 Tab 1
			# >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$dilaz_meta_boxes[] = array(
				'id'	  => DILAZ_MB_PREFIX .'samp_1_tab_1_opt_1',
				'name'	  => __('Tab 1 - Option 1:', 'dilaz-metabox'),
				'desc'	  => '',
				'type'	  => 'radio',
				'options' => dilaz_mb_var('yes_no'),
				'std'     => 'no'
			);
			
			
		# TAB - Sample 1 Tab 2
		# *****************************************************************************************
		$dilaz_meta_boxes[] = array(
			'id'    => DILAZ_MB_PREFIX .'samp_1_tab_2',
			'title' => __('Sample 1 - Tab 2', 'dilaz-metabox'),
			'icon'  => 'fa-automobile',
			'type'  => 'metabox_tab'
		);
			
			# FIELDS - Sample 1 Tab 2
			# >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$dilaz_meta_boxes[] = array(
				'id'	  => DILAZ_MB_PREFIX .'samp_1_tab_1_opt_2',
				'name'	  => __('Tab 2 - Option 1:', 'dilaz-metabox'),
				'desc'	  => '',
				'type'	  => 'radio',
				'options' => dilaz_mb_var('yes_no'),
				'std'     => 'no'
			);
			
			
			
			
	# BOX - Sample Set 2
	# =============================================================================================
	$dilaz_meta_boxes[] = array(
		'id'	   => DILAZ_MB_PREFIX .'samp_set_2',
		'title'	   => __('Sample Set 2', 'dilaz-metabox'),
		'pages'    => array('post', 'page'),
		'context'  => 'normal',
		'priority' => 'high',
		'type'     => 'metabox_set'
	);
		
		# TAB - Sample 2 Tab 1
		# *****************************************************************************************
		$dilaz_meta_boxes[] = array(
			'id'    => DILAZ_MB_PREFIX .'samp_2_tab_1',
			'title' => __('Sample 2 - Tab 1', 'dilaz-metabox'),
			'icon'  => 'fa-bank',
			'type'  => 'metabox_tab'
		);
			
			# FIELDS - Sample 2 Tab 1
			# >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$dilaz_meta_boxes[] = array(
				'id'	  => DILAZ_MB_PREFIX .'samp_2_tab_1_opt_1',
				'name'	  => __('Tab 1 - Option 1:', 'dilaz-metabox'),
				'desc'	  => '',
				'type'	  => 'radio',
				'options' => dilaz_mb_var('yes_no'),
				'std'     => 'no'
			);
			
	return $dilaz_meta_boxes;
}