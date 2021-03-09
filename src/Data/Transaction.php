<?php

declare(strict_types=1);

namespace Devscast\AirtelMoney\Data;

class Transaction
{

    /**
     * required:true
     * Partner unique transaction id to identify the transaction.
     */
    private string $id;

    /**
     * number(double)
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
}
