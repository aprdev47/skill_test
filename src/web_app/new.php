<h1>Create New Employee</h1>
<form action="" method="post">
    <ul>
        <li>
            Employee Name:
            <input name="name" type="text" />
        </li>
        <li>
            Gender :
            <select name="gender">
                <option value="Prefer not to say">Prefer not to say</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Non-binary">Non-binary</option>
                <option value="Other">Other</option>
            </select>
        </li>
        <li>
            Phone Number:
            <input name="phone_number" type="text"/>
        </li>
        <li>
            Password:
            <input name="password" type="password" />
        </li>
        <li>
            Email:
            <input name="email" type="text"/>
        </li>
        <li>
            Employee Type:
            <select name="employee_type">
                <option value="1">Part Time</option>
                <option value="2">Full Time</option>
            </select>
        </li>
    </ul>
    <input type="submit" value="Create">
</form>

<?php

require_once __DIR__ . '/../../config.php';

use Athul\SkillTest\Common\BusinessObject\EmployeeBo;
use Athul\SkillTest\models\Employee;

// Add new employee
/**
 * @throws Exception
 */
function addEmployee($name, $gender, $phone_number, $password, $email, $employee_type) :int {

    $employee = new Employee();

    $employee->name = $name;
    $employee->gender = $gender;
    $employee->phoneNumber = $phone_number;
    $employee->email = $email;
    $employee->type = $employee_type;
    $employee->password = sha1($password); // or some default, if required
    $employee->email_sent = false; // or true, depending on logic

    $employee->save();

    return $employee->id;
}

// Send registration email
function sendRegistrationEmail($email, $name, $password) {
    mail($email, "Thanks for registering", "Dear " . $name . ",\nThanks for registering with AwesomeCorp!! your password is $password.\nYou can login at: http://www.awesomecorp.com/login.\nRegards,\nAwesomeCorp");
}

// Function to update the employee's password and set email sent flag
function updateEmployeePassword($employeeId, $password) {
    $employee = Employee::getById($employeeId);

    $employee->password = sha1($password);
    $employee->save();
}

if ($_POST) {
    $employeeId = addEmployee($_POST['name'], $_POST['gender'], $_POST['phone_number'],$_POST['password'], $_POST['email'], $_POST['employee_type']);

    $employeeBo = new EmployeeBo();
    $employeeBo->logEmployeeAddition($_POST['name']);

    sendRegistrationEmail($_POST['email'], $_POST['name'], $_POST['password']);

    updateEmployeePassword($employeeId, $_POST['password']);

    $_SESSION["logged_in_user_id"] = $employeeId;

    echo "<script type='text/javascript'>
            window.location.href = 'dashboard.php';
          </script>";
    exit;
}

?>




