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
    public static function getCustomers() {
        $customers = self::all();
        return $customers;
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
}