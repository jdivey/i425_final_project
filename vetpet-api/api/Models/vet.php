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

    //set up the relation between Class and Course and class belongs to a course
    public function pet() {
        return $this->belongsTo('VetPetAPI\Models\Pet', 'pet');
    }

    //set up the relation between a class and professor, a class belongs to a professor
    public function customer() {
        return $this->belongsTo('VetPetAPI\Models\Customer', 'customer');
    }


    //retrieve all vets
    public static function getVets() {
        $vets = self::with(['customer', 'pet'])->get();
        return $vets;
    }

    //retrieve a specific vet
    public static function getVetById($vet_id) {
        $vet = self::findOrfail($vet_id);
        $vet->load('customer')->load('pet');
        return $vet;
    }



}
