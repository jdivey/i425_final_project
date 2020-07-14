<?php
/**
 * Author: Jacob Ivey
 * Date: 7/13/2020
 * File: services.php
 * Description:
 */

use VetPetAPI\Controllers\PetController;
use VetPetAPI\Controllers\CustomerController;
use VetPetAPI\Controllers\VetController;

//register controllers with the DIC
$container['Pet'] = function ($c) {
    return new PetController();
};

$container['Customer'] = function ($c) {
    return new CustomerController();
};

$container['Vet'] = function ($c) {
    return new VetController();
};