<?php
/**
 * Author: Jacob Ivey
 * Date: 7/23/2020
 * File: OAuth2Authenticator.php
 * Description: the OAuth2 authenticator class
 */

namespace VetPetAPI\Authentication;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use VetPetAPI\Models\User;

class OAuth2Authenticator {
    public function __invoke(Request $request, Response $response, $next) {
        //if the header named Authorization does not exist then returns an error
        if (!$request->hasHeader('Authorization')) {
            $results = ['Status' => 'Authorization header not available.'];
            return $response->withJson($results, 404, JSON_PRETTY_PRINT);
        }

        //retrieve the header and the token
        $auth = $request->getHeader('Authorization');
        $token =  substr($auth[0], strpos($auth[0], ' ') + 1);

        //validate the token, if it is invalid, return an error
        if (!User::validateOauth2($token)) {
            return $response->withJson(['Status' => 'Authentication failed.'], 401, JSON_PRETTY_PRINT);
        }

        //a user has been authenticated
        $response = $next($request, $response);
        return $response;
    }
}