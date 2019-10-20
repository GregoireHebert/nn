<?php

declare(strict_types=1);

namespace App\Bus;

use App\Commands\LifeCommand;
use App\Handlers\HandlerInterface;

class LifeBus implements Bus
{
    private $handlers = [];

    public function addHandler(HandlerInterface $handler): void
    {
        $this->handlers[] = $handler;
    }

    /**
     * @param LifeCommand $command
     */
    public function execute($command): void
    {
        /*
         * @var HandlerInterface $handler
         */
        usort($this->handlers, function (HandlerInterface $a, HandlerInterface $b) {
            return $b->priority <=> $a->priority;
        });

        foreach ($this->handlers as $handler) {
            if ($handler->support($command)) {
                $handler->handle($command);
            }
        }
    }

    public function setHandlers(array $handlers): void
    {
        $this->handlers = $handlers;
    }
}
