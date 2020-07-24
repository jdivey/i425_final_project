<?php
/**
 * Author: Jacob Ivey
 * Date: 7/13/2020
 * File: services.php
 * Description:
 */

use VetPetAPI\Controllers\PetController;
use VetPetAPI\Controllers\CustomerController;
use VetPetAPI\Controllers\UserController;
use VetPetAPI\Controllers\VetController;
use VetPetAPI\Controllers\Appointment_statusController;

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

$container['Appointment'] = function ($c) {
    return new Appointment_statusController();
};

$container['User'] = function ($c) {
    return new UserController();
};