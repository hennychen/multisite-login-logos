<?php

class Multisite_Login_Logos {
    function __construct() {
        add_action( "customize_register", array( $this, "add_multisite_login_logos_customizer" ), 10, 1 );
    }

    public function add_multisite_login_logos_customizer() {
    }
}
