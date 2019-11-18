<?php

require_once 'vendor/autoload.php';

use nn\NeuralNetwork;

$nn = new NeuralNetwork(1, 1, 1);

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

// try with a loop of 50 then 100 or 500 to see the difference in the result precision
for ($i = 0; $i < 500; ++$i) {
    foreach ($training_data as $data) {
        $nn->train($data[0], $data[1]);
    }
}

var_dump($nn->feedForward([2]));
var_dump($nn->feedForward([8]));
