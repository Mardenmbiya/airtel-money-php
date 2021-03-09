<?php

declare(strict_types=1);

namespace Devscast\AirtelMoney\Data;

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
}
