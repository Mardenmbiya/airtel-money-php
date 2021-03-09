<?php

declare(strict_types=1);

namespace Devscast\AirtelMoney\Data;

class User {

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

    private ?string $reg_status = null;

    private ?string $registered_id = null;
}
