<?php
/**
Plugin Name: oik-nivo-slider
Requires Plugins: oik
Plugin URI: https://oik-plugins.com/oik-plugins/oik-nivo-slider/
Description: [nivo] shortcode for the Nivo slider using oik
Version: 1.18.0
Author: bobbingwide
Author URI: https://bobbingwide.com/about-bobbing-wide
Text Domain: oik-nivo-slider
Domain Path: /languages/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

    Copyright 2012-2026 Bobbing Wide (email : herb@bobbingwide.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html

*/

/**
 * Implement "oik_loaded" action for the oik-nivo-slider 
 */
function oik_nivo_add_shortcodes() {
  bw_add_shortcode( "nivo", "bw_nivo_slider", oik_path( "nivo.php", "oik-nivo-slider" ), false );

}

/**
 * Implement "admin_menu" action for the oik-nivo-slider 
 */
function oik_nivo_admin_menu() {
  require_once( "admin/oik-nivo-slider.php" );
  oik_nivo_lazy_admin_menu();
}

/**
 * Implements "admin_notices" action for oik-nivo-slider 
 *
 * This code will produce a message when oik-nivo-slider is activated but oik isn't.
 *
 * Version | Dependency
 * ------- | --------------
 * v1.13   | oik v2.1
 * v1.14.0 | oik v2.4
 * v1.14.2 | oik v3.0.0
 * v1.15.0 | oik v3.2.1
 * v1.15.2 | oik v3.2.8
 * v1.18.0 | oik v4.15.4
 *
 */ 
function oik_nivo_activation() {
  static $plugin_basename = null;
  if ( !$plugin_basename ) {
    $plugin_basename = plugin_basename(__FILE__);
    add_action( "after_plugin_row_oik-nivo-slider/oik-nivo-slider.php" , "oik_nivo_activation" );   
    if ( !function_exists( "oik_plugin_lazy_activation" ) ) { 
      require_once( "admin/oik-activation.php" );
    }
  }  
  $depends = "oik:4.15.4";
  oik_plugin_lazy_activation( __FILE__, $depends, "oik_plugin_plugin_inactive" );
}

/**
 * function to invoke when oik-nivo-slider loaded 
 */                  
function oik_nivo_loaded() {
  add_action( "admin_notices", "oik_nivo_activation", 12 );
  add_action( "oik_loaded", "oik_nivo_add_shortcodes" );
  add_action( 'oik_loaded', 'oik_nivo_register_block');
  add_action( "oik_admin_menu", "oik_nivo_admin_menu" );
}

function oik_nivo_register_block() {

	if ( is_admin() ) {
		oik_nivo_register_editor_script();
	}
	load_plugin_textdomain( 'oik-nivo-slider', false, 'oik-nivo-slider/languages' );
	$args      =[ 'render_callback'=>'oik_nivo_dynamic_block_nivo' ];
	$registered = register_block_type_from_metadata( __DIR__ . '/build/nivo', $args );
	bw_trace2( $registered, "registered");
	//print_r( $registered);
	//$registered = register_block_type_from_metadata( __DIR__ . '/src/second-block' );

}

/**
 * Registers the block editor script manually.
 *
 * If the `editorScript` property was set to a file name in block.json, register_block_type_from_metadata() would normally register the `editorScript` automatically.
 * But since we need to add the additional dependency on nivo-slider-32-js, we register this script manually, using logic similar to that in register_block_script_handle()
 * and set the entry in block.json to the script handle.
 */
function oik_nivo_register_editor_script() {
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/nivo.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		_doing_it_wrong(
			__FUNCTION__,
			__( 'The asset file ( build/nivo.asset.php ) is missing.', 'oik-nivo-slider' ),
			'1.0.0'
		);
		return false;
	}
	$index_js     = 'build/nivo.js';
	$script_asset = require( $script_asset_path );
	//bw_trace2( $script_asset );
	if ( is_admin() ) {
		oik_require( 'nivo.php', 'oik-nivo-slider');
		bw_nivo_enqueue_script( '32');
		$script_asset['dependencies'][] = 'nivo-slider-32-js';
		//$script_asset['dependencies'][] = 'chartjs-adapter-date-fns-script';
	}

	wp_register_script(
		'oik-nivo-slider-nivo-editor-script',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);
}




/**
 * Server rendering dynamic CSV block
 *
 *
 * @param array $attributes
 * @return string generated HTML
 */
function oik_nivo_dynamic_block_nivo( $attributes ) {

	//bw_backtrace();
	$html = \oik\oik_blocks\oik_blocks_check_server_func( "nivo.php", "oik-nivo-slider", "bw_nivo_slider");
	if ( !$html ) {
		if ( function_exists( "oik_is_block_renderer") ) {
			oik_is_block_renderer( true );
		}
		//do_action( "oik_add_shortcodes");
		//$content = bw_array_get( $attributes, "content", null );
		//bw_trace2( $content, "Content", true, BW_TRACE_VERBOSE );
		//oik_require( "shortcodes/oik-csv.php", "oik-bob-bing-wide" );

		/**
		 * Just a quick test to display the Nivo slider screenshots

		$attributes['post_type'] = 'screenshot:oik-nivo-slider';
		$attributes['theme'] = 'oik';
		$attributes['caption'] = 'n';
		$attributes['link'] = 'n';
 */
		$html = bw_nivo_slider( $attributes );
		$html = oik_nivo_server_side_wrapper( $attributes, $html );
		bw_trace2( $html, "html", false, BW_TRACE_VERBOSE );
	}
	return $html;
}

/**
 * Implements wrapper for Server Side Rendered blocks.
 *
 * Here we have to specifically add `has-text-align-something`
 * to the classes in order to support left, center or right alignment.
 *
 * @param $attributes
 * @param $html
 * @return string
 */
function oik_nivo_server_side_wrapper( $attributes, $html ) {
	$align_class_name=empty( $attributes['textAlign'] ) ? '' : "has-text-align-{$attributes['textAlign']}";
	$align_class_name .= empty( $attributes['align'] ) ? '' : " has-text-align-{$attributes['align']}";
	$extra_attributes  =[ 'class'=>$align_class_name ];
	$wrapper_attributes = get_block_wrapper_attributes( $extra_attributes );
	$html=sprintf(
		'<div %1$s>%2$s</div>',
		$wrapper_attributes,
		$html
	);
	return $html;
}

oik_nivo_loaded();