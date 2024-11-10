<?php

namespace App\Repositories;

use App\DTO\GuestDTO;
use App\Models\Guest;
use App\Repositories\Interfaces\GuestRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class GuestRepository implements GuestRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return Guest::query()->paginate($perPage, ['*'], 'page', $page);
    }

    public function findById(int $id): Guest
    {
        return Guest::query()->findOrFail($id);
    }

    public function create(GuestDTO $guestDTO): Guest
    {
        return Guest::query()->create($guestDTO->toArray());
    }

    public function update(int $id, array $data): bool
    {
        $guest = $this->findById($id);

        return $guest->update($data);
    }

    public function delete(int $id): bool
    {
        $guest = $this->findById($id);

        return $guest->delete() === true;
    }
}
