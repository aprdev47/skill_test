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

// DB connection
function getDbConnection() {
    return new mysqli("db", "root", "root", "skill_test_db");
}

// Add new employee
function addEmployee($name, $gender, $phone_number, $email, $employee_type): int {
    $dbConnection = getDbConnection();

    $stmt = $dbConnection->prepare("INSERT INTO employee (`name`,`gender`, `phone_number`, `email`, `type`) VALUES (?, ?,?, ?, ?)");
    $stmt->bind_param("sssss", $name, $gender, $phone_number, $email, $employee_type);

    if (!$stmt->execute()) {
        die("<h2>Sorry, could not add employee: " . $stmt->error . "</h2>");
    }

    $employeeId = $dbConnection->insert_id;
    $stmt->close();

    return $employeeId;
}

// Employ addition logging
function logEmployeeAddition($name) {
    $dbConnection = getDbConnection();

    $timestamp = date("d/m/y h:i:s");
    $stmtAudit = $dbConnection->prepare("INSERT INTO audit_log (`message`) VALUES (?)");
    $message = "{$name} was added on $timestamp";
    $stmtAudit->bind_param("s", $message);
    $stmtAudit->execute();

    $stmtAudit->close();
}

// Send registration email
function sendRegistrationEmail($email, $name, $password) {
    mail($email, "Thanks for registering", "Dear " . $name . ",\nThanks for registering with AwesomeCorp!! your password is $password.\nYou can login at: http://www.awesomecorp.com/login.\nRegards,\nAwesomeCorp");
}

// Function to update the employee's password and set email sent flag
function updateEmployeePassword($employeeId, $password) {
    $dbConnection = getDbConnection();

    $stmtUpdate = $dbConnection->prepare("UPDATE employee SET password = ?, email_sent = 1 WHERE id = ?");
    $hashedPassword = sha1($password);
    $stmtUpdate->bind_param("si", $hashedPassword, $employeeId);
    $stmtUpdate->execute();

    $stmtUpdate->close();
}

if ($_POST) {
    $employeeId = addEmployee($_POST['name'], $_POST['gender'], $_POST['phone_number'], $_POST['email'], $_POST['employee_type']);

    logEmployeeAddition($_POST['name']);

    sendRegistrationEmail($_POST['email'], $_POST['name'], $_POST['password']);

    updateEmployeePassword($employeeId, $_POST['password']);

    $_SESSION["logged_in_user_id"] = $employeeId;

    echo "<script type='text/javascript'>
            window.location.href = 'dashboard.php';
          </script>";
    exit;
}

?>




