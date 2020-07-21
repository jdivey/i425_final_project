<?php
/**
 * Author: Jacob Ivey
 * Date: 7/14/2020
 * File: appointment_statusController.php
 * Description:
 */

namespace VetPetAPI\Controllers;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use VetPetAPI\Models\Appointment_status;
use VetPetAPI\Validation\Validator;

class  Appointment_statusController{
    //list all appointments
    public function index(Request $request, Response $response, array $args) {
        //get query string variables from url
        $params = $request->getQueryParams();
        $term = array_key_exists('q', $params) ? $params['q'] : null;

        if (!is_null($term)) {
            $results = Appointment_status::searchAppointments($term);
        }else{
            $results = Appointment_status::getAppointments();
        }
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);

    }

    //view an appointment
    public function view(Request $request, Response $response, array $args) {
        $appointment_id = $args['appointment_id'];
        $results = Appointment_status::getAppointmentId($appointment_id);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //create an appointment
    public function create(Request $request, Response $response, array $args) {
        //validate the request
        $validation = Validator::validateAppointment($request);

        if (!$validation) {
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors()
            ];

            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }

        //insert a new appointment
        $appointment = Appointment_status::createAppointment($request);

        $results = [
            'status' => "Appointment created",
            'data' => $appointment
        ];
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //update an appointment
    public function update(Request $request, Response $response, array $args) {
        //validate the request
        $validation = Validator::validateAppointment($request);

        //if validation failed
        if(!$validation) {
            $results['status'] = "Validation: failed";
            $results['errors'] = Validator::getErrors();
            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }

        $appointment = Appointment_status::updateAppointment($request);
        $status = $appointment ? "Appointment has been updated" : "Appointment cannot be updated";
        $status_code = $appointment ? 200 : 500;
        $results['status'] = $status;
        if ($appointment) {
            $results['data'] = $appointment;
        }
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }

    //method to delete an appointment
    public function delete(Request $request, Response $response, array $args) {
        $appointment = Appointment_status::deleteAppointment($request);
        $status = $appointment ? "Appointment has been deleted" : "Appointment cannot be deleted";
        $status_code = $appointment ? 200 : 500;
        $results = ['status' => $status];
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }
}