# Multisite Login Logos

Easily change the logo on the WP login screen using WordPress's Customize settings. Choose between the default network logo or any other image you upload.

Developer Guide
---------------

To run, test, and develop the Multisite Login Logos plugin with Docker container, please simply follow these steps:

1. Build the container:

  `$ docker build -t wptest .`
 
2. Test running the PHPUnit on this plugin:

  `$ docker run -it -v $(pwd):/app wptest /bin/bash -c "service mysql start && phpunit"`

Changelog
----------

= 1.0.1 =
- Support SSL if it is enabled.

= 1.0.0 =
- Set custom logo on login page in Theme Customizer.
