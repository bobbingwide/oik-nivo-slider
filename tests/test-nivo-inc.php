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
	
		global $bw_slider_count; 
		$bw_slider_count = null;
	
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
	
	
	/**
	 * For i18n we need to test bw_format_nivo with and without the link
	 * This partially tests bw_thumbnail()
	 *
	 * Note: Every time this test is performed another screenshot-1 file and all its variations is created in uploads
	 * We should delete these file afterwards.
	 *
	 * No need to 'properly' switch_to_locale here as we don't expect anything to be translated.
	 *
	 * The generated HTML needs to be sanitized 
	 * `<img class="bw_thumbnail post-33566 page type-page status-publish hentry entry has-post-thumbnail" 
	 * src="https://qw/wordpress/wp-content/uploads/2017/11/screenshot-1.jpg" title="post title 1" 
	 * alt="post title 1"  data-thumb="https://qw/wordpress/wp-content/uploads/2017/11/screenshot-1.jpg" />
	 * `
	 */
	function test_bw_format_nivo() {
		$this->switch_to_locale( "en_GB" );
		$post = $this->dummy_post( 1 );
		$attachment = $this->dummy_attachment( $post->ID );
		bw_format_nivo( $post, array( "caption" => "y", "link" => "n" ) );
		br(); 
		bw_format_nivo( $post, array( "caption" => "y", "link" => "y" ) );
		$html = bw_ret();
		$html = str_replace( $attachment->guid, "screenshot-1.jpg", $html );
		$html = $this->strip_post_classes( $html, $post->ID );
		$html = $this->replace_post_id( $html, $post, "post-" );
		$html = $this->replace_home_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->delete_uploaded_files( $attachment );
		$this->switch_to_locale( "en_GB" );
	
	}
	
	function strip_post_classes( $html, $post_id ) {
		//echo $html;
		//echo PHP_EOL;
    $classes = bw_get_post_class( $post_id );
		//echo "Classes" . PHP_EOL;
		//echo $classes;
		//echo "!";
		$stripped = str_replace( $classes, "post-42 page type-page status-publish hentry entry has-post-thumbnail", $html );
		$this->assertNotEquals( $html, $stripped );
		return $stripped;
		
	
	}
	
	/**
	 * Non generic routine to delete the uploaded files
	 *
	 * @TODO Improve logic to work with specific upload files
	 */
	function delete_uploaded_files( $attachment ) {
		//print_r( $attachment );
		$dir = wp_upload_dir();
		$path = bw_array_get( $dir, "path", null );
		$this->assertNotNull( $path );
		$files = glob( $path . '/screenshot-1*.jpg' );
		if ( $files ) {
			array_map( "unlink", $files );
		}
		// $files = glob( $path . '/screenshot-1*.jpg' );
	}
	
	
	/**
	 * Create a dummy page 
	 */
	function dummy_post( $n ) {
		$args = array( 'post_type' => 'page', 'post_title' => "post title $n", 'post_excerpt' => 'Excerpt. No post ID' );
		$id = self::factory()->post->create( $args );
		$post = get_post( $id );
		return $post;
	}
	
	/**
	 * Create a dummy attachment
	 */
	function dummy_attachment( $parent ) {
		$args = array( 'post_type' => 'attachment'
								 , 'post_parent' => $parent
								 , 'post_content' => 'attachment content'
								 , 'file' => oik_path( '!.png' )
								 , 'post_title' => ' !'
								 );
		$id = self::factory()->attachment->create_upload_object( oik_path( "screenshot-1.jpg", "oik-nivo-slider" ), $parent );
		$this->assertGreaterThan( 0, $id );
		$post = get_post( $id );
		//print_r( $post );
		return $post;
	}
	
	/**
	 * We need to test the different values for "link" and ensure that "count" and "slider-id" are also set appropriately to avoid warnings.
	 */
	function test_bw_format_nivo_attachment() {
		$this->switch_to_locale( "en_GB" );
		$post = $this->dummy_post( 1 );
		$attachment = $this->dummy_attachment( $post->ID );
		bw_format_nivo_attachment( $attachment, array( "caption" => "n", "link" => "y", "count" => 0, "slider-id" => 1 ) );
		bw_format_nivo_attachment( $attachment, array( "caption" => "n", "link" => "file", "count" => 1, "slider-id" => 2 ) );
		bw_format_nivo_attachment( $attachment, array( "caption" => "n", "link" => "full", "count" => 2, "slider-id" => 3  ) );
		bw_format_nivo_attachment( $attachment, array( "caption" => "n", "link" => "n", "count" => 3, "slider-id" => 4 ) );
		$html = bw_ret();
		$html = str_replace( $attachment->guid, "screenshot-1.jpg", $html );
		$html = $this->replace_home_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->delete_uploaded_files( $attachment );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_nivo__help() {
    $this->switch_to_locale( "en_GB" );
		$html = nivo__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_nivo__help_bb_BB() {
    $this->switch_to_locale( "bb_BB" );
		$html = nivo__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
    $this->switch_to_locale( "en_GB" );
	}
	
	
	function test_nivo__syntax() {
		update_option( "posts_per_page", 10 );
		$array = nivo__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_nivo__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		update_option( "posts_per_page", 10 );
		$array = nivo__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * Test what's echo'd when using an unsupported special post type
	 */
	function test_bw_get_special_post_type_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$posts = bw_get_special_post_type( array( "post_type" => "unsupported:oik-nivo-slider" ) );
		$this->assertNull( $posts );
		$html = bw_ret(); 
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * This test is for when NextGEN is NOT active!
	 */ 
	function test_bw_get_spt_nggallery_bb_BB() {
		$exists = class_exists( "nggdb" );
		$this->assertFalse( $exists );
	
		$this->switch_to_locale( "bb_BB" );
		$images = bw_get_spt_nggallery( array( "post_type" => "nggallery:oik-nivo-slider" ) );
		$this->assertNull( $images );
		$html = bw_ret(); 
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * Reloads the text domains
	 * 
	 * - Loading the 'oik-libs' text domain from the oik-libs plugin invalidates tests where the plugin is delivered from WordPress.org so oik-libs won't exist.
	 * - but we do need to reload oik's and oik-nivo-slider's text domains
	 * - and cause the null domain to be rebuilt.
	 */
	function reload_domains() {
		$domains = array( "oik", "oik-nivo-slider" );
		foreach ( $domains as $domain ) {
			$loaded = bw_load_plugin_textdomain( $domain );
			$this->assertTrue( $loaded, "$domain not loaded" );
		}
		oik_require_lib( "oik-l10n" );
		oik_l10n_enable_jti();
	}

}
