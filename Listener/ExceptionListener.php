<?php

declare(strict_types=1);

namespace ElasticApmBundle\Listener;

use ElasticApmBundle\Interactor\Config;
use ElasticApmBundle\Interactor\ElasticApmInteractorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Listen to exceptions dispatched by Symfony to log them to Elastic APM.
 */
class ExceptionListener implements EventSubscriberInterface
{
    private $interactor;
    private $ignoredExceptions;

    public function __construct(ElasticApmInteractorInterface $interactor, array $ignoredExceptions)
    {
        $this->interactor = $interactor;
        $this->ignoredExceptions = $ignoredExceptions;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 0],
        ];
    }

    /**
     * @param GetResponseForExceptionEvent|ExceptionEvent $event
     */
    public function onKernelException(KernelExceptionEvent $event): void
    {
        $exception = \method_exists($event, 'getThrowable') ? $event->getThrowable() : $event->getException();
        if (! $exception instanceof HttpExceptionInterface && ! in_array(get_class($exception), $this->ignoredExceptions)) {
            $this->interactor->addContextFromConfig();
            $this->interactor->noticeThrowable($exception);
        }
    }
}

if (! \class_exists(KernelExceptionEvent::class)) {
    if (\class_exists(ExceptionEvent::class)) {
        \class_alias(ExceptionEvent::class, KernelExceptionEvent::class);
    } else {
        \class_alias(GetResponseForExceptionEvent::class, KernelExceptionEvent::class);
    }
}
