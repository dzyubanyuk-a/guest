<?php

namespace App\Services\Interfaces;

use App\DTO\GuestDTO;
use App\Models\Guest;
use Illuminate\Pagination\LengthAwarePaginator;

interface GuestServiceInterface
{
    public function getPaginatedGuests(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function getGuestById(int $id): Guest;

    public function createGuest(GuestDTO $guestDTO): Guest;

    public function updateGuest(int $id, array $data): bool;

    public function deleteGuest(int $id): bool;
}
