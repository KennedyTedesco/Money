<?php

namespace KennedyTedesco\Money;

use UnexpectedValueException;
use KennedyTedesco\Money\Interfaces\CurrencyInterface;

class Currency implements CurrencyInterface
{
    /**
     * @var array
     */
    protected $currency = [];

    /**
     * @var array
     */
    protected static $currencies = [];

    /**
     * Currency constructor.
     * @param $code
     */
    public function __construct($code)
    {
        $this->currency = $this->getCurrency($code);
    }

    /**
     * @return mixed
     */
    public function code()
    {
        return $this->currency['code'];
    }

    /**
     * @return mixed
     */
    public function symbol()
    {
        return $this->currency['symbol'];
    }

    /**
     * @return int
     */
    public function precision()
    {
        return $this->currency['precision'];
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->currency['name'];
    }

    /**
     * @return string
     */
    public function thousandSeparator()
    {
        return $this->currency['thousand_separator'];
    }

    /**
     * @return string
     */
    public function decimalSeparator()
    {
        return $this->currency['decimal_separator'];
    }

    /**
     * @return bool
     */
    public function symbolFirst()
    {
        return $this->currency['symbol_first'];
    }

    /**
     * @param $name
     * @return mixed
     */
    protected function getCurrency($name)
    {
        if (empty(self::$currencies)) {
            self::$currencies = require __DIR__.'/data/currencies.php';
        }

        if (isset(self::$currencies[$name])) {
            return self::$currencies[$name];
        }

        throw new UnexpectedValueException("The currency {$name} wasn't found.");
    }
}
