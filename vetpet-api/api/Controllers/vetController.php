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


class  VetController
{
    //list all vets
    public function index(Request $request, Response $response, array $args)
    {
        //get query string variables from url
        $params = $request->getQueryParams();
        $term = array_key_exists('q', $params) ? $params['q'] : null;

        if (!is_null($term)) {
            $results = Vet::searchVets($term);
        } else {
            $results = Vet::getVets();
        }
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);

    }

    //view a vet
    public function view(Request $request, Response $response, array $args)
    {
        $results = Vet::getVetById($args['vet_id']);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

}
