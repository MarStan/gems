<?php

declare(strict_types=1);

namespace App\Services;

class EmployeeService
{
    public static function isEmployeeEmail($email): bool
    {
        return str_ends_with($email, '@usergems.com');
    }
}