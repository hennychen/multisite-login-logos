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
            ->setMethods( array( "add_section", "add_setting", "add_control", "get_setting" ) )
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

    public function test_add_multisite_login_logos_customizer_should_add_login_logo_settings() {
        $wp_customize = $this->getMockBuilder( "WP_Customize_Manager" )
            ->setMethods( array( "add_section", "add_setting", "add_control", "get_setting" ) )
            ->getMock();

        $wp_customize->expects( $this->at( 1 ) )
            ->method( "add_setting" )
            ->with(
                "multisite_login_logos_settings",
                array(
                    "default" => "1",
                    "type"    => "option",
                )
            );

        $this->multisite_login_logos->add_multisite_login_logos_customizer( $wp_customize );
    }

    public function test_add_multisite_login_logos_customizer_should_add_login_logo_control() {
        $wp_customize = $this->getMockBuilder( "WP_Customize_Manager" )
            ->setMethods( array( "add_section", "add_setting", "add_control", "get_setting" ) )
            ->getMock();

        $wp_customize->expects( $this->at( 2 ) )
            ->method( "add_control" )
            ->with(
                "multisite_login_logos",
                array(
                    "label"    => "Login logo displays",
                    "section"  => "multisite_login_logos_section",
                    "settings" => "multisite_login_logos_settings",
                    "type"     => "radio",
                    "choices"  => array(
                        "1" => "Default network logo",
                        "3" => "Custom logo",
                    ),
                )
            );

        $this->multisite_login_logos->add_multisite_login_logos_customizer( $wp_customize );
    }

    public function test_add_multisite_login_logos_customizer_should_add_login_logo_custom_settings() {
        $wp_customize = $this->getMockBuilder( "WP_Customize_Manager" )
            ->setMethods( array( "add_section", "add_setting", "add_control", "get_setting" ) )
            ->getMock();

        $wp_customize->expects( $this->at( 3 ) )
            ->method( "add_setting" )
            ->with(
                "multisite_login_logos_custom",
                array(
                    "type" => "option",
                )
            );

        $this->multisite_login_logos->add_multisite_login_logos_customizer( $wp_customize );
    }

    public function test_add_multisite_login_logos_customizer_should_add_login_logo_custom_control() {
        $wp_customize = $this->getMockBuilder( "WP_Customize_Manager" )
            ->setMethods( array( "add_section", "add_setting", "add_control", "get_setting" ) )
            ->getMock();

        $image_control_object = new WP_Customize_Image_Control(
            $wp_customize,
            "multisite_login_logos_custom",
            array(
                "label"    => "Custom logo",
                "section"  => "multisite_login_logos_section",
                "settings" => "multisite_login_logos_custom",
            )
        );
        $image_control_object->instance_number = 6;

        $wp_customize->expects( $this->at( 5 ) )
            ->method( "add_control" )
            ->with( $image_control_object );

        $this->multisite_login_logos->add_multisite_login_logos_customizer( $wp_customize );
    }
}
