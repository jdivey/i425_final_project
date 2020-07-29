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

    //search for a vet
    public static function searchVets($term) {
        if (is_numeric($term)) {
            $query = self::where('vet_id', '>=', $term);
        }else{
            $query = self::where('first_name', 'like', "%$term%")
                ->orWhere('last_name', 'like', "%$term%");
        }

        return $query->get();
    }

    //insert new vet
    public static function createVet($request) {
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //create a new vet instance
        $vet = new Vet();

        //set the vet's attributes
        foreach ($params as $field => $value) {
            //echo $field, ':', $value;
            $vet->$field = $value;
        }

        //insert the vet into the database
        $vet->save();

        return $vet;
    }

    //update a vet
    public static function updateVet($request) {
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //retrieve id from the request body
        $vet_id = $request->getAttribute('vet_id');
        $vet = self::find($vet_id);
        if (!$vet) {
            return false;
        }

        //update attributes of the vet
        foreach ($params as $field => $value) {
            $vet->$field = $value;
        }

        //save the student into the database
        $vet->save();
        return $vet;
    }

    //delete a vet
    public static function deleteVet($request) {
        //retrieve the id from the request
        $vet_id = $request->getAttribute('vet_id');
        $vet = self::find($vet_id);
        return($vet ? $vet->delete() : $vet);
    }

}
