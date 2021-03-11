<?php

declare(strict_types=1);

namespace Devscast\AirtelMoney\Data;

/**
 * Class Subscriber
 * @package Devscast\AirtelMoney\Data
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class Subscriber
{
    /**
     * required:false
     * The country of the subscriber
     */
    private ?string $country = null;

    /**
     * Currency of the subscriber
     */
    private ?string $currency = null;

    /**
     * MSISDN without the country code of the subscriber from which the payment amount deducted.
     */
    private string $msisdn;

    /**
     * Subscriber constructor.
     * @param array $data
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(array $data)
    {
        $this->country = $data['country'] ?: null;
        $this->country = $data['currency'] ?: null;
        $this->msisdn = $data['msisdn'];
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getMsisdn(): string
    {
        return $this->msisdn;
    }
}
