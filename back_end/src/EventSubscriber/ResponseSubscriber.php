<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class ResponseSubscriber implements EventSubscriberInterface
{
    /** @var Security */
    private $security;

    /** @var JWTTokenManagerInterface */
    private $jwt;

    public function __construct(Security $security, JWTTokenManagerInterface $jwt)
    {
        $this->security = $security;
        $this->jwt = $jwt;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => [
                ['setAccessControl', EventPriorities::POST_RESPOND],
                ['renewJWT', EventPriorities::POST_RESPOND],
            ],
        ];
    }

    public function setAccessControl(ResponseEvent $event)
    {
        $event->getResponse()->headers->add([
            'Access-Control-Expose-Headers' => 'Renewed-JWT',
        ]);
    }

    public function renewJWT(ResponseEvent $event)
    {
        $token = $this->security->getToken();
        if (!$event->isMainRequest() || null === $token || !$token->getUser() instanceof User) {
            return;
        }

        $jwtData = $this->jwt->decode($token);
        if ($jwtData['exp'] - (new \DateTime())->getTimestamp() <= 2700) {
            $response = $event->getResponse();
            $response->headers->add([
                'Renewed-JWT' => $this->jwt->create($token->getUser()),
            ]);
        }
    }
}
