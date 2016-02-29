<?php

/*
 * Plugin Name: Multisite Login Logos
 * Plugin URI: https://github.com/prontotools/multisite-logoin-logos
 * Description: Easily change the logo on the WP login screen using WordPress's Customize settings. Choose between the default network logo, your site's logo, or any other image you upload.
 * Author: Pronto Tools
 * Author URI: http://www.prontotools.io
 */

require_once( "class-multisite-login-logos.php" );

new Multisite_Login_Logos();
