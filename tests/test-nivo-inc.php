<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package oik-nivo-slider
 * 
 * Test the functions in nivo.inc
 */
class Tests_nivo_inc extends BW_UnitTestCase {

	/**
	 */
	function setUp() {
		parent::setUp();
		oik_require( "nivo.inc", "oik-nivo-slider" );
		oik_require_lib( "oik-sc-help" );
	}
	
	/**
	 * Tests set_url_scheme returns https:
	 *
	 * Test added for oik v3.2.0-RC1. Shortcode expansion now returns the saved HTML
	 * when the shortcode is unchanged. So the scheme will not change due to changing the 
	 * value of $_SERVER['HTTPS']. To test this properly we'll have to 
	 * the same 
	 */	
	function test_is_ssl() { 		
		$_SERVER['HTTPS'] = 'on';
		$https = bw_array_get( $_SERVER, 'HTTPS', null );
		$this->assertEquals( "on", $https );
		
		$secure = is_ssl();
		$this->assertTrue( $secure );
		$url = WP_PLUGIN_URL;
		//echo $url;
		
		$url = set_url_scheme( $url );
		//echo $url;
		
		$this->assertContains( "https://", $url );
		
		
	}	
	
	
	

	/**
	 * Unit test to demonstrate that the [nivo] shortcode works 
	 * 
<p>Slideshow of the screenshots from oik-nivo-slider, using the oik theme and the current settings. </p>
<p><code>[nivo theme=oik post_type="screenshot:oik-nivo-slider" caption=n link=n]</code></p>
<div>
<div class="slider-wrapper theme-oik">
<div class="ribbon"></div>
<div class="nivoSlider" id="slider-1"><img class="" src="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-1.jpg"  data-thumb="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-1.jpg" /><img class="" src="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-2.jpg"  data-thumb="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-2.jpg"  style="display:none" /><img class="" src="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-3.jpg"  data-thumb="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-3.jpg"  style="display:none" /><img class="" src="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-4.jpg"  data-thumb="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-4.jpg"  style="display:none" /><img class="" src="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-5.jpg"  data-thumb="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-5.jpg"  style="display:none" /><img class="" src="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-6.jpg"  data-thumb="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-6.jpg"  style="display:none" /><img class="" src="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-7.jpg"  data-thumb="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-7.jpg"  style="display:none" /><img class="" src="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-8.jpg"  data-thumb="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-8.jpg"  style="display:none" /><img class="" src="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-9.jpg"  data-thumb="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-9.jpg"  style="display:none" /><img class="" src="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-A.jpg"  data-thumb="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-A.jpg"  style="display:none" /><img class="" src="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-B.jpg"  data-thumb="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-B.jpg"  style="display:none" /><img class="" src="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-C.jpg"  data-thumb="http://qw/oikcom/wp-content/plugins/oik-nivo-slider/screenshot-C.jpg"  style="display:none" /></div>
</div>
</div>
<div class="clear"></div>
	 * 
	 */
	function test_nivo__example() {
	
		$_SERVER['HTTPS'] = null;
		$expected = '<div class="slider-wrapper theme-oik">';
		$expected .= "\n";
		$expected .= '<div class="ribbon"></div>';
		$expected .= "\n";
		$expected .= '<div class="nivoSlider" id="slider-1"><img class=""';
		 
		nivo__example();
		$actual = bw_ret();
		$this->assertContains( $expected, $actual );
		
	}
	
	/**
	 * Note: This doesn't test the fix for specific problem reported
	 * But it's a start.
	 *
	 */
	
	function test_issue_3_image_2_onwards_has_display_none() {
		$atts = array( "post_type" => "screenshot:oik-nivo-slider" 
								 , "caption" => "y" 
								 );
		$actual = bw_nivo_slider( $atts ); 
		//echo $actual;
		$this->assertContains( 'screenshot-1.jpg" />', $actual );
		$this->assertContains( 'screenshot-2.jpg"  style="display:none" />', $actual );
	}
	
	/**
	 * Currently there are 12 screenshots
	 */
	function test_bw_get_spt_screenshot() {
		$urls = bw_get_spt_screenshot( ['post_type' => 'screenshot:oik-nivo-slider' ] );
		$this->assertCount( 12, $urls );
		//print_r( $urls );
	}
	
	/**
	 * Test support for HTTPS protocol
	 */
	function test_no_http_in_output() {
		$_SERVER['HTTPS'] = 'on';
		bw_expand_shortcode();
		nivo__example();
		$actual = bw_ret();
		//echo $actual;
		$this->assertContains( "https://", $actual );
		$this->assertNotContains( "http://", $actual );
	}
	
	
	

}
