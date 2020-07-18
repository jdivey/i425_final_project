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

    class Validator {
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
                'vet_id' => v::notEmpty()->alnum()->startsWith('s')->length(5, 5),
                'first_name' => v::alnum(' '),
                'last_name' =>  v::email()
            ];

            return self::validate($request, $rules);
        }

        //return the errors in an array
        public static function getErrors() {
            return self::$errors;
        }
    }