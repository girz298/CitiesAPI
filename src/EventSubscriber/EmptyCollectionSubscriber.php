<?php

namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class EmptyCollectionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['isResultEmpty', EventPriorities::POST_SERIALIZE],
        ];
    }

    public function isResultEmpty(GetResponseForControllerResultEvent $event)
    {
        if ($event->getControllerResult() === '[]') {
            throw new NotFoundHttpException('Not Found');
        }
    }
}