# NEURAL NETWORK

This repository purpose is to provide a toy for Machine Learning around the SymfonyLive Paris 2018 and SymfonyCon Amsterdam 2019

## prerequisite

* php > 7.1
* composer (http://getcomposer.org)

## Installation

```shell
composer install
```

## Usage

``` shell
php example.php
```

```php
<?php
// example.php

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

for ($i = 0; $i < 200; ++$i) {
    foreach ($training_data as $data) {
        $nn->train(...$data);
    }
}

var_dump($nn->feedForward([2]));
var_dump($nn->feedForward([8]));
```

Have fun :)
