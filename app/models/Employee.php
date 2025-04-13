<?php

namespace Athul\SkillTest\models;

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
    public function save()
    {
        $pdo = new PDO("mysql:host=localhost;dbname=test", "root");

        $updateId = false;

        if (!$this->id) {
            $stmt = $pdo->prepare(
                "INSERT INTO employee (`name`, `phone_number`, `type`, `email`, `password`, `email_sent`) VALUES (:name, :phone, :type, :email, :password, :email_sent)"
            );
            $updateId = true;
        } else {
            $stmt = $pdo->prepare(
                "UPDATE employee SET `name` = :name, `phone_number` = :phone ,`type` = :type, `email` = :email, `password` = :password, `email_sent` = :email_sent where id = :id"
            );
            $stmt->bindParam(":id", $this->id);
        }

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':phone', $this->phoneNumber);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':email_sent', $this->email_sent);


        if (!$stmt->execute()) {
            throw new Exception("Could not save employee.");
        }

        if ($updateId) {
            $this->id = $pdo->lastInsertId();
        }
    }

}
