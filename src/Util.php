<?php

declare(strict_types=1);

namespace nn;

/**
 * @author Grégoire Hébert gregoire@les-tilleuls.coop
 */
class Util
{
    /**
     * Sigmoid function
     *
     * @param int $x
     *
     * @return float
     */
    public static function sigmoid(int $x): float
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
    public static function dsigmoid(float $x): float
    {
        return  $x * (1 - $x);
    }
}
