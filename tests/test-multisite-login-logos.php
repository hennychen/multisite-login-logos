<?php

require_once( "multisite-login-logos.php" );

class Multisite_Login_Logos_Test extends WP_UnitTestCase {
    public function setUp() {
        parent::setUp();
    }

    public function test_add_multisite_login_logos_customizer_is_registered_to_customize_register() {
        global $wp_filter;

        $expected = "add_multisite_login_logos_customizer";
        $key      = array_keys( $wp_filter["customize_register"][10] )[0];
        $this->assertContains( $expected, $key );

        $expected = "add_multisite_login_logos_customizer";
        $function = $wp_filter["customize_register"][10][ $key ]["function"];
        $this->assertEquals( $expected, $function[1] );

        $expected      = 1;
        $accepted_args = $wp_filter["customize_register"][10][ $key ]["accepted_args"];
        $this->assertEquals( $expected, $accepted_args );
    }
}
