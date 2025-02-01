<?php

use PHPUnit\Framework\TestCase;
use App\Entity\Wallet;

class WalletTest extends TestCase
{
    public function testConstructorSetsInitialBalanceAndCurrency()
    {
        $wallet = new Wallet('USD');
        $this->assertEquals(0, $wallet->getBalance());
        $this->assertEquals('USD', $wallet->getCurrency());
    }

    public function testSetBalance()
    {
        $wallet = new Wallet('USD');
        $wallet->setBalance(100);
        $this->assertEquals(100, $wallet->getBalance());
    }

    public function testSetBalanceThrowsExceptionForNegativeBalance()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid balance');
        $wallet = new Wallet('USD');
        $wallet->setBalance(-100);
    }

    public function testSetCurrency()
    {
        $wallet = new Wallet('USD');
        $wallet->setCurrency('EUR');
        $this->assertEquals('EUR', $wallet->getCurrency());
    }

    public function testSetCurrencyThrowsExceptionForInvalidCurrency()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid currency');
        $wallet = new Wallet('USD');
        $wallet->setCurrency('GBP');
    }

    public function testAddFund()
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(50);
        $this->assertEquals(50, $wallet->getBalance());
    }

    public function testAddFundThrowsExceptionForNegativeAmount()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid amount');
        $wallet = new Wallet('USD');
        $wallet->addFund(-50);
    }

    public function testRemoveFund()
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100);
        $wallet->removeFund(50);
        $this->assertEquals(50, $wallet->getBalance());
    }

    public function testRemoveFundThrowsExceptionForNegativeAmount()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid amount');
        $wallet = new Wallet('USD');
        $wallet->removeFund(-50);
    }

    public function testRemoveFundThrowsExceptionForInsufficientFunds()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient funds');
        $wallet = new Wallet('USD');
        $wallet->removeFund(50);
    }
}