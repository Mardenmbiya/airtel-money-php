<?php

declare(strict_types=1);

namespace Devscast\AirtelMoney\Data;

/**
 * Class Registration
 * @package Devscast\AirtelMoney\Data
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class Registration
{
    /**
     * Registration id
     */
    private string $id;

    /**
     * Registration status
     */
    private string $status;

    /**
     * Registration constructor.
     * @param array $data
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->status = $data['status'];
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
