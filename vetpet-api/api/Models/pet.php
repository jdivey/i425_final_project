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

    //search for a vet
    public static function searchPets($term) {
        if (is_numeric($term)) {
                $query = self::where('owner_id', '>=', $term)
                    ->orWhere('vet_id', '>=', $term);
        }else{
            $query = self::where('pet_id', 'like', "%$term%")
                ->orWhere('pet_breed', 'like', "%$term%")
                ->orWhere('pet_sex', 'like', "%$term%")
                ->orWhere('pet_birthday', 'like', "%$term%")
                ->orWhere('first_name', 'like', "%$term%")
                ->orWhere('last_name', 'like', "%$term%");
        }

        return $query->get();
    }

    //insert new pet
    public static function createPet($request) {
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //create a new pet instance
        $pet = new Pet();

        //set the pet's attributes
        foreach ($params as $field => $value) {

            $pet->$field = $value;
        }

        //insert the pet into the database
        $pet->save();

        return $pet;
    }

    //update a pet
    public static function updatePet($request) {
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //retrieve id from the request body
        $pet_id = $request->getAttribute('pet_id');
        $pet = self::find($pet_id);
        if (!$pet) {
            return false;
        }

        //update attributes of the pet
        foreach ($params as $field => $value) {
            $pet->$field = $value;
        }

        //save the pet into the database
        $pet->save();
        return $pet;
    }

    //delete a pet
    public static function deletePet($request) {
        //retrieve the id from the request
        $pet_id = $request->getAttribute('pet_id');
        $pet = self::find($pet_id);
        return($pet ? $pet->delete() : $pet);
    }

}