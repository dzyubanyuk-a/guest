<?php

namespace App\Services;

use App\DTO\GuestDTO;
use App\Models\Guest;
use App\Repositories\GuestRepository;
use App\Services\Interfaces\GuestServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Propaganistas\LaravelPhone\PhoneNumber;

class GuestService implements GuestServiceInterface
{
    public GuestRepository $guestRepository;

    public function __construct(GuestRepository $guestRepository)
    {
        $this->guestRepository = $guestRepository;
    }

    public function getPaginatedGuests(int $perPage = 15, $page = 1): LengthAwarePaginator
    {
        return $this->guestRepository->paginate($perPage, $page);
    }

    public function getGuestById(int $id): Guest
    {
        return $this->guestRepository->findById($id);
    }

    public function createGuest(GuestDTO $guestDTO): Guest
    {
        $countryCode = $this->getCountryCode($guestDTO->phone);
        $this->updateCountry($guestDTO, $countryCode);

        return $this->guestRepository->create($guestDTO);
    }

    public function updateGuest(int $id, array $data): bool
    {
        return $this->guestRepository->update($id, $data);
    }

    public function deleteGuest(int $id): bool
    {
        return $this->guestRepository->delete($id);
    }

    private function getCountryCode(string $phone): ?string
    {
        $phone = new PhoneNumber($phone);

        return $phone->getCountry();
    }

    private function updateCountry(GuestDTO $guestDTO, ?string $countryCode): void
    {
        $guestDTO->country = $countryCode;
    }
}
