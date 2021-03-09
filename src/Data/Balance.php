<?php

declare(strict_types=1);

namespace Devscast\AirtelMoney\Data;

use Webmozart\Assert\Assert;

class Balance 
{

    /**
     * balance
     */
    private float $balance;

    /**
     * currency
     */
    private string $currency;

    /**
     * Status of Account
     */
    private string $account_status;

    public function __construct(float $balance, string $currency, string $account_status)
    {

        Assert::float($balance);
        Assert::stringNotEmpty($currency);
        Assert::stringNotEmpty($account_status);

        $this->balance = $balance;
        $this->currency = $currency;
        $this->account_status = $account_status;
    }


    public function getAccountStatus(): string
    {
        return $this->account_status;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }
}
