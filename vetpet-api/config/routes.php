<?php
/**
 * Author: Jacob Ivey
 * Date: 7/13/2020
 * File: routes.php
 * Description:
 */

//define app routes
$app->get('/', function ($request, $response, $args) {
    return $response->write('Welcome to VetPet API');
});

$app->get('/api/hello/{name}', function ($request, $response, $args) {
    return $response->write("Hello ". $args['name']);
});

//route pet group
$app->group('/api/v1', function () {
    //the pet group
    $this->group('/pets', function () {
        $this->get('', "Pet:index");
        $this->get('/{pet_id}', 'Pet:view');
    });

    //the customer group
    $this->group('/customers', function () {
        $this->get('', 'Customer:index');
        $this->get('/{customer_id}', 'Customer:view');
    });

    //the vet group
    $this->group('/vets', function () {
        $this->get('', 'Vet:index');
        $this->get('/{vet_id}', 'Vet:view');
        $this->post('', 'Vet:create');
        $this->put('/{vet_id}', 'Vet:update');
        $this->delete('/{vet_id}', 'Vet:delete');
    });

    //the appointment group
    $this->group('/appointments', function () {
        $this->get('', 'Appointment:index');
        $this->get('/{appointment_id}', 'Appointment:view');
    });
});