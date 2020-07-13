<?php
/**
 * Author: Jacob Ivey
 * Date: 7/9/2020
 * File: courseController.php
 * Description:
 */

namespace VetPetAPI\Controllers;

use VetPetAPI\Models\Pet;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class  PetController{
    //list all pets
    public function index(Request $request, Response $response, array $args) {
        $results = Pet::getPets();
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);

    }

    //view a pet
    public function view(Request $request, Response $response, array $args) {
        $pet_id = $args['pet_id'];
        $results = Pet::getPetNumber($pet_id);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }
}