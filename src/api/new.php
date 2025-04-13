<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config.php';

use Athul\SkillTest\Common\BusinessObject\EmployeeBo;
use Athul\SkillTest\models\Employee;

/**
 * For the purposes of the test, the way that the data comes in to the system is not relevant. Let's assume that
 * we have a REST API, and this is a POST request to the "new employee" endpoint. The data provided is the array
 * that has been specified here.
 */

//allowed data is "name", "number", "email" & "type"
$receivedData = array(
    "name" => "Bob Smith",
    "email" => "luke.zawadzki@astutepayroll.com",
    "number" => "+61 430 131 409",
    "type" => "full-time"
);

//this simulates a call to the API.
var_dump(handle_API_Request($receivedData));

/**
 * This is the processing code for the API.
 */
function handle_API_Request($data) {
    try {
        $employee = new Employee();
        $employee->name = $data['name'];
        $employee->email = $data['email'];
        $employee->phoneNumber = $data['number'];
        $employee->type = $data['type'];
        $generatedPassword = uniqid();
        $employee->password = sha1($generatedPassword);

        $employeeBo = new EmployeeBo();
        $employeeBo->logEmployeeAddition($data['name']);

        //send comms
        mail($employee->email, "Thanks for registering", "Dear " . $employee->name . ",\nYou have been added to AwesomeCorp! your password is $generatedPassword.\nYou can login at: http://awesomecorp.recruiterforce.com/login.\nRegards,\nRecruiterForce");

        $employee->email_sent = 1;
        $employee->save();

        return array(
            "status" => "success",
            "message" => $employee->id . " was added"
        );
    }
    catch (exception $e) {
        return array(
            "status" => "error",
            "message" => $e->getMessage()
        );
    }

}
