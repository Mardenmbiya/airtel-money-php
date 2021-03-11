<?php

declare(strict_types=1);

namespace Devscast\AirtelMoney\Data;

/**
 * Class HttpResponseStatus
 * @package Devscast\AirtelMoney\Data
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class HttpResponseStatus
{
    /**
     * Status code, HTTP status code.
     */
    private string $code;

    /**
     * The descriptive message of the response/action
     */
    private string $message;

    /**
     * Application-specific code to identify the error and success response.
     * This will be different for the type of error and success
     */
    private string $result_code;


    /**
     * true if no error else false.
     */
    private bool $success;

    /**
     * create from array
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->code = $data['code'];
        $this->message = $data['message'];
        $this->result_code = $data['result_code'];
        $this->success = boolval($data['success']);
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getResultCode(): string
    {
        return $this->result_code;
    }

    /**
     * @return bool
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }
}
