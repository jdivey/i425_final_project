<?php
/**
 * Author: Jacob Ivey
 * Date: 7/9/2020
 * File: courseController.php
 * Description:
 */

namespace MyCollegeAPI\Controllers;

use MyCollegeAPI\Models\Course;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use MyCollegeAPI\Models\Professor;

class  CourseController{
    //list all courses
    public function index(Request $request, Response $response, array $args) {
        $results = Course::getCourses();
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);

    }

    //view a course
    public function view(Request $request, Response $response, array $args) {
        $number = $args['number'];
        $results = Course::getCourseByNumber($number);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }
}