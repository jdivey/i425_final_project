<?php
/**
 * Author: Jacob Ivey
 * Date: 7/13/2020
 * File: vet.php
 * Description:
 */

namespace VetPetAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Vet extends Model
{
    //the table associated with this model
    protected $table = "vets";

    //the primary key of the table
    protected $primaryKey = "vet_id";

    //the key is non-numeric
    public $implementing = false;

    //if primary key is not an integer, set its type
    protected $keyType = "char";

    //if the created at and updated at columns are not used
    public $timestamps = false;

    //retrieve all vets
    public static function getVets() {
        $vets = self::all();
        return $vets;
    }

    //retrieve a specific vet
    public static function getVetById($vet_id) {
        $vet = self::findOrfail($vet_id);
        return $vet;
    }

}