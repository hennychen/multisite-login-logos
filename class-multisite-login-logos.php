<?php

class Multisite_Login_Logos {
    function __construct() {
        add_action( "customize_register", array( __CLASS__, "add_multisite_login_logos_customizer" ), 10, 1 );
        add_action( "login_head", array( __CLASS__, "display_multisite_login_logo" ), 99, 0 );
    }

    public function add_multisite_login_logos_customizer( $wp_customize ) {
        $wp_customize->add_section( "multisite_login_logos_section", array(
            "title"    => "Login Logo",
            "priority" => 55,
        ) );

        $wp_customize->add_setting( "multisite_login_logos_settings", array(
            "default" => "1",
            "type"    => "option",
        ) );

        $wp_customize->add_control( "multisite_login_logos", array(
            "label"    => "Login logo displays",
            "section"  => "multisite_login_logos_section",
            "settings" => "multisite_login_logos_settings",
            "type"     => "radio",
            "choices"  => array(
                "1" => "Default network logo",
                "3" => "Custom logo",
            ),
        ) );

        $wp_customize->add_setting( "multisite_login_logos_custom", array(
            "type" => "option",
        ) );

        $wp_customize->add_control(
            new WP_Customize_Image_Control( $wp_customize, "multisite_login_logos_custom", array(
                "label"    => "Custom logo",
                "section"  => "multisite_login_logos_section",
                "settings" => "multisite_login_logos_custom",
            ) )
        );
    }

    function display_multisite_login_logo() {
        $multisite_login_logos_settings = get_option( "multisite_login_logos_settings" );
        if ( "3" == $multisite_login_logos_settings ) {
            $logo = get_option( "multisite_login_logos_custom" );
            if ( true == is_ssl() ) {
                $logo = str_replace( "http://", "https://", $logo );
            }

            try {
                $sizes = getimagesize( $logo );
            }
            catch ( Exception $e ) {
                $sizes = array( 0, 0 );
                error_log( "Caught exception: " . $e->getMessage() );
            }

            echo "<style type=\"text/css\">\n";
            echo ".login h1 a {\n";
            echo "background-image: url(" . $logo . ");\n";
            echo "width: 100%;\n";
            echo "height: " . $sizes[1] . "px;\n";
            echo "}\n";
            echo "</style>\n";
        }
    }
}
