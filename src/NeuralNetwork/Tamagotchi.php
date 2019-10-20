<?php

declare(strict_types=1);

namespace App\NeuralNetwork;

use nn\NeuralNetwork;

class Tamagotchi
{
    private const TRAINING_DATA = [
        [[0], [0]],
        [[1], [0]],
        [[2], [0]],
        [[3], [0]],
        [[4], [0]],
        [[5], [0]],
        [[6], [1]],
        [[7], [1]],
        [[8], [1]],
        [[9], [1]],
        [[10], [1]],
    ];

    private $nn;

    public function __construct()
    {
        $this->nn = new NeuralNetwork(1, 3, 1);

        for ($i = 0; $i < 200; ++$i) {
            foreach (self::TRAINING_DATA as $data) {
                $this->nn->train(...$data);
            }
        }
    }

    public function shouldI($value): bool
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        return (bool) round($this->nn->feedForward($value)[0] ?? 0);
    }
}
