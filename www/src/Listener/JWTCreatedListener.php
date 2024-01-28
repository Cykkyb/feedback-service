<?php

namespace App\Listener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        /** @var \App\Entity\User $user */
        $user = $event->getUser();
        $payload = $event->getData();
        $payload['id'] = $user->getId();

        $event->setData($payload);
    }

}