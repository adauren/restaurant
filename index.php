<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'vendor/autoload.php';
require 'app/main.php';
