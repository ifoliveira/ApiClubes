<?php

namespace App\Service\Message;

use App\Entity\Message;
use App\Entity\Equipo;
use App\Repository\MessageRepository;
use Ramsey\Uuid\UuidInterface;
use App\Entity\User;
use DateTime;

class CreateMessage
{

    public function __construct(private MessageRepository $messageRepository)
    {
    }

    public function __invoke( 
    ?UuidInterface $Uuid, 
    ?user $user,
    ?string $text,
    ?DateTime $createAt,
    ?equipo $equipo,
    ?string $image,
    ?string $video,
    ?string $audio,
    ?bool $sistema,
    ?bool $sent,
    ?bool $receive,
    ?bool $pending): Message
    {


        $Message = Message::create($Uuid, $user, $text, $createAt, $equipo, $image,$video,$audio,$sistema,$sent,$receive,$pending);       

        return $Message;
    }
}