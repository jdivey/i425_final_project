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

class  Appointment_statusController{
    //list all appointments
    public function index(Request $request, Response $response, array $args) {
        $results = Appointment_status::getAppointments();
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);

    }

    //view an appointment
    public function view(Request $request, Response $response, array $args) {
        $appointment_id = $args['appointment_id'];
        $results = Appointment_status::getAppointmentId($appointment_id);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }
}