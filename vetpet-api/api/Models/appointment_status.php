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
        $appointments = self::all();
        return $appointments;
    }

    //view a specific appointment by id
    public static function getAppointmentId($appointment_id) {
        $appointment = self::findOrfail($appointment_id);
        return $appointment;
    }
}