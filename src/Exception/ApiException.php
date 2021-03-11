<?php

declare(strict_types=1);

namespace Devscast\AirtelMoney\Exception;

use Exception;
use Throwable;

/**
 * Class ApiException
 * @package Devscast\AirtelMoney\Exception
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class ApiException extends Exception
{
    /**
     * ApiException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
