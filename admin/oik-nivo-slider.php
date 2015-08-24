<?php // (C) Copyright Bobbing Wide 2012-2014

/**
 * Define Nivo slider settings fields and page
 */
function oik_nivo_lazy_admin_menu() {
  register_setting( 'oik_nivo_options', 'bw_nivo_slider', 'oik_plugins_validate' ); // No validation for oik-nivo-slider
  add_submenu_page( 'oik_menu', 'oik nivo slider settings', "Nivo settings", 'manage_options', 'oik_nivo', "oik_nivo_options_do_page" );
}

/**
 * Nivo slider settings page
 */
function oik_nivo_options_do_page() {
  oik_menu_header( "Nivo slider options", "w50pc" );
  oik_box( NULL, NULL, "Default slider options", "oik_nivo_slider_options" );
  ecolumn();
  scolumn( "w50pc" );
  oik_box( NULL, NULL, "Usage notes", "oik_nivo_slider_usage" );
  oik_menu_footer();
  bw_flush();
}

/**
 * Settings for the jQuery Nivo slider  
 *
 * Extract from jQuery Nivo slider below
 * The setting selection field has been set for each line marked with an 'x' 

      x	effect: 'random',
      x	slices: 15,
      x	boxCols: 8,
      x	boxRows: 4,
      x animSpeed: 500,
      x	pauseTime: 3000,
      	startSlide: 0,
      x	directionNav: true,
      x	directionNavHide: true,
      x	controlNav: true,
      x	controlNavThumbs: false,
        controlNavThumbsFromRel: false,
      	controlNavThumbsSearch: '.jpg',
      	controlNavThumbsReplace: '_thumb.jpg',
      	keyboardNav: true,
      x	pauseOnHover: true,
      x	manualAdvance: false,
      x	captionOpacity: 0.8,
      	prevText: 'Prev',
      	nextText: 'Next',
      	randomStart: false,
      	beforeChange: function(){},
      	afterChange: function(){},
      	slideshowEnd: function(){},
        lastSlide: function(){},
        afterLoad: function(){}
        
*/
function oik_nivo_slider_options() {
  bw_form( "options.php" );
  $option = "bw_nivo_slider"; 
  $options = get_option( $option );     
  stag( 'table class="form-table"' );
  bw_flush();
  settings_fields('oik_nivo_options');
  $effect_options = array( 'random', 'sliceDownRight','sliceDownLeft','sliceUpRight','sliceUpLeft','sliceUpDown','sliceUpDownLeft','fold','fade',
                'boxRandom','boxRain','boxRainReverse','boxRainGrow','boxRainGrowReverse', 'slideInLeft', 'slideInRight' );
  $effect_options_assoc = bw_assoc( $effect_options );                
                
  bw_select_arr( $option, "Effect (effect=<i>effect</i>)", $options, 'effect', array( "#options" => $effect_options_assoc ) );
  
  bw_textfield_arr( $option, "Slices", $options, 'slices', 2 );
  bw_textfield_arr( $option, "Box cols", $options, 'boxCols', 2 );
  bw_textfield_arr( $option, "Box rows", $options, 'boxRows', 2 );
  bw_textfield_arr( $option, "Anim speed", $options, 'animSpeed', 4 );
  bw_textfield_arr( $option, "Pause time (pause=<i>nnnn</i>)", $options, 'pauseTime', 4 );
  
  bw_checkbox_arr( $option, "Control nav (nav=n|y)", $options, 'controlNav' );
  bw_checkbox_arr( $option, "Control nav thumbs (thumbs=n|y)", $options, 'controlNavThumbs' );
  //bw_textfield_arr( $option, "Control nav thumbs search", $options, 'controlNavThumbsSearch', 20 );
  //bw_textfield_arr( $option, "Control nav thumbs replace", $options, 'controlNavThumbsReplace', 20 );
  bw_checkbox_arr( $option, "Direction nav", $options, 'directionNav' );
  bw_checkbox_arr( $option, "Direction nav hide", $options, 'directionNavHide' );
  bw_checkbox_arr( $option, "Pause on hover", $options, 'pauseOnHover' );
  bw_checkbox_arr( $option, "Manual advance (manual=n|y)", $options, 'manualAdvance' );
  
  bw_textfield_arr( $option, "Caption opacity", $options, 'captionOpacity', 4 );
  
  //bw_tablerow( array( "", "<input type=\"submit\" name=\"ok\" value=\"Save changes\" class=\"button-primary\"/>") ); 
  
  etag( "table" ); 
  e( isubmit( "ok", __( "Save changes", "oik" ), null, "button-primary" ) );
  			
  etag( "form" );
  bw_flush();
}  

/**
 * Display some usage notes for the oik-nivo-slider with an example
 */
function oik_nivo_slider_usage() {
  p("These options for the jQuery Nivo slider control the default behaviour for each instance of the slider." );
  oik_require( "nivo.inc", "oik-nivo-slider" );
  oik_require( "includes/oik-sc-help.inc" );
  bw_flush();
  nivo__example();
  sediv("cleared"); 
}


