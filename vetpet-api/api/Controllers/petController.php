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
        //get query string variables from url
        $params = $request->getQueryParams();
        $term = array_key_exists('q', $params) ? $params['q'] : null;

        if (!is_null($term)) {
            $results = Pet::searchPets($term);
        }else{
            $results = Pet::getPets();
        }
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);

    }

    //view a pet
    public function view(Request $request, Response $response, array $args) {
        $pet_id = $args['pet_id'];
        $results = Pet::getPetById($pet_id);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //create a pet
    public function create(Request $request, Response $response, array $args) {
        //validate the request
        $validation = Validator::validateVet($request);

        if (!$validation) {
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors()
            ];

            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }

        //insert a new pet
        $pet = Pet::createPet($request);

        $results = [
            'status' => "Student created",
            'data' => $pet
        ];
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //update a pet
    public function update(Request $request, Response $response, array $args) {
        //validate the request
        $validation = Validator::validatePet($request);

        //if validation failed
        if(!$validation) {
            $results['status'] = "Validation: failed";
            $results['errors'] = Validator::getErrors();
            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }

        $pet = Pet::updatePet($request);
        $status = $pet ? "Pet has been updated" : "Pet cannot be updated";
        $status_code = $pet ? 200 : 500;
        $results['status'] = $status;
        if ($pet) {
            $results['data'] = $pet;
        }
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }

    //method to delete a pet
    public function delete(Request $request, Response $response, array $args) {
        $pet = Pet::deletePet($request);
        $status = $pet ? "Pet has been deleted" : "Pet cannot be deleted";
        $status_code = $pet ? 200 : 500;
        $results = ['status' => $status];
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }
}