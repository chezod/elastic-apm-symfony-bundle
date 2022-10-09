<?php

declare(strict_types=1);

namespace ElasticApmBundle\Listener;

use ElasticApmBundle\Interactor\Config;
use ElasticApmBundle\Interactor\ElasticApmInteractorInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommandListener implements EventSubscriberInterface
{
    private $interactor;
    private $config;

    public function __construct(ElasticApmInteractorInterface $interactor, Config $config)
    {
        $this->interactor = $interactor;
        $this->config = $config;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::COMMAND => ['onConsoleCommand', 0],
            ConsoleEvents::ERROR => ['onConsoleError', 0],
        ];
    }

    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        $command = $event->getCommand();
        $input = $event->getInput();

        $this->interactor->setTransactionName($command->getName());

        foreach ($input->getOptions() as $key => $value) {
            $key = '--'.$key;
            if (\is_array($value)) {
                foreach ($value as $k => $v) {
                    $this->interactor->addCustomContext($key.'['.$k.']', $v);
                }
            } else {
                $this->interactor->addCustomContext($key, $value);
            }
        }

        foreach ($input->getArguments() as $key => $value) {
            if (\is_array($value)) {
                foreach ($value as $k => $v) {
                    $this->interactor->addCustomContext($key.'['.$k.']', $v);
                }
            } else {
                $this->interactor->addCustomContext($key, $value);
            }
        }

        $this->interactor->addContextFromConfig();
    }

    public function onConsoleError(ConsoleErrorEvent $event): void
    {
        if (! $this->config->shouldExplicitlyCollectCommandExceptions()) {
            return;
        }

        $this->interactor->addContextFromConfig();
        $this->interactor->noticeThrowable($event->getError());

        if (null !== $event->getError()->getPrevious()) {
            $this->interactor->addContextFromConfig();
            $this->interactor->noticeThrowable($event->getError()->getPrevious());
        }
    }
}
