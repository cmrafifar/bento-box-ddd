<?php

declare(strict_types=1);


use Illuminate\Database\DatabaseManager;
use SaatchiArt\BentoBoxDDD\Adapters\Repositories\ArtworkRepositoryMysqlImpl;
use SaatchiArt\BentoBoxDDD\Adapters\Repositories\UserRepositoryMysqlImpl;
use SaatchiArt\BentoBoxDDD\AppServices\TransactionFactory;
use SaatchiArt\BentoBoxDDD\Domain\Repositories\ArtworkRepository;
use SaatchiArt\BentoBoxDDD\Domain\Repositories\UserRepository;


$databaseManager = new DatabaseManager();
$artworkRepository = new ArtworkRepositoryMysqlImpl($databaseManager);
$userRepository = new UserRepositoryMysqlImpl($databaseManager);

$transactionFactory = new TransactionFactory($databaseManager);



$simpleTransaction = $transactionFactory->makeTransaction('central1');

$resultIfAny = $simpleTransaction->executeSimpleTransaction(static function () use ($artworkRepository, $userRepository) {

    $artworks = $artworkRepository->getByUserId(1);
    $user = $userRepository->findByUserId(1);

    $userRepository->storeUser($user);
    foreach ($artworks as $artwork) {
        $artworkRepository->storeArtwork($artwork);
    }
});

print_r($resultIfAny);
