<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Person;
use App\Entity\Wallet;
use App\Entity\Product;

class PersonTest extends TestCase
{
    private Person $person;
    private Person $person2;

    protected function setUp(): void
    {
        $this->person = new Person('John', 'USD');
        $this->person2 = new Person('Jane', 'EUR');
    }

    public function testGetName(): void
    {
        $this->assertEquals('John', $this->person->getName());
    }

    public function testSetName(): void
    {
        $this->person->setName('Jane');
        $this->assertEquals('Jane', $this->person->getName());
    }

    public function testGetWallet(): void
    {
        $this->assertInstanceOf(Wallet::class, $this->person->getWallet());
        $this->assertEquals('USD', $this->person->getWallet()->getCurrency());
    }

    public function testSetWallet(): void
    {
        $wallet = new Wallet('EUR');
        $this->person->setWallet($wallet);
        $this->assertEquals($wallet, $this->person->getWallet());
    }

    public function testHasFund(): void
    {
        $wallet = new Wallet('EUR');
        $this->person->setWallet($wallet);
        $this->assertTrue($this->person->hasFund());
    }

    public function testTransfertFund(): void
    {
        $person2 = new Person('Jane', 'USD');
        $this->person->getWallet()->addFund(100);
        $this->person->transfertFund(50, $person2);
        $this->assertEquals(50, $this->person->getWallet()->getBalance());
        $this->assertEquals(50, $person2->getWallet()->getBalance());
    }

    public function testTransfertFundDifferentCurrency(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Can\'t give money with different currencies');
        $person2 = new Person('Jane', 'EUR');
        $this->person->transfertFund(50, $person2);
    }

    public function testDivideWallet(): void
    {
        $person2 = new Person('Jane', 'USD');
        $person3 = new Person('Doe', 'USD');
        $this->person->getWallet()->addFund(100);
        $this->person->divideWallet([$person2, $person3]);
        $this->assertEquals(0, $this->person->getWallet()->getBalance());
        $this->assertEquals(50, $person2->getWallet()->getBalance());
        $this->assertEquals(50, $person3->getWallet()->getBalance());
    }

    public function testBuyProduct(): void
    {
        $product = $this->createMock(Product::class);
        $product->method('listCurrencies')->willReturn(['USD']);
        $product->method('getPrice')->with('USD')->willReturn(50.0);

        $this->person->getWallet()->addFund(100);
        $this->person->buyProduct($product);
        $this->assertEquals(50, $this->person->getWallet()->getBalance());
    }

    public function testBuyProductDifferentCurrency(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Can\'t buy product with this wallet currency');
        $product = $this->createMock(Product::class);
        $product->method('listCurrencies')->willReturn(['EUR']);
        $this->person->buyProduct($product);
    }
}