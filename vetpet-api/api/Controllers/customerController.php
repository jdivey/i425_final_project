<?php
/**
 * Author: Jacob Ivey
 * Date: 7/13/2020
 * File: customerController.php
 * Description:
 */

namespace VetPetAPI\Controllers;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use VetPetAPI\Models\Customer;

class  CustomerController{
    //list all customers
    public function index(Request $request, Response $response, array $args) {
        $results = Customer::getCustomers();
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);

    }

    //view a customer
    public function view(Request $request, Response $response, array $args) {
        $customer_id = $args['customer_id'];
        $results = Customer::getCustomerId($customer_id);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }
}