<?php

declare(strict_types=1);

namespace App\Commands;

use App\Entity\Mouton;

class LifeCommand
{
    private $mouton;
    private $action;
    private $taken;

    public function __construct(Mouton $mouton, string $action)
    {
        $this->mouton = $mouton;
        $this->action = $action;
    }

    public function getMouton(): Mouton
    {
        return $this->mouton;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setTaken($action): void
    {
        $this->taken = $action;
    }

    public function isTaken(): ?string
    {
        return $this->taken;
    }
}
