<?php
namespace App\EventSubscriber\Api\Task;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Api\Task;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CreateRemoveTaskSubscriber implements EventSubscriberInterface
{
    private $tokenStorage;
    private $supportMethods = ['POST', 'DELETE'];

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['canCreateOrRemoveTasksInProject', EventPriorities::PRE_WRITE],
        ];
    }

    public function canCreateOrRemoveTasksInProject(GetResponseForControllerResultEvent $event)
    {
        if (in_array($event->getRequest()->getMethod(), $this->supportMethods)) {
            $object = $event->getControllerResult();
            if ($object instanceof Task) {
                $projectUserId = $object->getProject()->getUser()->getId();
                $currentUserId = $this->tokenStorage->getToken()->getUser()->getId();
                if ($projectUserId != $currentUserId) {
                    throw new AccessDeniedException();
                }
            }
        }
    }
}
