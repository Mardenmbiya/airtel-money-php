<?php

declare(strict_types=1);

namespace Devscast\AirtelMoney\Data;

use Webmozart\Assert\Assert;

/**
 * Class Payment
 * @package Devscast\AirtelMoney\Data
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class Payment
{
    /**
     * required:true
     * Reference for service / goods purchased.
     */
    private string $reference;

    /**
     * Subscriber | Client | customer
     */
    private Subscriber $subscriber;

    /**
     * Transaction detail
     */
    private Transaction $transaction;

    /**
     * Payment constructor.
     * Do not sent country code in msisdn.
     * @param string $reference
     * @param float $amount
     * @param string $msisdn
     * @param string $country
     * @param string $currency
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(
        string $reference,
        float $amount,
        string $msisdn,
        string $country = 'CD',
        string $currency = 'CDF'
    ) {
        Assert::stringNotEmpty($reference);

        $this->reference = $reference;

        // the client (subscriber) and the transaction must have the same currency and be from the same country
        // even if it is optional, it is important to have this information to avoid surprises from the api
        $this->subscriber = new Subscriber([
            'country' => $country,
            'currency' => $currency,
            'msisdn' => $msisdn
        ]);

        // Partner unique transaction id to identify the transaction.
        // TODO: transaction key customization
        $this->transaction = new Transaction([
            'amount' => $amount,
            'country' => $country,
            'current' => $currency,
            'id' => uniqid('airtel_transaction_')
        ]);
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @return Subscriber
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getSubscriber(): Subscriber
    {
        return $this->subscriber;
    }

    /**
     * @return Transaction
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }
}
