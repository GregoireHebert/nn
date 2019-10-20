<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Commands\LifeCommand;

class CircleOfLifeHandler implements HandlerInterface
{
    public $priority = -1;

    /**
     * @param LifeCommand $command
     */
    public function handle($command): void
    {
        $mouton = $command->getMouton();

        $mouton->setHunger($mouton->getHunger() + 1);
        $mouton->setSleepiness($mouton->getSleepiness() + 1);
        $mouton->setPlayfulness($mouton->getPlayfulness() + 1);

        $life = $mouton->getLife();

        if ($mouton->getHunger() > 7) {
            --$life;
        }

        if ($mouton->getSleepiness() > 7) {
            --$life;
        }

        if ($mouton->getPlayfulness() > 7) {
            --$life;
        }

        $mouton->setLife($life);
    }

    public function support($command): bool
    {
        return $command instanceof LifeCommand;
    }
}
