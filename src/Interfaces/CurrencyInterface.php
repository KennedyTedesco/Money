<?php

namespace KennedyTedesco\Money\Interfaces;

interface CurrencyInterface
{
    /**
     * @return mixed
     */
    public function code();

    /**
     * @return mixed
     */
    public function symbol();

    /**
     * @return int
     */
    public function precision();

    /**
     * @return string
     */
    public function name();

    /**
     * @return string
     */
    public function thousandSeparator();

    /**
     * @return string
     */
    public function decimalSeparator();

    /**
     * @return bool
     */
    public function symbolFirst();
}
