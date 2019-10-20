<?php

declare(strict_types=1);

namespace App\Entity;

class Mouton
{
    private $id;
    private $life = 10;
    private $hunger = 0;
    private $sleepiness = 0;
    private $playfulness = 0;
    private $lastActionTaken = null;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getLife(): int
    {
        return (int) $this->life;
    }

    public function setLife(int $life): void
    {
        $this->life = min(max($life, 0), 10);
    }

    public function getHunger(): int
    {
        return (int) $this->hunger;
    }

    public function setHunger(int $hunger): void
    {
        $this->hunger = min(max($hunger, 0), 10);
    }

    public function getSleepiness(): int
    {
        return (int) $this->sleepiness;
    }

    public function setSleepiness(int $sleepiness): void
    {
        $this->sleepiness = min(max($sleepiness, 0), 10);
    }

    public function getPlayfulness(): int
    {
        return (int) $this->playfulness;
    }

    public function setPlayfulness(int $playfulness): void
    {
        $this->playfulness = min(max($playfulness, 0), 10);
    }

    /**
     * @return null
     */
    public function getLastActionTaken()
    {
        return $this->lastActionTaken;
    }

    /**
     * @param null $lastActionTaken
     */
    public function setLastActionTaken($lastActionTaken): void
    {
        $this->lastActionTaken = $lastActionTaken;
    }
}
