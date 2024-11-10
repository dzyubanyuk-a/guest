<?php

namespace App\Repositories\Interfaces;

use App\DTO\GuestDTO;
use App\Models\Guest;
use Illuminate\Pagination\LengthAwarePaginator;

interface GuestRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findById(int $id): Guest;

    public function create(GuestDTO $guestDTO);

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
