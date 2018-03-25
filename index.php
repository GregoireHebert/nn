<?php

require_once 'vendor/autoload.php';
require_once 'NeuralNetwork.php';

$nn = new nn\NeuralNetwork(1, 4, 1);

$trainingData = [
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

for ($i=0; $i<20000; $i++) {

    try {
        $r = random_int(0, count($trainingData) - 1);
        $data = $trainingData[$r];

        $nn->train($data[0], $data[1]);
    } catch (Exception $e) {
        die ($e->getMessage());
    }
}

var_dump('0 =>'.json_encode($nn->feedForward([0])));
var_dump('1 =>'.json_encode($nn->feedForward([1])));
var_dump('2 =>'.json_encode($nn->feedForward([2])));
var_dump('3 =>'.json_encode($nn->feedForward([3])));
var_dump('4 =>'.json_encode($nn->feedForward([4])));
var_dump('5 =>'.json_encode($nn->feedForward([5])));
var_dump('6 =>'.json_encode($nn->feedForward([6])));
var_dump('7 =>'.json_encode($nn->feedForward([7])));
var_dump('8 =>'.json_encode($nn->feedForward([8])));
var_dump('9 =>'.json_encode($nn->feedForward([9])));
var_dump('10 =>'.json_encode($nn->feedForward([10])));
