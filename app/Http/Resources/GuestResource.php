<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GuestResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="GuestResource",
     *     type="object",
     *
     *     @OA\Property(property="first_name", type="string", example="Elmore"),
     *     @OA\Property(property="last_name", type="string", example="Ruecker"),
     *     @OA\Property(property="email", type="string", format="email", example="macie.hickle@example.com"),
     *     @OA\Property(property="phone", type="string", example="+12287197893"),
     *     @OA\Property(property="country", type="string", example="TD")
     * )
     */
    public function toArray($request): array
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
