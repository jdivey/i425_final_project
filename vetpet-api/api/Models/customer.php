<?php
/**
 * Author: Jacob Ivey
 * Date: 7/13/2020
 * File: customer.php
 * Description:
 */

namespace VetPetAPI\Models;

use Illuminate\Database\Eloquent\Model;


class customer extends Model
{
    //the table associated with this model
    protected $table = "customers";

    //the primary key of the table
    protected $primaryKey = "customer_id";

    //the key is non-numeric
    public $implementing = false;

    //if primary key is not an integer, set its type
    protected $keyType = "char";

    //if the created at and updated at columns are not used
    public $timestamps = false;

    //retrieve all customers
    public static function getCustomers($request) {
        //Get total number of row count
        $count = self::count();

        //Get querystring variables from url
        $params = $request->getQueryParams();

        //Do Limit and Offset exist?
        $limit = array_key_exists('limit', $params) ? (int)$params['limit'] : 10; //Items per page
        $offset = array_key_exists('offset', $params) ? (int)$params['offset'] : 0; //Offset of the first item

        //Pagination
        $links = self::getLinks($request, $limit, $offset);

        //Build query
        $query = self::skip($offset)->take($limit);  //Limit the number of rows
        $customers = $query->get();  // Finally, run the query and get the results

        //Construct the data for response
        $results = [
            'totalCount' => $count,
            'limit' => $limit,
            'offset' => $offset,
            'links' => $links,
            'data' => $customers
        ];

        return $results;
    }

    //view a specific customer by id
    public static function getCustomerId($customer_id) {
        $customer = self::findOrfail($customer_id);
        return $customer;
    }

    //search for a customer
    public static function searchCustomers($term) {
        if (is_numeric($term)) {
            $query = self::where('gpa', '>=', $term);
        }else{
            $query = self::where('customer_id', 'like', "%$term%")
                ->orWhere('first_name', 'like', "%$term%")
                ->orWhere('last_name', 'like', "%$term%");
        }

        return $query->get();
    }

    // This function returns an array of links for pagination. The array includes links for the current, first, next, and last pages.
    private static function getLinks($request, $limit, $offset) {
        $count = self::count();

        // Get request uri and parts
        $uri = $request->getUri();
        $base_url = $uri->getBaseUrl();
        $path = $uri->getPath();

        // Construct links for pagination
        $links = array();
        $links[] = ['rel' => 'self', 'href' => $base_url . "/" . $path . "?limit=$limit&offset=$offset"];
        $links[] = ['rel' => 'first', 'href' => $base_url . "/" . $path . "?limit=$limit&offset=0"];
        if ($offset - $limit >= 0) {
            $links[] = ['rel' => 'prev', 'href' => $base_url . "/" . $path . "?limit=$limit&offset=" . ($offset - $limit)];
        }
        if ($offset + $limit < $count) {
            $links[] = ['rel' => 'next', 'href' => $base_url . "/" . $path . "?limit=$limit&offset=" . ($offset + $limit)];
        }
        $links[] = ['rel' => 'last', 'href' => $base_url . "/" . $path . "?limit=$limit&offset=" . $limit * (ceil($count / $limit) - 1)];

        return $links;
    }

    //insert new customer
    public static function createCustomer($request) {
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //create a new customer instance
        $customer = new Customer();

        //set the customer's attributes
        foreach ($params as $field => $value) {
            //echo $field, ':', $value;
            $customer->$field = $value;
        }

        //insert the customer into the database
        $customer->save();

        return $customer;
    }

    //update a customer
    public static function updateCustomer($request) {
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //retrieve id from the request body
        $customer_id = $request->getAttribute('customer_id');
        $customer = self::find($customer_id);
        if (!$customer) {
            return false;
        }

        //update attributes of the customer
        foreach ($params as $field => $value) {
            $customer->$field = $value;
        }

        //save the customer into the database
        $customer->save();
        return $customer;
    }

    //delete a customer
    public static function deleteCustomer($request) {
        //retrieve the id from the request
        $customer_id = $request->getAttribute('customer_id');
        $customer = self::find($customer_id);
        return($customer ? $customer->delete() : $customer);
    }

    //view all pets owned by a customer
    public static function getPetsByCustomer($customer_id) {
        $pets = self::findOrfail($customer_id)->pets;
        return $pets;
    }
}