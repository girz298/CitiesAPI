<?php
namespace App\EventSubscriber\Api\Project;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Api\Project;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
        if ($object instanceof Project) {
            switch ($method) {
                case 'POST':
                    $user = $this->tokenStorage->getToken()->getUser();
                    $object->setUser($user);
                    break;
                case 'PUT':
                    $this->checkAccessForProject($object);
                    break;
                case 'DELETE':
                    $this->checkAccessForProject($object);
                    break;
                default:
                    break;
            }
        }
    }

    private function checkAccessForProject(Project $project)
    {
        $currentUserId = $this->tokenStorage->getToken()->getUser()->getId();
        $projectUserId = $project->getUser()->getId();
        if ($projectUserId != $currentUserId) {
            throw new AccessDeniedException();
        }
    }
}
