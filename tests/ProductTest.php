<?php

use PHPUnit\Framework\TestCase;
use App\Entity\Product;

class ProductTest extends TestCase
{
    public function testConstructorAndGetters()
    {
        $product = new Product('Apple', ['USD' => 1.0, 'EUR' => 0.9], 'food');
        $this->assertEquals('Apple', $product->getName());
        $this->assertEquals(['USD' => 1.0, 'EUR' => 0.9], $product->getPrices());
        $this->assertEquals('food', $product->getType());
    }

    public function testSetName()
    {
        $product = new Product('Apple', ['USD' => 1.0, 'EUR' => 0.9], 'food');
        $product->setName('Banana');
        $this->assertEquals('Banana', $product->getName());
    }

    public function testSetPrices()
    {
        $product = new Product('Apple', ['USD' => 1.0, 'EUR' => 0.9], 'food');
        $product->setPrices(['USD' => 1.2, 'EUR' => 1.1]);
        $this->assertEquals(['USD' => 1.2, 'EUR' => 1.1], $product->getPrices());
    }

    public function testSetType()
    {
        $product = new Product('Apple', ['USD' => 1.0, 'EUR' => 0.9], 'food');
        $product->setType('tech');
        $this->assertEquals('tech', $product->getType());
    }

    public function testSetTypeInvalid()
    {
        $this->expectException(\Exception::class);
        $product = new Product('Apple', ['USD' => 1.0, 'EUR' => 0.9], 'food');
        $product->setType('invalid');
    }

    public function testGetTVA()
    {
        $product = new Product('Apple', ['USD' => 1.0, 'EUR' => 0.9], 'food');
        $this->assertEquals(0.1, $product->getTVA());

        $product->setType('tech');
        $this->assertEquals(0.2, $product->getTVA());
    }

    public function testListCurrencies()
    {
        $product = new Product('Apple', ['USD' => 1.0, 'EUR' => 0.9], 'food');
        $this->assertEquals(['USD', 'EUR'], $product->listCurrencies());
    }

    public function testGetPrice()
    {
        $product = new Product('Apple', ['USD' => 1.0, 'EUR' => 0.9], 'food');
        $this->assertEquals(1.0, $product->getPrice('USD'));
        $this->assertEquals(0.9, $product->getPrice('EUR'));
    }

    public function testGetPriceInvalidCurrency()
    {
        $this->expectException(\Exception::class);
        $product = new Product('Apple', ['USD' => 1.0, 'EUR' => 0.9], 'food');
        $product->getPrice('GBP');
    }

    public function testGetPriceUnavailableCurrency()
    {
        $this->expectException(\Exception::class);
        $product = new Product('Apple', ['USD' => 1.0, 'EUR' => 0.9], 'food');
        $product->getPrice('JPY');
    }
}