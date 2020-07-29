<?php
/**
 * Author: Jacob Ivey
 * Date: 7/18/2020
 * File: validator.php
 * Description: the validator class defines methods that validate data of models
 */

    namespace VetPetAPI\Validation;

    use Respect\Validation\Validator as v;
    use Respect\Validation\Exceptions\NestedValidationException;

    class validator {
        private static $errors = [];

        //a generic Validation method it returns true on success or false on failure
        public static function validate($request, array $rules) {
            foreach ($rules as $field => $rule) {
                //retrieve parameter from the url or the request body
                $param = $request->getAttribute($field) ?? $request->getParam($field);
                try{
                    $rule->setName(ucfirst($field))->assert($param);
                } catch (NestedValidationException $ex) {
                    self::$errors[$field] = $ex->getMessage();
                }
            }

            return empty(self::$errors);
        }

        //validate attributes of a vet object
        public static function validateVet($request) {
            //define all the Validation rules
            $rules = [
                'vet_id' => v::notEmpty()->alnum()->length(9, 9),
                'first_name' => v::alnum(' '),
                'last_name' =>  v::alnum(' ')
            ];

            return self::validate($request, $rules);
        }

        //validate attributes of a pet object
        public static function validatePet($request) {
            //define all the Validation rules
            $rules = [
                'pet_id' => v::notEmpty()->alnum()->length(9, 9),
                'first_name' => v::alnum(' '),
                'last_name' =>  v::alnum(' ')
            ];

            return self::validate($request, $rules);
        }

        //validate attributes of a customer object
        public static function validateCustomer($request) {
            //define all the Validation rules
            $rules = [
                'customer_id' => v::notEmpty()->alnum()->length(9, 9),
                'first_name' => v::alnum(' '),
                'last_name' =>  v::alnum(' ')
            ];

            return self::validate($request, $rules);
        }

        //validate attributes of an appointment object
        public static function validateAppointment($request) {
            //define all the Validation rules
            $rules = [
                'appointment_id' => v::notEmpty()->alnum()->length(9, 9),
                'pet_id' => v::notEmpty()->alnum()->length(9, 9),
                'appointment_status' => v::alnum(' '),
                'appointment_type' =>  v::alnum(' ')
            ];

            return self::validate($request, $rules);
        }

        // Validate attributes of a User model. Do not include fields having default values (id, role, etc.)
        public function validateUser($request) {
            $rules = [
                'name' => v::alnum(' '),
                'email' => v::email(),
                'username' => v::notEmpty(),
                'password' => v::notEmpty()
            ];

            return self::validate($request, $rules);
        }

        //return the errors in an array
        public static function getErrors() {
            return self::$errors;
        }
    }
