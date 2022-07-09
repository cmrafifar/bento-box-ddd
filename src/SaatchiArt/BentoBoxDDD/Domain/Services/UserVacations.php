<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Domain\Services;

use SaatchiArt\BentoBoxDDD\Domain\Repositories\ArtworkRepository;
use SaatchiArt\BentoBoxDDD\Domain\Repositories\UserRepository as UserRepository;

class UserVacations
{
    private UserRepository $userRepository;
    private ArtworkRepository $artworkRepository;

    /**
     * @param UserRepository $userRepository
     * @param ArtworkRepository $artworkRepository
     */
    public function __construct(UserRepository $userRepository, ArtworkRepository $artworkRepository)
    {
        $this->userRepository = $userRepository;
        $this->artworkRepository = $artworkRepository;
    }

    public function goOnVacation(int $userId): void
    {
        $user = $this->userRepository->findByUserId($userId);
        $user->goOnVacation();

        $artworks = $this->artworkRepository->getByUserId($userId);
        foreach ($artworks as $artwork) {
            $artwork->makeNotForSale();
        }

        $this->userRepository->executeSimpleTransaction(function () use ($user, $artworks) {
            $this->userRepository->storeUser($user);
            foreach ($artworks as $artwork) {
                $this->artworkRepository->storeArtwork($artwork);
            }
        });
    }
}
