<?php

namespace App\EventSubscriber\Api\Task;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Api\Task;
use App\Repository\Api\TaskRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EditTaskSubscriber implements EventSubscriberInterface
{
    private $tokenStorage;
    private $manager;


    public function __construct(TokenStorageInterface $tokenStorage, ObjectManager $manager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->manager = $manager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['canEditTasksInProject', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function canEditTasksInProject(GetResponseForControllerResultEvent $event)
    {
        if ($event->getRequest()->getMethod() == 'PUT') {
            $object = $event->getControllerResult();

            if ($object instanceof Task) {
                if (!$this->isCurrentUserCanEditTask($object)) {
                    throw new AccessDeniedException();
                }
            }
        }
    }

    private function isCurrentUserCanEditTask(Task $task)
    {
        /**@var TaskRepository $taskRepository*/
        $taskRepository = $this->manager->getRepository('App:Api\Task');
        $currentUserId = $this->tokenStorage->getToken()->getUser()->getId();

        $dbUserId = $taskRepository->getUserIdForTask($task);

        if ($dbUserId == $currentUserId) {
            return true;
        }
        return false;
    }
}
