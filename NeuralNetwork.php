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
     * @param int $inputLayers
     * @param int $hiddenLayers
     * @param int $outputLayers
     *
     * @throws \Exception
     */
    public function __construct(int $inputLayers, int $hiddenLayers, int $outputLayers)
    {
        $this->weights_ih = new Matrix($this->initMatrixArray($hiddenLayers, $inputLayers));
        $this->weights_ho = new Matrix($this->initMatrixArray($outputLayers, $hiddenLayers));

        $this->bias_h = new Matrix($this->initMatrixArray($hiddenLayers, 1));
        $this->bias_o = new Matrix($this->initMatrixArray($outputLayers, 1));
    }

    /**
     * Initialize a matrix with random numbers between 0 and 1 included.
     *
     * @param int $rows
     * @param int $columns
     *
     * @return array
     *
     * @throws \Exception
     */
    private function initMatrixArray(int $rows, int $columns): array
    {
        $matrixData = array_fill(0, $rows, 0);

        array_walk($matrixData, function (&$value) use ($columns) {
            $value = array_fill(0, $columns, 0);
        });

        array_walk_recursive($matrixData, function (&$value) {
            $value = $this->getRandom();
        });

        return $matrixData;
    }

    /**
     * Return random float between 0 and 1 included.
     *
     * @return float
     *
     * @throws \Exception
     */
    private function getRandom(): float
    {
        return random_int(0,100)/100;
    }

    /**
     * Transform a matrix data to a flat array
     *
     * @param array $data
     * @return array
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
     *
     * @param $flat
     *
     * @return array
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
     *
     * @param array $inputs
     *
     * @return array
     */
    public function feedForward(array $inputs): array
    {
        $inputMatrix = new Matrix($this->flatToData($inputs));

        $hidden = clone $this->weights_ih;
        $hidden = $hidden->dot($inputMatrix);
        $hidden->add($this->bias_h);
        $hidden->map([$this, 'sigmoid']); // activation

        $output = clone $this->weights_ho;
        $output = $output->dot($hidden);
        $output->add($this->bias_o);
        $output->map([$this, 'sigmoid']); // activation

        return $this->dataToFlat($output->getData());
    }

    /**
     * Sigmoid function
     *
     * @param int $x
     *
     * @return float
     */
    public function sigmoid(int $x): float
    {
        return  1 / (1 + exp(-$x));
    }

    /**
     * The derivative of the sigmoid function is `sigmoid(x) * (1 - sigmoid(x))`
     * but since I use already a sigmoidal value for x, I don't need to perform it again, so I'll compute `x * (1 - x)`
     *
     * @param float $x
     *
     * @return float
     */
    public function dsigmoid(float $x): float
    {
        return  $x * (1 - $x);
    }

    /**
     * Function you need to perform to train you neural network.
     * For proper results, you need to pass in the different cases randomly.
     *
     * @param array $arrayInputs
     * @param array $arrayExpected
     *
     * @throws \Exception
     */
    public function train(array $arrayInputs, array $arrayExpected): void
    {
        $inputs = new Matrix($this->flatToData($arrayInputs));

        $hidden = clone $this->weights_ih;
        $hidden = $hidden->dot($inputs);
        $hidden->add($this->bias_h);
        $hidden->map([$this, 'sigmoid']); // activation

        $outputs = clone $this->weights_ho;
        $outputs = $outputs->dot($hidden);
        $outputs->add($this->bias_o);
        $outputs->map([$this, 'sigmoid']); // activation

        //////

        $expected = new Matrix($arrayExpected);
        $output_errors = clone $expected;
        $output_errors = $output_errors->sub($outputs);

        $gradient = clone $outputs;
        $gradient->map([$this, 'dsigmoid']);
        $gradient->dot($output_errors);
        $gradient->dot($this->learning_rate);

        $hidden_t = $hidden->getTranspose();
        $weight_ho_deltas = clone $gradient;
        $weight_ho_deltas = $weight_ho_deltas->dot($hidden_t);

        $this->weights_ho->add($weight_ho_deltas);
        $this->bias_o->add($gradient);

        /////
        /////

        $who_t = $this->weights_ho->getTranspose();
        $hidden_error = clone $who_t;
        $hidden_error = $hidden_error->dot($output_errors);


        $hidden_gradient = clone $hidden;
        $hidden_gradient->map([$this, 'dsigmoid']);
        $hidden_gradient->mult($hidden_error);
        $hidden_gradient->dot($this->learning_rate);

        $inputs_t = $inputs->getTranspose();

        $weight_ih_deltas = clone $hidden_gradient;
        $weight_ih_deltas = $weight_ih_deltas->dot($inputs_t);

        $this->weights_ih->add($weight_ih_deltas);
        $this->bias_h->add($hidden_gradient);
    }
}
