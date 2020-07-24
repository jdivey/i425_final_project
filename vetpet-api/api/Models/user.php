<?php
/**
 * Author: Jacob Ivey
 * Date: 7/24/2020
 * File: user.php
 * Description: the user model class
 */

namespace VetPetAPI\Models;

use Illuminate\Database\Eloquent\Model;
use Firebase\JWT\JWT;
use Google_Service_Oauth2;
use Google_Client;

class User extends Model
{
    //JWT secret
    const JWT_KEY = 'VetPetAPI-api-v25';

    //the lifetime of the JWT token: seconds
    const JWT_EXPIRE = 3600;

    //The table associated with this model. "users" is the default name.
    protected $table = 'users';

    //The primary key of the table. "id" is the default name.
    protected $primaryKey = 'id';

    //Is the PK an incrementing integer value? "True" is the default value.
    public $incrementing = true;

    //The data type of the PK. "int" is the default value.
    protected $keyType = 'int';

    //Do the created_at and updated_at columns exist in the table? "True" is the default value.
    public $timestamps = true;

    //List all users
    public static function getUsers() {
        $users = self::all();
        return $users;
    }

    // View a specific user by id
    public static function getUserById(string $id)
    {
        $user = self::find($id);
        return $user;
    }

    // Create a new user
    public static function createUser($request)
    {
        // Retrieve parameters from request body
        $params = $request->getParsedBody();

        // Create a new User instance
        $user = new User();

        // Set the user's attributes
        foreach ($params as $field => $value) {

            // Need to hash password
            if ($field == 'password') {
                $value = password_hash($value, PASSWORD_DEFAULT);
            }

            // Skip role. It defaults to 2.
            if ($field == 'role') {
                continue;
            }

            $user->$field = $value;
        }

        // Insert the user into the database
        $user->save();
        return $user;
    }

    // Update a user
    public static function updateUser($request)
    {
        // Retrieve parameters from request body
        $params = $request->getParsedBody();

        //Retrieve the user's id from url and then the user from the database
        $user = self::find($request->getAttribute('id'));

        //If user not found
        if(!$user) {
            return false;
        }

        // Update attributes of the user
        $user->name = $params['name'];
        $user->email = $params['email'];
        $user->username = $params['username'];
        $user->password = password_hash($params['password'], PASSWORD_DEFAULT);

        // Update the user
        $user->save();
        return $user;
    }

    // Delete a user
    public static function deleteUser($request)
    {
        $user = self::find($request->getAttribute('id'));
        return($user ? $user->delete() : $user);
    }

    // User Authentication and Authorization methods ***************************************************

    //authenticate user by username and password
    public static function authenticateUser($username, $password) {
        //retrieve the records from the database table that match the username
        $user = self::where('username', $username)->first();
        if (!$username) {
            return false;
        }

        //verify password
        return password_verify($password, $user->password) ? $user : false;
    }

    //***********************JWT Authentication******************************************
    public static function generateJWT($id) {
        //data for payload
        $user = self::find($id);

        if (!$user) {
            return false;
        }

        $key = self::JWT_KEY;
        $expiration = time() + self::JWT_EXPIRE;
        $issuer = 'mycollege-api.com';

        $token = [
            'rss' => $issuer,
            'exp' => $expiration,
            'rsa' => time(),
            'data' => [
                'uid' => $id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ]
        ];

        //generate and return the token
        return JWT::encode(
            $token, //data to be encoded in the JWT
            $key, // the signing key
            'HS256' //algorithm used to sign the token, defaults to hs256
        );
    }

    //validate a JWT token
    public static function validateJWT($token) {
        $decoded = JWT::decode($token, self::JWT_KEY, array('HS256'));
        return $decoded;
    }

    //****************************************************************** Google OAuth2 *************************
    //login to google account and return a token
    public static function generateOauth2($code) {
        //create google api client
        $client = new Google_Client();
        $client->setAuthConfig(__DIR__. '/client_secret.json');
        $client->addScope(Google_Service_Oauth2::OPENID);

        //initial request, code does not exist
        if (!$code) {
            //generate a url to request access from google's oauth2 server
            $auth_url = $client->createAuthUrl();
            return $auth_url;
        } else {
            //redirected request, code is available
            //exchange an authorization code for an access token, use the authenticate method
            $client->authenticate($code);

            //you can retrieve the access token with the getAccessToken method
            $token = $client->getAccessToken();
            return $token;
        }
    }

    //validate an id token received from google oauth2 server
    public static function validateOauth2($token) {
        $client = new Google_Client();
        $client->setAuthConfig(__DIR__. '/client_secret.json');
        $payload = $client->verifyIdToken($token);
        return ($payload) ? true : false;
    }
}