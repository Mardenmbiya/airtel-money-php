<?php

declare(strict_types=1);

namespace Devscast\AirtelMoney;

use Devscast\AirtelMoney\Data\User;
use Devscast\AirtelMoney\Data\Balance;
use Devscast\AirtelMoney\Data\Subscriber;
use Devscast\AirtelMoney\Data\Transaction;
use Devscast\AirtelMoney\Data\Credentials;
use Devscast\AirtelMoney\Data\HttpResponseStatus;
use Devscast\AirtelMoney\Data\Registration;
use Devscast\AirtelMoney\Data\ResponseStatus;
use Devscast\AirtelMoney\Data\UssdPaymentTransaction;

use Webmozart\Assert\Assert;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class Client {

    protected string $client_id;
    protected string $client_secret;
    protected Credentials $credentials;
    protected HttpClientInterface $client;

    /**
     * IMT Remittance error codes
     * These are the common error codes, 
     * which may be returned when you communicate with Airtel for IMT Remittance.
     */
    public const IMT_ERROR_CODES = [
        '521002' => 'Invalid Msisdn Length',
        '1703' => 'Account number for the service is rejected',
        '60011' => 'Threshold count for Payer reached for the day'
    ];

    /**
     * Group Merchant error codes
     * These error codes may be returned when you interact for Group Merchant transactions.
     */
    public const MERCHANT_ERROR_CODES = [
        'ESB000001' => 'Something went wrong',
        'ESB000004' => 'An error occurred while initiating the payment',
        'ESB000008' => 'Field validation',
        'ESB000011' => 'Failed',
        'ESB000014' => 'An error occurred while fetching the transaction status',
        'ESB000033' => 'Invalid MSISDN Length. MSISDN Length should be %s',
        'ESB000034' => 'Invalid Country Name',
        'ESB000035' => 'Invalid Currency Code',
        'ESB000036' => 'Invalid MSISDN Length. MSISDN Length should be %s and should start with 0',
        'ESB000039' => 'Vendor is not configured to do transaction in the country %s'
    ];

    public const OAUTH_URL = '%s/oauth2/token';
    public const BASE_URL = [
        'dev' => 'https://openapiuat.airtel.africa',
        'prod' => 'https://openapi.airtel.africa'
    ];

    public function __construct(string $client_id, string $client_secret, string $environment)
    {

        Assert::stringNotEmpty($client_id);
        Assert::stringNOtEmpty($client_secret);
        Assert::oneOf($environment, ['prod', 'dev']);

        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->environment = $environment;

        $client = HttpClient::create();
        $url = $this->url(sprintf(self::OAUTH_URL, '/merchant/v1/payments'));
        $response = $client->request('POST', $url, [
            'json' => [
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'grant_type' => 'client_credentials'
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $data = $response->toArray();
            $this->credentials = new Credentials(
                $data['token_type'], 
                $data['access_token'], 
                $data['expires_in']
            );
            $this->client = HttpClient::create([
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'auth_bearer' => $data['access_token'],
            ]);
        }
    }

    /**
     * GET /standard/v1/users/balance
     */
    public function balance(?string $country = 'CD', ?string $currency = 'CDF'): ?Balance 
    {
        Assert::stringNotEmpty($country);
        Assert::stringNotEmpty($currency);

        $url = $this->url('/standard/v1/users/balance');
        $response = $this->client->request('GET', $url);

        if ($response->getStatusCode() === 200) {
            $data = $response->toArray();
            return [
                'balance' => new Balance(
                    $data['data']['balance'], 
                    $data['data']['currency'], 
                    $data['data']['account_status']
                ),
                'status' => new HttpResponseStatus($data['status'])
            ];
        }
    }


    /**
     * GET /standard/v1/payments/{id}
     */
    public function findTxn(string $id, ?string $country = 'CD', ?string $currency = 'CDF'): ?Transaction
    {
        Assert::stringNotEmpty($country);
        Assert::stringNotEmpty($currency);

        $url = $this->url("/standard/v1/payments/{$id}");
        $response = $this->client->request('GET', $url, [
            'header' => [
                'X-Country' => $country,
                'X-Currency' => $currency
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            $data = $response->toArray();
            return [
                'transaction' => new Transaction(),
                'status' => new HttpResponseStatus($data['status'])
            ]
        }
    }

    /**
     * POST /standard/v1/payments/refund
     */
    public function refund(string $airtel_money_id, ?string $country = 'CD', ?string $currency = 'CDF'): void
    {
        Assert::stringNotEmpty($country);
        Assert::stringNotEmpty($currency);

        $url = $this->url("/standard/v1/payments/refund");
        $response = $this->client->request("POST", $url, [
            'header' => [
                'X-Country' => $country,
                'X-Currency' => $currency
            ],
            'body' => [
                'transaction' => [
                    'airtel_money_id' => $airtel_money_id
                ]
            ]
        ]);
    }

    /**
     * GET /standard/v1/users/{msisdn}
     * 
     */
    public function findUser(string $msisdn, ?string $country = 'CD', ?string $currency = 'CDF'): ?User 
    {
        Assert::stringNotEmpty($country);
        Assert::stringNotEmpty($currency);

        $url = $this->url("/standard/v1/users/{$msisdn}");
        $response = $this->client->request('GET', $url, [
            'header' => [
                'X-Country' => $country,
                'X-Currency' => $currency
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            $data = $response->toArray();
            return new User();
        }
    }

    /**
     * Generate url depending on current environment
     */
    private function url(string $url): string
    {
        return self::BASE_URL[$this->environment] . $url;
    }
}
