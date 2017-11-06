<?php // (C) Copyright Bobbing Wide 2017


/**
 * @package oik-nivo-slider
 * 
 * Test the functions in admin/oik-nivo-slider.php
 */
class Tests_admin_oik_nivo_slider extends BW_UnitTestCase {

	/**
	 */
	function setUp() {
		parent::setUp();
		//oik_require( "admin/oik-nivo-slider.php", "oik-nivo-slider" );
		//oik_require_lib( "oik-sc-help" );
	}
	
	/**
	 * Tests translation for oik-nivo-slider's admin menu 
	 * this tests oik_nivo_lazy_admin_menu
	 * 
	 * - How do we test that the setting has been registered?
	 * It's probably good enough to ensure the array key exists.
	 This test printed output: Array
(
    [type] => string
    [group] => oik_nivo_options
    [description] =>
    [sanitize_callback] => oik_plugins_validate
    [show_in_rest] =>
)
	 * - How do we test that the submenu page has been added?
	 * - How do we test the authority?
	 */
	function test_oik_nivo_admin_menu() {
		wp_set_current_user( 1 );
		$this->unset_submenu();
		$this->switch_to_locale( 'en_GB' );
		oik_nivo_admin_menu();
		$registered = get_registered_settings();
		$this->assertArrayHasKey( 'bw_nivo_slider', $registered );
		global $submenu;
		$html = $this->arraytohtml( $submenu );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	
	/**
	 * Just in time translation only works for plugins delivered from wordpress.org
	 * or plugins that deliver their .mo files to languages/plugins
	 *
	 * This is because get_translations_for_domain 
	 * calls  _load_textdomain_just_in_time( $domain ) 
	 * calls _get_path_to_translation( $domain );
	 * calls _get_path_to_translation_from_lang_dir
	 * which only looks in languages/plugins.
	 * 
	 * languages/plugins | plugin/languages |	File loaded?
	 * ----------------- | ---------------- | ------------- 
	 *                   |									| None
	 *                   | domain-la_CY.a   | None
	 * domain-la_CY.a    |                  | .a
	 * domain-la_CY.a    | domain-la_CY.b  	| .a 	 
	 *
	 * Consider .mo files under wp-content where  .a and .b indicate different versions of the .mo file
	 * - The files under languages/plugins are the ones managed by wordpress.org
	 * - The files in the languages directory for the plugin are delivered by the plugin.
	 * - For the .b file to be loaded from plugin/languages we need to 
	 * - ensure the files are not in languages/plugins and to call bw_load_plugin_textdomain
	 * - We achieve half of this by overriding the reload_domains method
	 */
	function test_translate_bb_BB() {
		$this->switch_to_locale( 'bb_BB' );
		
		//$translations = get_translations_for_domain( "oik-nivo-slider" );
		//print_r( $translations );
    $text = translate( "Nivo settings", "oik-nivo-slider" );
		$this->assertEquals( "Nvio steitgns", $text );
		$text = translate( "oik nivo slider settings", "oik-nivo-slider" );
		$this->assertEquals( "OIk nvio siledr steitgns", $text );
	}
	
	
	function test_oik_nivo_admin_menu_bb_BB() {
		wp_set_current_user( 1 );
		$this->unset_submenu();
		$this->switch_to_locale( 'bb_BB' );
		global $submenu;
		//print_r( $submenu );
		oik_nivo_admin_menu();
		$registered = get_registered_settings();
		$this->assertArrayHasKey( 'bw_nivo_slider', $registered );
		
		$html = $this->arraytohtml( $submenu );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( 'en_GB' );
	}
	
	function unset_submenu() {	
		unset( $GLOBALS['submenu'] );
		//print_r( $GLOBALS['submenu'] );
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
	
	/**
	 * Tests oik_nivo_options_do_page
	 * which assumes admin/oik-nivo-slider.php has been loaded
	 *
	 * Note: For environment dependence we'll need to update the settings.
	 */
	function test_oik_nivo_options_do_page() {
		$this->switch_to_locale( 'en_GB' );
		ob_start(); 
		oik_nivo_options_do_page();
		$html = ob_get_contents();
		ob_end_clean();
		$this->assertNotNull( $html );
		$html = $this->replace_admin_url( $html );
		$html = $this->replace_home_url( $html );
		$html_array = $this->tag_break( $html );
		$this->assertNotNull( $html_array );
		$html_array = $this->replace_nonce_with_nonsense( $html_array );
		$html_array = $this->replace_nonce_with_nonsense( $html_array, "closedpostboxesnonce", "closedpostboxesnonce" );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	
	/**
	 * Tests oik_nivo_options_do_page
	 * which assumes admin/oik-nivo-slider.php has been loaded
	 */
	function test_oik_nivo_options_do_page_bb_BB() {
		$this->switch_to_locale( 'bb_BB' );
		ob_start(); 
		oik_nivo_options_do_page();
		$html = ob_get_contents();
		ob_end_clean();
		$this->assertNotNull( $html );
		$html = $this->replace_admin_url( $html );
		$html = $this->replace_home_url( $html );
		$html_array = $this->tag_break( $html );
		$this->assertNotNull( $html_array );
		$html_array = $this->replace_nonce_with_nonsense( $html_array );
		$html_array = $this->replace_nonce_with_nonsense( $html_array, "closedpostboxesnonce", "closedpostboxesnonce" );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	
	
}
	
