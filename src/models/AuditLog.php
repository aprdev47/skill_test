<?php

declare(strict_types=1);

namespace Athul\SkillTest\models;

use Exception;
use PDO;

class AuditLog
{
    /**
     * The unique identifier
     * @var int
     */
    public $id;

    /**
     * The log message
     * @var string
     */
    public $message;

    public function save(): void
    {
        $host   = getenv('DB_HOST');
        $dbName = getenv('DB_NAME');
        $dsn    = "mysql:host={$host};port=3306;dbname={$dbName}";

        $this->pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'));
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $this->pdo->prepare(
            "INSERT INTO audit_log (`message`) VALUES (:message)"
        );
        $stmt->bindParam(':message', $this->message);

        if (!$stmt->execute()) {
            throw new Exception("Could not save audit log.");
        }

        $this->id = (int)$this->pdo->lastInsertId();
    }
}
