<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use KennedyTedesco\Money\Money;

class MoneyTest extends TestCase
{
    public function testMoney()
    {
        $money = new Money(10);

        $this->assertEquals(10, $money->amount());
    }

    public function testImmutability()
    {
        $money1 = new Money(10);
        $money2 = $money1->plus(10);
        $money3 = $money1->plus(20)->sub(10)->div(2)->plus(1);
        $money4 = $money3;

        $this->assertEquals(spl_object_hash($money3), spl_object_hash($money4));
        $this->assertEquals(spl_object_hash($money1), spl_object_hash($money1));
        $this->assertNotEquals(spl_object_hash($money1), spl_object_hash($money2));
        $this->assertNotEquals(spl_object_hash($money1), spl_object_hash($money1->sub(2)));

        $this->assertEquals(10, $money1->amount());
        $this->assertEquals(20, $money2->amount());
        $this->assertEquals(11, $money3->amount());
    }

    public function testPlus()
    {
        $money1 = new Money(10);
        $money2 = (new Money($money1))->plus($money1)->plus(30);

        $this->assertEquals(10, $money1->amount());
        $this->assertEquals(50, $money2->amount());
    }

    public function testMultiply()
    {
        $money1 = (new Money)->plus(new Money(10))->multiply(new Money(2));
        $money2 = $money1->multiply($money1)->multiply(2);

        $this->assertEquals(20, $money1->amount());
        $this->assertEquals(800, $money2->amount());
    }

    public function testDiv()
    {
        $money1 = (new Money)->plus(new Money(100))->div(new Money(2));
        $money2 = $money1->plus(50)->div($money1)->div(2);

        $this->assertEquals(50, $money1->amount());
        $this->assertEquals(1, $money2->amount());
    }

    public function testSub()
    {
        $money1 = (new Money(50))->sub(new Money(10));
        $money2 = (new Money($money1))->sub(10)->plus(20)->sub($money1);

        $this->assertEquals(40, $money1->amount());
        $this->assertEquals(10, $money2->amount());
    }

    public function testSum()
    {
        $amounts = [
            10,
            new Money(10),
            (new Money(20))->sub(10),
        ];

        $money1 = (new Money(10))->sum($amounts);
        $money2 = (new Money($money1))->sum($amounts);
        $money3 = $money2->sum($amounts);

        $this->assertEquals(40, $money1->amount());
        $this->assertEquals(70, $money2->amount());
        $this->assertEquals(100, $money3->amount());
    }

    public function testPercents()
    {
        $money1 = (new Money(100))->percents(new Money(10));

        $this->assertEquals(1, $money1->percents(10)->amount());
        $this->assertEquals(10, $money1->amount());
    }

    public function subPercents()
    {
        $money1 = (new Money(100))->subPercents(new Money(10));

        $this->assertEquals(72, $money1->subPercents(20)->amount());
        $this->assertEquals(90, $money1->amount());
    }

    public function plusPercents()
    {
        $money1 = (new Money(100))->plusPercents(new Money(10));

        $this->assertEquals(132, $money1->plusPercents(20)->amount());
        $this->assertEquals(110, $money1->amount());
    }

    public function testMoneyFormatted()
    {
        $money = (new Money(20, 'BRL'))->sub(1.57)->div(2);

        $this->assertEquals($money->format(), 'R$ 9,22');
        $this->assertEquals($money->amount(), 9.215);
    }

    public function testFloat()
    {
        $money = (new Money(20, 'BRL'))->sub(1.57)->div(2);
        $this->assertEquals($money->float(), 9.22);

        $money = (new Money(20, 'BRL'))->sub(2);
        $this->assertEquals($money->float(), 18);
        $this->assertTrue(is_float($money->float()));
    }

    public function testCurrency()
    {
        $money = new Money(20);

        $this->assertEquals($money->currency('BRL')->format(), 'R$ 20,00');
    }
}
