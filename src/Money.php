<?php

namespace KennedyTedesco\Money;

use InvalidArgumentException;
use KennedyTedesco\Money\Interfaces\MoneyInterface;
use KennedyTedesco\Money\Interfaces\CurrencyInterface;

class Money implements MoneyInterface
{
    /**
     * @var float|int
     */
    private $amount = 0;

    /**
     * @var CurrencyInterface
     */
    private $currency = null;

    /**
     * Money constructor.
     * @param int $value
     * @param CurrencyInterface|string|null $currency
     */
    public function __construct($value = 0, $currency = null)
    {
        $this->amount = $this->decorate($value);

        $this->setCurrency($currency);
    }

    /**
     * @return float|int
     */
    public function amount()
    {
        return $this->amount;
    }

    /**
     * @param $value
     * @return MoneyInterface
     */
    public function plus($value)
    {
        return $this->newFromBase(
            $this->amount + $this->decorate($value)
        );
    }

    /**
     * @param $factor
     * @return MoneyInterface
     */
    public function multiply($factor)
    {
        return $this->newFromBase(
            $this->amount * $this->decorate($factor)
        );
    }

    /**
     * @param $divisor
     * @return MoneyInterface
     */
    public function div($divisor)
    {
        return $this->newFromBase(
            $this->amount / $this->decorate($divisor)
        );
    }

    /**
     * @param $value
     * @return MoneyInterface
     */
    public function sub($value)
    {
        return $this->newFromBase(
            $this->amount - $this->decorate($value)
        );
    }

    /**
     * @param array $values
     * @return MoneyInterface
     */
    public function sum(array $values)
    {
        $sum = $this->newFromBase($this);

        foreach ($values as $value) {
            $sum = $sum->plus($value);
        }

        return $sum;
    }

    /**
     * @param $percentage
     * @return MoneyInterface
     */
    public function percents($percentage)
    {
        $money = $this->newFromBase($this);

        return $money->multiply($percentage)->div(100);
    }

    /**
     * @param $percentage
     * @return MoneyInterface
     */
    public function subPercents($percentage)
    {
        $money = $this->newFromBase($this);

        return $money->sub($money->percents($percentage));
    }

    /**
     * @param $percentage
     * @return MoneyInterface
     */
    public function plusPercents($percentage)
    {
        $money = $this->newFromBase($this);

        return $money->plus($money->percents($percentage));
    }

    /**
     * @param CurrencyInterface|string|null $currency
     */
    public function setCurrency($currency)
    {
        if ($currency instanceof CurrencyInterface) {
            $this->currency = $currency;
        } elseif (is_string($currency)) {
            $this->currency = new Currency($currency);
        }
    }

    /**
     * @param int $precision
     * @param int $roundingMode
     * @return float
     */
    public function float($precision = 2, $roundingMode = self::ROUND_HALF_UP)
    {
        return floatval(
            $this->round($this->amount(), $precision, $roundingMode)
        );
    }

    /**
     * @param int $precision
     * @param int $roundingMode
     * @param bool $usingSymbol
     * @return float|string
     */
    public function format($precision = 2, $roundingMode = self::ROUND_HALF_UP, $usingSymbol = true)
    {
        if (! $this->currency) {
            return $this->float($precision, $roundingMode);
        }

        $amount = number_format(
            $this->round($this->amount(), $this->currency->precision(), $roundingMode),
            $this->currency->precision(),
            $this->currency->decimalSeparator(),
            $this->currency->thousandSeparator()
        );

        if($usingSymbol) {
            if ($this->currency->symbolFirst()) {
                return "{$this->currency->symbol()}{$amount}";
            } else {
                return "{$amount}{$this->currency->symbol()}";
            }
        }

        return $amount;
    }

    /**
     * @param int $precision
     * @param int $roundingMode
     * @return float|string
     */
    public function formatWithoutSymbol($precision = 2, $roundingMode = self::ROUND_HALF_UP)
    {
        return $this->format($precision, $roundingMode, false);
    }

    /**
     * @param $currency
     * @return MoneyInterface
     */
    public function currency($currency)
    {
        $this->setCurrency($currency);

        return $this->newFromBase($this);
    }

    /**
     * @param null $roundingMode
     * @return void
     *
     * @throws InvalidArgumentException
     */
    private function assertRoudingMode($roundingMode = null)
    {
        $roudingModes = [
            self::ROUND_UP,
            self::ROUND_DOWN,
            self::ROUND_HALF_UP,
            self::ROUND_HALF_ODD,
            self::ROUND_HALF_DOWN,
            self::ROUND_HALF_EVEN,
            self::ROUND_HALF_NEGATIVE_INFINITY,
            self::ROUND_HALF_POSITIVE_INFINITY,
        ];

        if (! in_array($roundingMode, $roudingModes)) {
            throw new InvalidArgumentException("Invalid rouding mode.");
        }
    }

    /**
     * @param $amount
     * @param int $precision
     * @param $roundingMode
     * @return float|int
     */
    private function round($amount, $precision = 2, $roundingMode = self::ROUND_UP)
    {
        $this->assertRoudingMode($roundingMode);

        if ($roundingMode === self::ROUND_UP) {
            return intval(ceil($amount));
        } elseif ($roundingMode === self::ROUND_DOWN) {
            return intval(floor($amount));
        }

        return round($amount, $precision, $roundingMode);
    }

    /**
     * @param $value
     * @return float|int
     */
    private function decorate($value)
    {
        if ($value instanceof MoneyInterface) {
            return $value->amount();
        }

        return $value;
    }

    /**
     * @param $value
     * @return MoneyInterface
     */
    private function newFromBase($value)
    {
        return new self($value, $this->currency);
    }
}
