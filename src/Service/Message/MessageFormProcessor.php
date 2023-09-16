<?php

namespace App\Service\Message;

use Symfony\Component\HttpFoundation\Request;
use App\Form\Model\MessageDto;
use App\Form\Type\MessageFormType;
use App\Repository\MessageRepository;
use App\Service\Message\CreateMessage;
use App\Service\Equipo\GetEquipo;
use Symfony\Component\Form\FormFactoryInterface;
use Ramsey\Uuid\Uuid;
use App\Service\User\GetUser;




class MessageFormProcessor
{
    public function __construct(
        private MessageRepository $messageRepository,
        private CreateMessage $createMessage,        
        private GetEquipo $getEquipo,
        private GetUser $getUser,
        private FormFactoryInterface $formFactory,
    ) {

    }
    
    public function __invoke(Request $request): array
        
    {

        $messageDto = MessageDto::createEmpty();
       
        $content = json_decode($request->getContent(), true);

        if (empty($content)) {

            return [null, 'JSON Vacio'];
        } 


        $form = $this->formFactory->create(MessageFormType::class, $messageDto);
        $form->submit($content, false);


        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }
        if (!$form->isValid()) {
            return [null, $form];
        }

    
        $message = ($this->createMessage)
                                        (Uuid::uuid4(),
                                        ($this->getUser)($messageDto->getUser()), 
                                        $messageDto->getText(),
                                        date_create_from_format('Y-m-d H:m:s', $messageDto->getCreatedAt(), null),
                                        ($this->getEquipo)($messageDto->getEquipo()),
                                        $messageDto->getImage(),
                                        $messageDto->getVideo(),
                                        $messageDto->getAudio(),
                                        $messageDto->getSistema(),
                                        $messageDto->getSent(),
                                        $messageDto->getReceive(),
                                        $messageDto->getPending()
                                        );
    
        $this->messageRepository->save($message,true);
                                       

         return [$message, null]; 

    }

}