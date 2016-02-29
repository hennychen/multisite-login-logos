<?php

class Multisite_Login_Logos {
    function __construct() {
        add_action( "customize_register", array( __CLASS__, "add_multisite_login_logos_customizer" ) );
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
    }
}
