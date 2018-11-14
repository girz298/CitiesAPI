<?php
namespace App\EventSubscriber\Api\Project;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Api\Project;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CreateProjectSubscriber implements EventSubscriberInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setUserForProject', EventPriorities::PRE_WRITE],
        ];
    }

    public function setUserForProject(GetResponseForControllerResultEvent $event)
    {
        $object = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ($object instanceof Project && $method === Request::METHOD_POST) {
            $user = $this->tokenStorage->getToken()->getUser();
            $object->setUser($user);
        }
    }
}
