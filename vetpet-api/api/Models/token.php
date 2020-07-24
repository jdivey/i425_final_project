<?php
/**
 * Author: Jacob Ivey
 * Date: 7/24/2020
 * File: token.php
 * Description:
 */

namespace VetPetAPI\Models;
use Illuminate\Database\Eloquent\Model;

class Token extends Model {
    //lifetime of the Bearer token: seconds
    const EXPIRE = 3600;

    //Generate Bearer if it does not exist for the current user, store the token in the database
    //if the token already exists and has not expired, retrieve the token
    public static function generateBearer($id) {
        //retrieve the token by user id
        $token = self::where('user', $id)->first();

        //determine a time in the past, current time minus the lifetime of the token
        $expire = time() - self::EXPIRE;

        //if the token exists and has expired, create a new one
        if ($token) {
            if ($expire > date_timestamp_get($token->updated_at)) {
                $token->value = bin2hex(random_bytes(64));
                $token->save();
            }

            return $token;
        }

        //token does not exist, create a new one
        $token = new Token();
        $token->user = $id;
        $token->value = bin2hex(random_bytes(64));
        $token->save();

        return $token;

    }

    //validate a Bearer token by matching the token with a database record
    public static function validateBearer($value) {
        //retrieve the token from the database
        $token = self::where('value', $value)->first();

        //create a time in the past
        $expire = time() - self::EXPIRE;
        return ($token && $expire < date_timestamp_get($token->updated_at)) ? $token : false;
    }
}