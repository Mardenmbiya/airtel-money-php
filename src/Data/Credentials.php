<?php

declare(strict_types=1);

namespace Devscast\AirtelMoney\Data;

class Credentials {

    /**
     * Type of token
     */
    private string $token_type;

    /**
     * Received token of the user
     */
    private string $access_token;

    /**
     * Expiry time of the access-token received
     */
    private string $expires_in;

    
    public function __construct(string $token_type, string $access_token, string $expires_in)
    {
        $this->token_type = $token_type;
        $this->access_token = $access_token;
        $this->expires_in = $expires_in;
    }

    public function getTokenType(): string
    {
        return $this->token_type;
    }

    public function getAccessToken(): string
    {
        return $this->access_token;
    }

    public function getExpiresIn(): string
    {
        return $this->expires_in;
    }
}
