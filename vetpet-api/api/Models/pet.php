<?php
/**
 * Author: Jacob Ivey
 * Date: 7/12/2020
 * File: pet.php
 * Description:
 */

namespace VetPetAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    //the table associated with this model
    protected $table = "pets";

    //the primary key of the table
    protected $primaryKey = "pet_id";

    //the key is non-numeric
    public $implementing = false;

    //if primary key is not an integer, set its type
    protected $keyType = "char";

    //if the created at and updated at columns are not used
    public $timestamps = false;

    //set the one to many relation between Pet and Appointment
    public function appointments() {
        return $this->hasMany('VetPetAPI\Models\appointment_status', 'pet_id');
    }

    //retrieve all pets
    public static function getPets($request) {
        $count = self::count();

        //get query string variables from url
        //do limit and offset exist?
        $params = $request->getQueryParams();
        $limit = array_key_exists('limit', $params) ? (int)$params['limit']: 10; //items per page
        $offset = array_key_exists('offset', $params) ? (int)$params['offset']: 0; //offset of the first item

        //pagination
        $links = self::getLinks($request, $limit, $offset);

        //sorting
        $sort_key_array = self::getSortKeys($request);

        //build query
        $query = self::with('appointments'); //build the query to get all the course
        $query = $query->skip($offset)->take($limit); //limit the rows

        //sort the output by one or more columns
        foreach ($sort_key_array as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        $pets = $query->get(); //finally run the query and get the results

        //construct the data for response
        $results = [
            'totalCount' => $count,
            'limit' => $limit,
            'offset' => $offset,
            'links' => $links,
            'sort' => $sort_key_array,
            'data' => $pets
        ];

        return $results;
    }

    //retrieve a specific pet
    public static function getPetById($pet_id) {
        $pet = self::findOrfail($pet_id);
        $pet->load('appointments');
        return $pet;
    }

    //view all appointments of a pet
    public static function getAppointmentsByPet($pet_id) {
        $appointments = self::findOrfail($pet_id)->appointments;
        return $appointments;
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


    //Sort keys are optionally enclosed in [ ], separated with commas;
    // Sort directions can be optionally appended to each sort key, separated by :.
    // Sort directions can be 'asc' or 'desc' and defaults to 'asc'.
    // Examples: sort=[number:asc,title:desc], sort=[number, title:desc]
    // This function retrieves sorting keys from uri and returns an array.

    private static function getSortKeys($request) {
        $sort_key_array = array();

        // Get querystring variables from url
        $params = $request->getQueryParams();

        if (array_key_exists('sort', $params)) {
            $sort = preg_replace('/^\[|\]$|\s+/', '', $params['sort']);  // remove white spaces, [, and ]
            $sort_keys = explode(',', $sort); //get all the key:direction pairs
            foreach ($sort_keys as $sort_key) {
                $direction = 'asc';
                $column = $sort_key;
                if (strpos($sort_key, ':')) {
                    list($column, $direction) = explode(':', $sort_key);
                }
                $sort_key_array[$column] = $direction;
            }
        }

        return $sort_key_array;
    }

}
