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

        $expected = "Multisite_Login_Logos::add_multisite_login_logos_customizer";
        $key      = array_keys( $wp_filter["customize_register"][10] );
        $this->assertEquals( $expected, $key[0] );

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

    public function test_display_multisite_login_logo_is_registered_to_login_head() {
        global $wp_filter;

        $expected = "display_multisite_login_logo";
        $key      = array_keys( $wp_filter["login_head"][99] );
        $this->assertContains( $expected, $key[1] );

        $expected = "display_multisite_login_logo";
        $function = $wp_filter["login_head"][99][ $key[1] ]["function"];
        $this->assertEquals( $expected, $function[1] );

        $expected      = 0;
        $accepted_args = $wp_filter["login_head"][99][ $key[1] ]["accepted_args"];
        $this->assertEquals( $expected, $accepted_args );
    }

    public function test_display_multisite_login_logo_should_show_nothing_if_setting_is_default_logo() {
        update_option( "multisite_login_logos_settings", "1" );

        $expected = "";

        ob_start();
        $this->multisite_login_logos->display_multisite_login_logo();
        $actual = ob_get_clean();

        $this->assertEquals( $expected, $actual );
    }

    public function test_display_multisite_login_logo_should_show_custom_logo_if_setting_is_custom_logo_logo() {
        update_option( "multisite_login_logos_settings", "3" );
        update_option( "multisite_login_logos_custom", "tests/login-logo.png" );

        $expected  = "<style type=\"text/css\">\n";
        $expected .= ".login h1 a {\n";
        $expected .= "background-image: url(tests/login-logo.png);\n";
        $expected .= "width: 100%;\n";
        $expected .= "height: 74px;\n";
        $expected .= "}\n";
        $expected .= "</style>\n";

        ob_start();
        $this->multisite_login_logos->display_multisite_login_logo();
        $actual = ob_get_clean();

        $this->assertEquals( $expected, $actual );
    }

    public function test_display_multisite_login_logo_should_use_https_if_ssl_is_enabled() {
        $_SERVER["HTTPS"] = "on";

        update_option( "multisite_login_logos_settings", "3" );
        update_option( "multisite_login_logos_custom", "http://local.bypronto.dev/wp-content/uploads/2016/03/51323de85a313e11.png" );

        $expected  = "<style type=\"text/css\">\n";
        $expected .= ".login h1 a {\n";
        $expected .= "background-image: url(https://local.bypronto.dev/wp-content/uploads/2016/03/51323de85a313e11.png);\n";
        $expected .= "width: 100%;\n";
        $expected .= "height: 0px;\n";
        $expected .= "}\n";
        $expected .= "</style>\n";

        ob_start();
        $this->multisite_login_logos->display_multisite_login_logo();
        $actual = ob_get_clean();

        $this->assertEquals( $expected, $actual );

        $_SERVER["HTTPS"] = null;
    }
}
