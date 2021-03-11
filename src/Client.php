<?php

declare(strict_types=1);

namespace Devscast\AirtelMoney;

use Devscast\AirtelMoney\Data\Configuration;
use Devscast\AirtelMoney\Data\Payment;
use Devscast\AirtelMoney\Data\User;
use Devscast\AirtelMoney\Data\Balance;
use Devscast\AirtelMoney\Data\Transaction;
use Devscast\AirtelMoney\Data\Credentials;
use Devscast\AirtelMoney\Data\HttpResponseStatus;
use Devscast\AirtelMoney\Data\UssdPaymentTransaction;
use Devscast\AirtelMoney\Exception\HttpException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class Client
 * @package Devscast\AirtelMoney
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class Client
{
    protected Configuration $configuration;
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

    /**
     * Client constructor.
     * @param Configuration $configuration
     * @throws HttpException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
        $client = HttpClient::create();

        // TODO: use correct url for each product
        try {
            $url = $this->url(sprintf(self::OAUTH_URL, '/merchant/v1/payments'));
            $response = $client->request('POST', $url, [
                'json' => [
                    'client_id' => $configuration->getClientId(),
                    'client_secret' => $configuration->getClientSecret(),
                    'grant_type' => 'client_credentials'
                ]
            ]);

            if ($response->getStatusCode() == 200) {
                $data = $response->toArray();
                $this->credentials = new Credentials($data['token_type'], $data['access_token'], $data['expires_in']);
                $this->client = HttpClient::create([
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'X-Country' => $configuration->getCountry(),
                        'X-Currency' => $configuration->getCurrency()
                    ],
                    'auth_bearer' => $this->credentials->getAccessToken(),
                ]);
            }
        } catch (
            ClientExceptionInterface |
            DecodingExceptionInterface |
            RedirectionExceptionInterface |
            ServerExceptionInterface |
            TransportExceptionInterface $e
        ) {
            throw HttpException::fromClientException($e);
        }
    }

    /**
     * GET /standard/v1/users/balance
     * @param string|null $currency
     * @return array|null
     * @throws HttpException
     */
    public function balance(?string $currency): ?array
    {
        try {
            $url = $this->url('/standard/v1/users/balance');
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'X-Currency' => $currency ?: $this->configuration->getCurrency()
                ]
            ]);
            if ($response->getStatusCode() === 200) {
                $data = $response->toArray();
                return [
                    'balance' => new Balance($data['data']),
                    'status' => new HttpResponseStatus($data['status'])
                ];
            }
        } catch (
            ClientExceptionInterface |
            DecodingExceptionInterface |
            RedirectionExceptionInterface |
            ServerExceptionInterface |
            TransportExceptionInterface $e
        ) {
            throw HttpException::fromClientException($e);
        } finally {
            return null;
        }
    }

    /**
     * POST /merchant/v1/payments/
     * @param Payment $payment
     * @return array
     * @throws HttpException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function capture(Payment $payment): ?array
    {
        $url = $this->url('/merchant/v1/payments/');
        $transaction = $payment->getTransaction();
        $subscriber = $payment->getSubscriber();

        try {
            $response = $this->client->request("POST", $url, [
                'body' => [
                    'reference' => $payment->getReference(),
                    'subscriber' => [
                        'country' => $subscriber->getCountry(),
                        'currency' => $subscriber->getCurrency(),
                        'msisdn' => $subscriber->getMsisdn()
                    ],
                    'transaction' => [
                        'amount' => $transaction->getAmount(),
                        'country' => $transaction->getCountry(),
                        'currency' => $transaction->getCurrency(),
                        'id' => $transaction->getId()
                    ]
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $data = $response->toArray();
                return [
                    'transaction' => new UssdPaymentTransaction($data['data']['transaction']),
                    'status' => new HttpResponseStatus($data['status'])
                ];
            }
        } catch (
            ClientExceptionInterface |
            DecodingExceptionInterface |
            RedirectionExceptionInterface |
            ServerExceptionInterface |
            TransportExceptionInterface $e
        ) {
            throw HttpException::fromClientException($e);
        } finally {
            return null;
        }
    }

    /**
     * GET /standard/v1/payments/{id}
     * @param string $id Partner unique transaction id to identify the transaction.
     * @return array
     * @throws HttpException
     */
    public function transaction(string $id): ?array
    {
        try {
            $url = $this->url("/standard/v1/payments/{$id}");
            $response = $this->client->request('GET', $url);

            if ($response->getStatusCode() === 200) {
                $data = $response->toArray();
                return [
                    'transaction' => new Transaction($data['transaction']),
                    'status' => new HttpResponseStatus($data['status'])
                ];
            }
        } catch (
            ClientExceptionInterface |
            DecodingExceptionInterface |
            RedirectionExceptionInterface |
            ServerExceptionInterface |
            TransportExceptionInterface $e
        ) {
            throw HttpException::fromClientException($e);
        } finally {
            return null;
        }
    }

    /**
     * POST /standard/v1/payments/refund
     * @param string $airtel_money_id Airtel unique transaction id to identify the transaction.
     * @return array|null
     * @throws HttpException
     */
    public function refund(string $airtel_money_id): ?array
    {
        try {
            $url = $this->url("/standard/v1/payments/refund");
            $response = $this->client->request("POST", $url, [
                'body' => [
                    'transaction' => [
                        'airtel_money_id' => $airtel_money_id
                    ]
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $data = $response->toArray();
                return [
                    'transaction' => new UssdPaymentTransaction($data['data']['transaction']),
                    'status' => new HttpResponseStatus($data['status'])
                ];
            }
        } catch (
            ClientExceptionInterface |
            DecodingExceptionInterface |
            RedirectionExceptionInterface |
            ServerExceptionInterface |
            TransportExceptionInterface $e
        ) {
            throw HttpException::fromClientException($e);
        }
    }

    /**
     * GET /standard/v1/users/{msisdn}
     * @param string $msisdn The unique number of the user by which he has registered
     * @return User|null
     * @throws HttpException
     */
    public function user(string $msisdn): ?array
    {
        try {
            $url = $this->url("/standard/v1/users/{$msisdn}");
            $response = $this->client->request('GET', $url);
            if ($response->getStatusCode() == 200) {
                $data = $response->toArray();
                return [
                    'user' => new User($data['data']),
                    'status' => new HttpResponseStatus($data['registration'])
                ];
            }
        } catch (
            ClientExceptionInterface |
            DecodingExceptionInterface |
            RedirectionExceptionInterface |
            ServerExceptionInterface |
            TransportExceptionInterface $e
        ) {
            throw HttpException::fromClientException($e);
        } finally {
            return null;
        }
    }

    /**
     * Generate base url depending on current environment
     * @param string $url
     * @return string
     */
    protected function url(string $url): string
    {
        return self::BASE_URL[$this->configuration->getEnvironment() . $url];
    }
}
