<?php

declare(strict_types=1);

namespace nn;

use NumPHP\Core\NumArray as Matrix;

/**
 * @author Grégoire Hébert <gregoire@les-tilleuls.coop>
 */
class NeuralNetwork
{
    /**
     * @var Matrix weights from input to hidden
     */
    private $weights_ih;

    /**
     * @var Matrix weights from hidden to output
     */
    private $weights_ho;

    /**
     * @var Matrix Bias applied to the hidden result
     */
    private $bias_h;

    /**
     * @var Matrix Bias applied to the output result
     */
    private $bias_o;

    /**
     * @var float learning rate
     */
    private $learning_rate = 0.3;

    /**
     * @throws \Exception
     */
    public function __construct(int $inputLayers, int $hiddenLayers, int $outputLayers)
    {
        $this->weights_ih = new Matrix($this->initMatrix($hiddenLayers, $inputLayers));
        $this->weights_ho = new Matrix($this->initMatrix($outputLayers, $hiddenLayers));

        $this->bias_h = new Matrix($this->initMatrix($hiddenLayers, 1));
        $this->bias_o = new Matrix($this->initMatrix($outputLayers, 1));
    }

    public function setLearningRate(float $lr): void
    {
        $this->learning_rate = $lr;
    }

    /**
     * Initialize a matrix with random numbers between 0 and 1 included.
     *
     * @throws \Exception
     */
    private function initMatrix(int $rows, int $columns): array
    {
        $matrixData = array_fill(0, $rows, 0);

        array_walk($matrixData, function (&$value) use ($columns) {
            $value = array_fill(0, $columns, 0);
        });

        array_walk_recursive($matrixData, function (&$value) {
            $value = lcg_value();
        });

        return $matrixData;
    }

    /**
     * Transform a matrix data to a flat array
     */
    private function dataToFlat(array $data): array
    {
        $flat = [];

        array_walk_recursive($data, function ($value) use(&$flat) {
            $flat[] = $value;
        });

        return $flat;
    }

    /**
     * Transform a flat array to a matrix data
     */
    private function flatToData(array $flat): array
    {
        $data = array_fill(0, \count($flat), []);
        foreach ($flat as $key=>$value) {
            $data[$key][0] = $value;
        }

        return $data;
    }

    /**
     * Feed in the data to analyze, it'll return the computed result as a flat array.
     */
    public function feedForward(array $inputs): array
    {
        $inputMatrix = new Matrix($this->flatToData($inputs));

        $hidden = clone $this->weights_ih;
        $hidden = $hidden->dot($inputMatrix);
        $hidden->add($this->bias_h);
        $hidden->map(Util::class.'::sigmoid'); // activation

        $output = clone $this->weights_ho;
        $output = $output->dot($hidden);
        $output->add($this->bias_o);
        $output->map(Util::class.'::sigmoid'); // activation

        return $this->dataToFlat($output->getData());
    }

    /**
     * Function you need to perform to train you neural network.
     * For proper results, you need to pass in the different cases randomly.
     *
     * @throws \Exception
     */
    public function train(array $arrayInputs, array $arrayExpected): void
    {
        $inputs = new Matrix($this->flatToData($arrayInputs));

        // feed forward
        $hidden = clone $this->weights_ih;
        $hidden = $hidden->dot($inputs);
        $hidden->add($this->bias_h);
        $hidden->map(Util::class.'::sigmoid');

        $outputs = clone $this->weights_ho;
        $outputs = $outputs->dot($hidden);
        $outputs->add($this->bias_o);
        $outputs->map(Util::class.'::sigmoid');

        // back propagation // Linear Gradient Descent // Output -> Hidden
        $expected = new Matrix($arrayExpected);
        $output_errors = clone $expected;
        $output_errors = $output_errors->sub($outputs);

        $gradient = clone $outputs;
        $gradient->map(Util::class.'::dsigmoid');
        $gradient->dot($output_errors);
        $gradient->dot($this->learning_rate);

        $hidden_t = $hidden->getTranspose();
        $weight_ho_deltas = clone $gradient;
        $weight_ho_deltas = $weight_ho_deltas->dot($hidden_t);

        $this->weights_ho->add($weight_ho_deltas);
        $this->bias_o->add($gradient);

        // back propagation // Linear Gradient Descent // Hidden -> Input
        $who_t = $this->weights_ho->getTranspose();
        $hidden_error = clone $who_t;
        $hidden_error = $hidden_error->dot($output_errors);

        $hidden_gradient = clone $hidden;
        $hidden_gradient->map(Util::class.'::dsigmoid');
        $hidden_gradient->mult($hidden_error);
        $hidden_gradient->dot($this->learning_rate);

        $inputs_t = $inputs->getTranspose();

        $weight_ih_deltas = clone $hidden_gradient;
        $weight_ih_deltas = $weight_ih_deltas->dot($inputs_t);

        $this->weights_ih->add($weight_ih_deltas);
        $this->bias_h->add($hidden_gradient);
    }
}
