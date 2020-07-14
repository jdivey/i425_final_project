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
        $results = Vet::getVets();
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);

    }

    //view a vet
    public function view(Request $request, Response $response, array $args) {
        $vet_id = $args['vet_id'];
        $results = Vet::getPetById($vet_id);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }
}