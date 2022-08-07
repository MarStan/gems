<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Employee;
use App\Models\Meeting;
use App\Models\Person;
use App\Services\EmployeeService;

class PersonRepository
{
    public function __construct(private EmployeeService $employeeService)
    {
    }

    //TODO: move employee part to own repo
    public function createAndAttachToMeeting(string $email, Meeting $meeting): void
    {
        if (!$this->employeeService->isEmployeeEmail($email)) {
            $person = Person::updateOrCreate(['email' => $email]);
            $meeting->people()->syncWithoutDetaching($person);
        } else {
            $employee = Employee::where('email', $email)->firstOrFail();
            $meeting->employees()->syncWithoutDetaching($employee);
        }
    }
}