<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Commands\LifeCommand;
use App\NeuralNetwork\Tamagotchi;
use App\Repository\MoutonRepository;

class DatabaseHandler implements HandlerInterface
{
    private $repository;
    public $priority = -2;

    public function __construct(MoutonRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param LifeCommand $command
     */
    public function handle($command): void
    {
        $mouton = $command->getMouton();
        $this->repository->update($mouton);
    }

    public function support($command): bool
    {
        return $command instanceof LifeCommand;
    }
}
