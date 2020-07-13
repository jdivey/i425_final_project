<?php
/**
 * Author: Jacob Ivey
 * Date: 7/13/2020
 * File: config.php
 * Description:
 */

return [
    //display error details in the development environment
    'displayErrorDetails' => true,
    //database connection details
    'db' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'vetpet_db',
        'username' => 'phpuser',
        'password' => 'phpuser',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => ''
    ]
];