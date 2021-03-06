<?php
/**
 * Author: Jacob Ivey
 * Date: 7/13/2020
 * File: routes.php
 * Description:
 */

//define app routes
use VetPetAPI\Authentication\BearerAuthenticator;
use VetPetAPI\Authentication\Basic_Authenticator;
use VetPetAPI\Authentication\JWTAuthenticator;
use VetPetAPI\Authentication\MyAuthenticator;

$app->get('/', function ($request, $response, $args) {
    return $response->write('Welcome to VetPet API');
});

$app->get('/api/hello/{name}', function ($request, $response, $args) {
    return $response->write("Hello ". $args['name']);
});

// User routes
$app->group('/api/v1/users', function () {
    $this->get('', 'User:index');
    $this->get('/{id}', 'User:view');
    $this->post('', 'User:create');
    $this->put('/{id}', 'User:update');
    $this->delete('/{id}', 'User:delete');
    $this->post('/authBearer', 'User:authBearer');
    $this->post('/authJWT', 'User:authJWT');

});

//route pet group
$app->group('/api/v1', function () {
    //the pet group
    $this->group('/pets', function () {
        $this->get('', "Pet:index");
        $this->get('/{pet_id}', 'Pet:view');
       $this->get('/{pet_id}/vets/', 'Pet:viewVets');

    });

    //the customer group
    $this->group('/customers', function () {
        $this->get('', 'Customer:index');
        $this->get('/{customer_id}', 'Customer:view');
        $this->get('/{customer_id}/pets', 'Customer:viewPets');
        $this->get('/{customer_id}/pets/{pet_id}', 'Pet:view');

    });

    //the vet group
    $this->group('/vets', function () {
        $this->get('', 'Vet:index');
        $this->get('/{vet_id}', 'Vet:view');
    });

    //the appointment group
    $this->group('/appointments', function () {
        $this->get('', 'Appointment:index');
        $this->get('/{appointment_id}', 'Appointment:view');
        $this->post('', 'Appointment:create');
        $this->put('/{appointment_id}', 'Appointment:update');
        $this->delete('/{appointment_id}', 'Appointment:delete');
    });
//})->add(new MyAuthenticator()); //My Authenticator method
//})->add(new Basic_Authenticator()); //Basic Authenticator method
//})->add(new BearerAuthenticator()); //BearerAuthenticator method
})->add(new JWTAuthenticator()); //JWTAuthenticator method
