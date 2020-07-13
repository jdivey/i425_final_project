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

    //retrieve all pets
    public static function getPets() {
        $pets = self::all();
        return $pets;
    }

    //retrieve a specific pet
    public static function getPetById($pet_id) {
        $pet = self::findOrfail($pet_id);
        return $pet;
    }

}