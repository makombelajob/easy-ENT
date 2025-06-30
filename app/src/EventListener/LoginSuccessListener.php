<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

final class LoginSuccessListener
{
    public function __construct(private EntityManagerInterface $entityManager){}
    #[AsEventListener]
    public function onLoginSuccessEvent(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();

        if(!$user instanceof UserInterface){
            return;
        }
        /**
         * @var Users $user
         */

        $user->setLastConnectionAt(new \DateTimeImmutable());
        $this->entityManager->flush();
    }
}
