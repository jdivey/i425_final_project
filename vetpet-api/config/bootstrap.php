<?php
/**
 * Author: Jacob Ivey
 * Date: 7/13/2020
 * File: bootstrap.php
 * Description:
 */

//load system configuration settings
$config = require __DIR__. '/config.php';

//load composer autoload
require __DIR__. '/../vendor/autoload.php';

//prepare the app
$app = new\Slim\App(['settings' => $config]);

//add dependencies to container
require __DIR__. '/dependencies.php';

//load the service factory
require __DIR__.'/services.php';

//container routes
require __DIR__. '/routes.php';