<?php

declare(strict_types=1);

namespace Devscast\AirtelMoney\Data;

/**
 * Class Transaction
 * @package Devscast\AirtelMoney\Data
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class Transaction
{
    /**
     * required:true
     * Partner unique transaction id to identify the transaction.
     */
    private string $id;

    /**
     * required:true
     * Transaction amount which will be deducted from subscriber's wallet.
     */
    private float $amount;

    /**
     * required:false
     * The currency in which the transaction is happening,
     * basically used for cross border payments.
     * For the same country, this field is not required
     */
    private ?string $currency = null;

    /**
     * required:false
     * The country in which the transaction is happening,
     * basically used for cross border payments.
     * For the same country, this field is not required
     */
    private ?string $country = null;

    /**
     * Transaction constructor.
     * @param array $data
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->amount = $data['amount'];
        $this->currency = $data['currency'] ?: null;
        $this->country = $data['country'] ?: null;
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return float
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getCountry(): string
    {
        return $this->country;
    }
}
