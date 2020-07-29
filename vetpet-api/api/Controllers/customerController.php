<?php
/**
 * Author: Jacob Ivey
 * Date: 7/13/2020
 * File: customerController.php
 * Description:
 */

namespace VetPetAPI\Controllers;

use VetPetAPI\Validation\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use VetPetAPI\Models\Customer;


class  CustomerController{
    //list all customers
    public function index(Request $request, Response $response, array $args) {
        //get query string variables from url
        $params = $request->getQueryParams();
        $term = array_key_exists('q', $params) ? $params['q'] : null;

        if (!is_null($term)) {
            $results = Customer::searchCustomers($term);
        }else{
            $results = Customer::getCustomers($request);
        }
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //view a customer
    public function view(Request $request, Response $response, array $args) {
        $customer_id = $args['customer_id'];
        $results = Customer::getCustomerId($customer_id);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }


    //create a customer
    public function create(Request $request, Response $response, array $args) {
        //validate the request
        $validation = Validator::validateCustomer($request);

        if (!$validation) {
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors()
            ];

            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }

        //insert a new customer
        $customer = Customer::createCustomer($request);

        $results = [
            'status' => "Customer created",
            'data' => $customer
        ];
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //update a customer
    public function update(Request $request, Response $response, array $args) {
        //validate the request
        $validation = Validator::validateCustomer($request);

        //if validation failed
        if(!$validation) {
            $results['status'] = "Validation: failed";
            $results['errors'] = Validator::getErrors();
            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }

        $customer = Customer::updateCustomer($request);
        $status = $customer ? "Customer has been updated" : "Customer cannot be updated";
        $status_code = $customer ? 200 : 500;
        $results['status'] = $status;
        if ($customer) {
            $results['data'] = $customer;
        }
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }

    //method to delete a customer
    public function delete(Request $request, Response $response, array $args) {
        $customer = Customer::deleteCustomer($request);
        $status = $customer ? "Customer has been deleted" : "Customer cannot be deleted";
        $status_code = $customer ? 200 : 500;
        $results = ['status' => $status];
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }
}
