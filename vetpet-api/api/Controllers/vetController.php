<?php
/**
 * Author: Jacob Ivey
 * Date: 7/13/2020
 * File: vetController.php
 * Description:
 */

namespace VetPetAPI\Controllers;

use VetPetAPI\Models\Vet;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class  VetController{
    //list all vets
    public function index(Request $request, Response $response, array $args) {
        //get query string variables from url
        $params = $request->getQueryParams();
        $term = array_key_exists('q', $params) ? $params['q'] : null;

        if (!is_null($term)) {
            $results = Vet::searchVets($term);
        }else{
            $results = Vet::getVets();
        }
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);

    }

    //view a vet
    public function view(Request $request, Response $response, array $args) {
        $vet_id = $args['vet_id'];
        $results = Vet::getVetById($vet_id);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //create a vet
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

        //insert a new vet
        $vet = Vet::createVet($request);

        $results = [
            'status' => "Student created",
            'data' => $vet
        ];
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //update a vet
    public function update(Request $request, Response $response, array $args) {
        //validate the request
        $validation = Validator::validateVet($request);

        //if validation failed
        if(!$validation) {
            $results['status'] = "Validation: failed";
            $results['errors'] = Validator::getErrors();
            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }

        $vet = Vet::updateVet($request);
        $status = $vet ? "Vet has been updated" : "Vet cannot be updated";
        $status_code = $vet ? 200 : 500;
        $results['status'] = $status;
        if ($vet) {
            $results['data'] = $vet;
        }
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }

    //method to delete a vet
    public function delete(Request $request, Response $response, array $args) {
        $vet = Vet::deleteVet($request);
        $status = $vet ? "Vet has been deleted" : "Vet cannot be deleted";
        $status_code = $vet ? 200 : 500;
        $results = ['status' => $status];
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }
}