<?php

namespace App\DTO;

class GuestDTO
{
    public string $first_name;

    public string $last_name;

    public string $email;

    public string $phone;

    public ?string $country;

    public function __construct(
        string $first_name,
        string $last_name,
        string $email,
        string $phone,
        ?string $country = null,

    ) {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->phone = $phone;
        $this->country = $country;
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'country' => $this->country,
        ];
    }
}
