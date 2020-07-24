<?php
/**
 * Author: Jacob Ivey
 * Date: 7/22/2020
 * File: Basic_Authenticator.php
 * Description: the basic authenticator class
 */

namespace VetPetAPI\Authentication;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use VetPetAPI\Models\User;

class Basic_Authenticator {
    public function __invoke(Request $request, Response $response, $next)
    {
        //the username and password are sent in a header called authorization in the format
        //Basic: username:password username and password are encoded
        if (!$request->hasHeader('Authorization')) {
            $results = ['Status' => 'Authorization header not found.'];
            return $response->withJson($results, 404, JSON_PRETTY_PRINT);
        }

        //Retrieve the header and the apikey consisting of username:password encoded
        $auth = $request->getHeader('Authorization');

        $apikey = substr($auth[0], strpos($auth[0], ' ') + 1);

        //get the username and the password
        list($username, $password) = explode( ':', base64_decode($apikey));

        //Authenticate the user
        if(!user::authenticateUser($username, $password)) {
            $results = ['Status' => 'Authentication failed.'];
            return $response->withHeader('www-Authenticate', 'Basic realn="VetPetAPI API"')
                ->withJson($results, 401, JSON_PRETTY_PRINT);
        }

        //a user has been authenticated
        $response = $next($request, $response);
        return $response;
    }
}