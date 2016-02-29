<?php

require_once( "multisite-login-logos.php" );

class Multisite_Login_Logos_Test extends WP_UnitTestCase {
    private $multisite_login_logos;

    public function setUp() {
        parent::setUp();

        $this->multisite_login_logos = new Multisite_Login_Logos();
    }

    public function test_add_multisite_login_logos_customizer_is_registered_to_customize_register() {
        global $wp_filter;

        $expected = "add_multisite_login_logos_customizer";
        $key      = array_keys( $wp_filter["customize_register"][10] );
        $this->assertContains( $expected, $key[0] );

        $expected = "add_multisite_login_logos_customizer";
        $function = $wp_filter["customize_register"][10][ $key[0] ]["function"];
        $this->assertEquals( $expected, $function[1] );

        $expected      = 1;
        $accepted_args = $wp_filter["customize_register"][10][ $key[0] ]["accepted_args"];
        $this->assertEquals( $expected, $accepted_args );
    }

    public function test_add_multisite_login_logos_customizer_should_add_login_logo_section() {
        $wp_customize = $this->getMockBuilder( "WP_Customize_Manager" )
            ->setMethods( array( "add_section" ) )
            ->getMock();

        $wp_customize->expects( $this->once() )
            ->method( "add_section" )
            ->with(
                "multisite_login_logos_section",
                array(
                    "title"    => "Login Logo",
                    "priority" => 55,
                )
            );

        $this->multisite_login_logos->add_multisite_login_logos_customizer( $wp_customize );
    }
}
