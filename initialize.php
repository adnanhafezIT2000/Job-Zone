<?php

    include 'connectDB.php';

    // Routes المسارات

        $css    = 'layout/css/';
        $font   = 'layout/Font Awesome Files/font-awesome/';
        $js     = 'layout/js/';
        $img    = 'layout/images/';

        $func   = 'includes/functions/';
        $tmp    = 'includes/templates/';

    // include Functions
        include $func . 'functions.php';
        

    // include (Header.php)
        include $tmp . 'Header.php';

    // include (homeNavbar.php)
        if( isset($homePage) ){

            include $tmp . 'homeNavbar.php';
        }