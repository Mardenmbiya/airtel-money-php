<?php

declare(strict_types=1);

namespace Devscast\AirtelMoney\Data;

use Webmozart\Assert\Assert;

/**
 * This API is used to get the bearer token.
 * The output of this API contains access_token that will be used as bearer token
 * for the API that we will be going to call.
 *
 * Class Configuration
 * @package Devscast\AirtelMoney\Data
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class Configuration
{
    /**
     * mandatory
     * The client_id is a public identifier for apps.
     * It must also be unique across all clients that the authorization server handles.
     * This is equivalent to consumer key displayed under keys section of application listing.
     * Example: c02e9e46-db9d-4faf-b91a-94f88bbe688c
     */
    private string $client_id;

    /**
     * mandatory
     * The client_secret is a secret known only to the application and the authorization server.
     * This is equivalent to consumer secret displayed under keys section of application listing
     * Example: ab672211-4197-4c11-ba79-b29ce2034ca2
     */
    private string $client_secret;

    /**
     * mandatory
     * The Client Credential grant type is used by confidential and public clients to fetch access token.
     * Example: client_credentials
     */
    private string $grant_type = 'client_credentials';

    /**
     * Transaction Country
     */
    private string $country;

    /**
     * Transaction Currency
     */
    private string $currency;

    /**
     * API environment prod or dev
     */
    private string $environment;

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getClientId(): string
    {
        return $this->client_id;
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getClientSecret(): string
    {
        return $this->client_secret;
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getCountry(): string
    {
        return $this->country;
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
     * @param string $client_id
     * @return Configuration
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setClientId(string $client_id): self
    {
        Assert::stringNotEmpty($client_id);
        $this->client_id = $client_id;
        return $this;
    }

    /**
     * @param string $client_secret
     * @return Configuration
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setClientSecret(string $client_secret): self
    {
        Assert::stringNotEmpty($client_secret);
        $this->client_secret = $client_secret;
        return $this;
    }

    /**
     * @param string $country
     * @return Configuration
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setCountry(string $country): self
    {
        Assert::stringNotEmpty($country);
        $this->country = $country;
        return $this;
    }

    /**
     * @param string $currency
     * @return Configuration
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setCurrency(string $currency): self
    {
        Assert::stringNotEmpty($currency);
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @param string $environment
     * @return Configuration
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setEnvironment(string $environment): self
    {
        Assert::oneOf($environment, ['prod', 'dev']);
        $this->environment = $environment;
        return $this;
    }
}
