<?php
/**
 * Author: Jacob Ivey
 * Date: 7/14/2020
 * File: appointment_status.php
 * Description:
 */

namespace VetPetAPI\Models;

use Illuminate\Database\Eloquent\Model;


class Appointment_status extends Model
{
    //the table associated with this model
    protected $table = "appointment_status";

    //the primary key of the table
    protected $primaryKey = "appointment_id";

    //the key is non-numeric
    public $implementing = false;

    //if primary key is not an integer, set its type
    protected $keyType = "char";

    //if the created at and updated at columns are not used
    public $timestamps = false;

    //retrieve all appointments
    public static function getAppointments() {
        return self::all();
    }

    //view a specific appointment by id
    public static function getAppointmentId($request) {
        //Get total number of row count
        $count = self::count();

        //Get querystring variables from url
        $params = $request->getQueryParams();

        //Do Limit and Offset exist?
        $limit = array_key_exists('limit', $params) ? (int)$params['limit'] : 10; //Items per page
        $offset = array_key_exists('offset', $params) ? (int)$params['offset'] : 0; //Offset of the first item

        //Pagination
        $links = self::getLinks($request, $limit, $offset);

        //Sorting
        $sort_key_array = self::getSortKeys($request);

        //Build query
        $query = self::skip('appointment_status'); //Build the query to get all courses
        $query = $query->skip($offset)->take($limit); //Limit the rows

        //Sort the output by one or more columns
        foreach($sort_key_array as $column => $direction) {
            $query->orderBy($column, $direction);
        }
        $appointment = $query->get();

        //Construct the data for response
        $results = [
            'totalCount' => $count,
            'limit' => $limit,
            'offset' => $offset,
            'links' => $links,
            'sort' => $sort_key_array,
            'data' => $appointment
        ];

        return $results;
    }
    

    //search for an appointment
    public static function searchAppointments($term) {
        if (is_numeric($term)) {
            $query = self::where('appointment_id', '=', "$term");
        }else{
            $query = self::where('pet_id', 'like', "%$term%")
                ->orWhere('appointment_status', 'like', "%$term%")
                ->orWhere('appointment_type', 'like', "%$term%");
        }

        return $query->get();
    }

    //insert new appointment
    public static function createAppointment($request) {
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //create a new appointment instance
        $appointment = new Appointment_status();

        //set the appointment's attributes
        foreach ($params as $field => $value) {

            $appointment->$field = $value;
        }

        //insert the appointment into the database
        $appointment->save();

        return $appointment;
    }

    //update an appointment
    public static function updateAppointment($request) {
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //retrieve id from the request body
        $appointment_id = $request->getAttribute('appointment_id');
        $appointment = self::find($appointment_id);
        if (!$appointment) {
            return false;
        }

        //update attributes of the appointment
        foreach ($params as $field => $value) {
            $appointment->$field = $value;
        }

        //save the appointment into the database
        $appointment->save();
        return $appointment;
    }

    //delete an appointment
    public static function deleteAppointment($request) {
        //retrieve the id from the request
        $appointment_id = $request->getAttribute('appointment_id');
        $appointment = self::find($appointment_id);
        return($appointment ? $appointment->delete() : $appointment);
    }
}