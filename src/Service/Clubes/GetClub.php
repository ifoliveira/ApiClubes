<?php

namespace App\Service\Clubes;

use App\Entity\Clubes;
use App\Model\Exception\Clubes\ClubNotFound;
use App\Repository\ClubesRepository;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class GetClub
{

    public function __construct(private ClubesRepository $ClubesRepository)
    {
    }

    public function __invoke(String $Uuid): Clubes
    {   

        $Clubes = $this->ClubesRepository->find(Uuid::fromString($Uuid));
        if (!$Clubes) {

            ClubNotFound::throwException();
            }
        return $Clubes;
    }
}