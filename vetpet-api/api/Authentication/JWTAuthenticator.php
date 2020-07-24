<?php
/**
 * Author: Jacob Ivey
 * Date: 7/22/2020
 * File: JWTAuthenticator.php
 * Description: the JWT Authenticator class
 */

namespace VetPetAPI\Authentication;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use VetPetAPI\Models\User;

class JWTAuthenticator {
    public function __invoke(Request $request, Response $response, $next) {
        //if the header named Authorization does not exist then returns an error
        if (!$request->hasHeader('Authorization')) {
            $results = ['Status' => 'Authorization header not available.'];
            return $response->withJson($results, 404, JSON_PRETTY_PRINT);
        }

        //retrieve the header and the token
        $auth = $request->getHeader('Authorization');
        $token =  substr($auth[0], strpos($auth[0], ' ') + 1);

        //validate token
        if (!User::validateJWT($token)) {
            return $response->withJson(['Status' => 'Authentication failed.'], 401, JSON_PRETTY_PRINT);
        }

        //a user has been authenticated
        $response = $next($request, $response);
        return $response;
    }
}