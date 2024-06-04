<?php

namespace NGFramer\NGFramerPHPBase;

// Set the display error property to E_ALL when in development.
if (APPMODE == 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);
}