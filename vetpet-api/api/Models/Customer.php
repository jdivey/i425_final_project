<?php
/**
 * Author: Jacob Ivey
 * Date: 7/13/2020
 * File: customer.php
 * File: Customer.php
 * Description:
 */


use Illuminate\Database\Eloquent\Model;


class Customer extends Model
{
    //the table associated with this model
    protected $table = "customers";

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

        $customer = self::find($customer_id);
        return($customer ? $customer->delete() : $customer);
    }


    // This function returns an array of links for pagination. The array includes links for the current, first, next, and last pages.
    private static function getLinks($request, $limit, $offset) {
    $count = self::count();

    // Get requet uri and parts
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
}
