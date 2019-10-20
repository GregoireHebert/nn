<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Commands\LifeCommand;

class RunHandler implements HandlerInterface
{
    public const ACTION = 'run';
    public $priority = 0;

    private $nn;

    public function __construct($nn)
    {
        $this->nn = $nn;
    }

    /**
     * @param LifeCommand $command
     */
    public function handle($command): void
    {
        $mouton = $command->getMouton();
        $mouton->setHunger($mouton->getHunger() + 2);
        $mouton->setSleepiness($mouton->getSleepiness() + 2);
        $mouton->setPlayfulness($mouton->getPlayfulness() - 5);
        $command->setTaken(self::ACTION);
    }

    public function support($command): bool
    {
        return $command instanceof LifeCommand && null === $command->isTaken() && $this->nn->shouldI($command->getMouton()->getPlayfulness());
    }
}
