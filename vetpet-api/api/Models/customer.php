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
}