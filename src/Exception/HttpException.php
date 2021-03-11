<?php

declare(strict_types=1);

namespace Devscast\AirtelMoney\Exception;

use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Throwable;

/**
 * Class HttpException
 * @package Devscast\AirtelMoney\Exception
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class HttpException extends Exception
{
    /**
     * HttpException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param ClientExceptionInterface|
     *  RedirectionExceptionInterface|
     *  DecodingExceptionInterface|
     *  TransportExceptionInterface|
     *  Exception|
     *  ServerExceptionInterface $e
     * @return HttpException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public static function fromClientException($e): self
    {
        return new self($e->getMessage(), $e->getCode(), $e->getPrevious());
    }
}
