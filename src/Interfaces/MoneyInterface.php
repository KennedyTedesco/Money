<?php

namespace KennedyTedesco\Money\Interfaces;

interface MoneyInterface
{
    /**
     * @const int
     */
    const ROUND_UP = 5;

    /**
     * @const int
     */
    const ROUND_DOWN = 6;

    /**
     * @const int
     */
    const ROUND_HALF_POSITIVE_INFINITY = 7;

    /**
     * @const int
     */
    const ROUND_HALF_NEGATIVE_INFINITY = 8;

    /**
     * @const int
     */
    const ROUND_HALF_UP = PHP_ROUND_HALF_UP;

    /**
     * @const int
     */
    const ROUND_HALF_DOWN = PHP_ROUND_HALF_DOWN;

    /**
     * @const int
     */
    const ROUND_HALF_EVEN = PHP_ROUND_HALF_EVEN;

    /**
     * @const int
     */
    const ROUND_HALF_ODD = PHP_ROUND_HALF_ODD;

    /**
     * @param $value
     * @return MoneyInterface
     */
    public function plus($value);

    /**
     * @param $factor
     * @return MoneyInterface
     */
    public function multiply($factor);

    /**
     * @param $divisor
     * @return MoneyInterface
     */
    public function div($divisor);

    /**
     * @param $value
     * @return MoneyInterface
     */
    public function sub($value);

    /**
     * @param array $values
     * @return MoneyInterface
     */
    public function sum(array $values);

    /**
     * @param $percentage
     * @return MoneyInterface
     */
    public function percents($percentage);

    /**
     * @param $percentage
     * @return MoneyInterface
     */
    public function subPercents($percentage);

    /**
     * @param $percentage
     * @return MoneyInterface
     */
    public function plusPercents($percentage);

    /**
     * @param int $precision
     * @param int $roundingMode
     * @return mixed
     */
    public function float($precision = 2, $roundingMode = self::ROUND_HALF_UP);

    /**
     * @param int $precision
     * @param int $round
     * @return mixed
     */
    public function format($precision = 2, $round = self::ROUND_HALF_UP);

    /**
     * @return float|int
     */
    public function amount();
}
