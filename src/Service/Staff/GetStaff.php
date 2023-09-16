<?php

namespace App\Service\Staff;

use App\Entity\Staff;
use App\Repository\StaffRepository;
use App\Model\Exception\Staff\StaffNotFound;
use Ramsey\Uuid\Uuid;

class GetStaff
{

    public function __construct(private staffRepository $staffRepository)
    {

    }

    public function __invoke(string $Uuid): ?staff
    {
        $staff = $this->staffRepository->find(Uuid::fromString($Uuid));
        if (!$staff) {
                staffNotFound::throwException();

        }
        return $staff;
    }
}