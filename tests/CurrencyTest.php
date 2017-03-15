<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use KennedyTedesco\Money\Currency;

class CurrencyTest extends TestCase
{
    public function testCurrency()
    {
        $brl = new Currency('BRL');
        $this->assertEquals($brl->code(), 'BRL');
        $this->assertEquals($brl->name(), 'Brazilian Real');
        $this->assertEquals($brl->symbol(), 'R$ ');
        $this->assertEquals($brl->precision(), 2);
        $this->assertEquals($brl->decimalSeparator(), ',');
        $this->assertEquals($brl->thousandSeparator(), '.');
        $this->assertEquals($brl->symbolFirst(), true);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testNotFoundCurrency()
    {
        $foo = new Currency('FOO');
    }
}
