<?php

declare(strict_types=1);


use Illuminate\Database\DatabaseManager;
use SaatchiArt\BentoBoxDDD\Adapters\Repositories\ArtworkRepositoryMysqlImpl;
use SaatchiArt\BentoBoxDDD\Adapters\Repositories\Transactions\TransactionFactory;
use SaatchiArt\BentoBoxDDD\Adapters\Repositories\UserRepositoryMysqlImpl;
use SaatchiArt\BentoBoxDDD\Domain\Repositories\ArtworkRepository;
use SaatchiArt\BentoBoxDDD\Domain\Repositories\UserRepository;

class SomeService {
    private ArtworkRepository $artworkRepository;
    private UserRepository $userRepository;
    private TransactionFactory $transactionFactory;

    public function __construct(
        ArtworkRepository $artworkRepository,
        UserRepository $userRepository,
        TransactionFactory $transactionFactory
    ) {
        $this->artworkRepository = $artworkRepository;
        $this->userRepository = $userRepository;
        $this->transactionFactory = $transactionFactory;
    }

    public function updateThoseArtworksForSomeReason(): int
    {
        $transaction = $this->transactionFactory->makeFullyProvisionedTransaction(
            [$this->artworkRepository, $this->userRepository],
            static function (
                ArtworkRepository $artworkRepository,
                UserRepository $userRepository
            ) {
                $artworks = $artworkRepository->getByUserId(1);
                $user = $userRepository->findByUserId(1);

                $userRepository->storeUser($user);
                foreach ($artworks as $artwork) {
                    $artworkRepository->storeArtwork($artwork);
                }

                return \count($artworks);
        });

        $countOfArtworksUpdated = $transaction->execute();

        return $countOfArtworksUpdated;
    }
}


$databaseManager = new DatabaseManager(/* blah blah */);
$artworkRepository = new ArtworkRepositoryMysqlImpl($databaseManager);
$userRepository = new UserRepositoryMysqlImpl($databaseManager);

$transactionFactory = new TransactionFactory($databaseManager);

$someService = new SomeService($artworkRepository, $userRepository, $transactionFactory);

$numberOfArtworksUpdated = $someService->updateThoseArtworksForSomeReason();
