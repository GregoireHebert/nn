# NEURAL NETWORK


This repository purpose is to provide a toy for Machine Learning around the SymfonyLive Paris 2018.
I had to made little changes int the matrices library with no will to publish, this is why I pushed the vendor directory.
Plus, It allows you to directly test it :)

All you need to do is to play with the index.php

```php
<?php
// index.php

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
        $nn->train($data[0], $data[1]);
    }
}

var_dump($nn->feedForward([2]));
var_dump($nn->feedForward([8]));
```

Have fun :)
