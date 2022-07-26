<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Domain\Services;

use SaatchiArt\BentoBoxDDD\Domain\Repositories\ArtworkRepository;

class UserVacations
{
    private User $userRepository;
    private ArtworkRepository $artworkRepository;

    /**
     * @param User|\SaatchiArt\BentoBoxDDD\Adapters\Repositories\UserRepositoryMysqlImpl $userRepository
     * @param ArtworkRepository|\SaatchiArt\BentoBoxDDD\Adapters\Repositories\ArtworkRepositoryMysqlImpl $artworkRepository
     */
    public function __construct(User $userRepository, ArtworkRepository $artworkRepository)
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

        $this->userRepository->beginTransaction();

        try {
            $this->userRepository->storeUser($user);
            foreach ($artworks as $artwork) {
                $this->artworkRepository->storeArtwork($artwork);
            }

        } catch (\Throwable $throwable) {

            $this->userRepository->rollBack();

            throw $throwable;
        }

        $this->userRepository->commit();
    }
}
