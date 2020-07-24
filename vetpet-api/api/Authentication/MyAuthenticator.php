<?php
/**
 * Author: Jacob Ivey
 * Date: 7/22/2020
 * File: MyAuthenticator.php
 * Description: the MyAuthenticator class authenticates user by username and password in a header
 */

    namespace VetPetAPI\Authentication;

    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;
    use VetPetAPI\Models\User;

    class MyAuthenticator {

        // use the invoke method so the object can be used as a callable.
        //this method gets called automatically when the object is treated as a callable
        public function __invoke(Request $request, Response $response, $next)
        {
            //username and password are stored in a header called "MyCollegeAPI-Authorization".  value of the header
            //is formatted as username:password
            if (!$request->hasHeader('VetPetAPI-Authorization')) {
                $results = ['Status' => 'Authorization header not found.'];
                return $response->withJson($results, 404, JSON_PRETTY_PRINT);
            }

            //retrieve the header and then the username and password
            $auth = $request->getHeader('VetPetAPI-Authorization');
            list($username, $password) = explode(':', $auth[0]);

            //validate username and password
            if(!User::authenticateUser($username, $password)) {
                $results = ['Status' => 'Authentication failed.'];
                return $response->withJson($results, 404, JSON_PRETTY_PRINT);
            }

            //a user has been authenticated
            $response = $next($request, $response);
            return $response;
        }
    }