<?php

declare(strict_types=1);

namespace Devscast\AirtelMoney\Data;

/**
 * Class Balance
 * @package Devscast\AirtelMoney\Data
 * @author bernard-ng <ngandubernard@gmail.com>
 */
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

    /**
     * Balance constructor.
     * @param array $data
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(array $data)
    {
        $this->balance = $data['balance'];
        $this->currency = $data['currency'];
        $this->account_status = $data['account_status'];
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getAccountStatus(): string
    {
        return $this->account_status;
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return float
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getBalance(): float
    {
        return $this->balance;
    }
}
