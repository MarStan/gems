<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\DTO\Meeting as MeetingDTO;

class EmployeeService
{
    private const USEGEM_EMAIL_DOMAIN = '@usergems.com';

    public function isEmployeeEmail($email): bool
    {
        return str_ends_with($email, self::USEGEM_EMAIL_DOMAIN);
    }

    public function getMeetingsEmployeesNamesAndMeetingAccept(MeetingDTO $meeting): array
    {
        $employees = [];
        foreach ($meeting->accepted as $email) {
            if ($this->isEmployeeEmail($email)) {
                $employees[$email]['name'] = $this->getNameFromEmail($email);
                $employees[$email]['accepted'] = true;
            }
        }
        foreach ($meeting->rejected as $email) {
            if ($this->isEmployeeEmail($email)) {
                $employees[$email]['name'] = $this->getNameFromEmail($email);
                $employees[$email]['accepted'] = false;
            }
        }

        return $employees;
    }

    public function getNameFromEmail(string $email): string
    {
        $name = str_replace(self::USEGEM_EMAIL_DOMAIN, '', $email);
        return strtoupper($name);
    }
}