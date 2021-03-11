<?php

declare(strict_types=1);

namespace Devscast\AirtelMoney\Data;

/**
 * Class User
 * @package Devscast\AirtelMoney\Data
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class User
{
    /**
     * Date of birth of the registered user
     */
    private string $dob;

    /**
     * First name of the user
     */
    private string $first_name;

    /**
     * Last name of the user
     */
    private ?string $last_name = null;

    /**
     * grade
     */
    private string $grade;

    /**
     * barred
     */
    private bool $is_barred;

    /**
     * pin set
     */
    private bool $is_pin_set;

    /**
     * The unique number of the user by which he has registered
     */
    private string $msisdn;

    private Registration $registration;

    /**
     * User constructor.
     * @param array $data
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(array $data)
    {
        $this->dob = $data['dob'];
        $this->first_name = $data['first_name'];
        $this->last_name = $data['last_name'];
        $this->grade = $data['grade'];
        $this->is_barred = boolval($data['is_barred']);
        $this->is_pin_set = boolval($data['is_pin_set']);
        $this->msisdn = $data['msisdn'];
        $this->registration = new Registration([
            'id' => $data['registration']['id'],
            'status' => $data['registration']['status']
        ]);
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getDob(): string
    {
        return $this->dob;
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getGrade(): string
    {
        return $this->grade;
    }

    /**
     * @return bool
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function isIsBarred(): bool
    {
        return $this->is_barred;
    }

    /**
     * @return bool
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function isIsPinSet(): bool
    {
        return $this->is_pin_set;
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getMsisdn(): string
    {
        return $this->msisdn;
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getRegistration(): Registration
    {
        return $this->registration;
    }
}
