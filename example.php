<?php

require_once 'vendor/autoload.php';

use nn\NeuralNetwork;

$nn = new NeuralNetwork(1, 3, 1);

$training_data = [
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

// try with a loop of 500 then 1000 or 2000 to see the difference in the result precision
for ($i = 0; $i < 200; ++$i) {
    foreach ($training_data as $data) {
        $nn->train($data[0], $data[1]);
    }
}

var_dump($nn->feedForward([2]));
var_dump($nn->feedForward([8]));
