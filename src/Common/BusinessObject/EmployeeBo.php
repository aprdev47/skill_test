<?php

declare(strict_types=1);

namespace Athul\SkillTest\Common\BusinessObject;

use Athul\SkillTest\models\AuditLog;

class EmployeeBo
{
    function logEmployeeAddition($name) {

        $timestamp = date("d/m/y h:i:s");
        $message = "{$name} was added on $timestamp";

        $auditLog = new AuditLog();

        $auditLog->message = $message;

        $auditLog->save();
    }
}
