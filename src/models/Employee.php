<?php

namespace Athul\SkillTest\models;

use Exception;
use PDO;

/**
 * Class Employee
 *
 * This model represents an employee in our system.
 */
class Employee
{

    /**
     * @var bool
     */
    public $email_sent;

    /**
     * The unique identifier
     * @var int
     */
    public $id;

    /**
     * The employee's full name
     * @var string
     */
    public $name;

    /**
     * @var string
     * @see self::TYPE_* constants
     */
    public $gender;

    /**
     * Male
     */
    const GENDER_MALE = "Male";

    /**
     * Female
     */
    const GENDER_FEMALE = "Female";

    /**
     * Non-binary
     */
    const GENDER_NON_BINARY = "Non-binary";

    /**
     * Other
     */
    const GENDER_OTHER = "Other";

    /**
     * The employee's phone number
     * @var string
     */
    public $phoneNumber;

    /**
     * The employee's password.
     * @var string
     */
    public $password;

    /**
     * The employee's email address
     * @var string
     */
    public $email;

    /**
     * @var string
     * @see self::TYPE_* constants
     */
    public $type;

    /**
     * Full time employee - works 40 hours week+
     */
    const TYPE_FULL_TIME = "full-time";

    /**
     * Part time employee - works < 40 hours week.
     */
    const TYPE_PART_TIME = "part-time";

    /**
     * Save method (this has been hacked to save to the database, but it should be presumed that this would happen in
     * an ORM of some sort - and that the ORM takes care of the DB connection, query, etc.)
     *
     * It should also be assumed that the save method will work nicely for inserting and saving updates to an already existing employee.
     *
     * @return void
     * @throws Exception if we had a problem saving.
     */

    public static function getById(int $id): ?Employee
    {
        $host   = getenv('DB_HOST');
        $dbName = getenv('DB_NAME');
        $dsn    = "mysql:host=" . $host . ";port=3306;dbname=" . $dbName;

        $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT * FROM employee WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $employee = new self();
        $employee->id = $data['id'];
        $employee->name = $data['name'];
        $employee->gender = $data['gender'];
        $employee->phoneNumber = $data['phone_number'];
        $employee->type = $data['type'];
        $employee->email = $data['email'];
        $employee->password = $data['password'];
        $employee->email_sent = (bool) $data['email_sent'];

        return $employee;
    }

    public function save()
    {
        $host   = getenv('DB_HOST');
        $dbName = getenv('DB_NAME');
        $dsn    = "mysql:host=" .$host . ";port=3306;dbname=" .$dbName;

        $pdo = new PDO( $dsn, getenv('DB_USER'), getenv('DB_PASS'));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $updateId = false;

        if (!$this->id) {
            $stmt = $pdo->prepare(
                "INSERT INTO employee (`name`, `gender`,`phone_number`, `type`, `email`, `password`, `email_sent`) VALUES (:name, :gender, :phone, :type, :email, :password, :email_sent)"
            );
            $updateId = true;
        } else {
            $stmt = $pdo->prepare(
                "UPDATE employee SET `name` = :name, `gender` = :gender, `phone_number` = :phone ,`type` = :type, `email` = :email, `password` = :password, `email_sent` = :email_sent where id = :id"
            );
            $stmt->bindParam(":id", $this->id);
        }

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':gender', $this->gender);
        $stmt->bindParam(':phone', $this->phoneNumber);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':email_sent', $this->email_sent,PDO::PARAM_INT);


        if (!$stmt->execute()) {
            throw new Exception("Could not save employee.");
        }

        if ($updateId) {
            $this->id = $pdo->lastInsertId();

            $csvLine = sprintf("%d,%s,%s\n", $this->id, $this->name, $this->email);
            file_put_contents('/tmp/employee_report.csv', $csvLine, FILE_APPEND | LOCK_EX);
        }
    }

}
