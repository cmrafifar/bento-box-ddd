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



$transactionExample1 = $transactionFactory->makeProvisioningTransaction('central1');

$resultIfAny = $transactionExample1->provisionAndSimpleExecute(static function () use ($artworkRepository, $userRepository) {
    // order of provision must match the order of the callable arguments.
    return [$artworkRepository, $userRepository];

}, static function (ArtworkRepository $artworkRepository, UserRepository $userRepository) {

    $artworks = $artworkRepository->getByUserId(1);
    $user = $userRepository->findByUserId(1);

    $userRepository->storeUser($user);
    foreach ($artworks as $artwork) {
        $artworkRepository->storeArtwork($artwork);
    }
});

print_r($resultIfAny);




$failingTransaction = $transactionFactory->makeProvisioningTransaction('central1');

$resultIfAny = $failingTransaction->provisionAndSimpleExecute(static function () use ($artworkRepository, $userRepository) {
    // this will fail
    return [$userRepository, $artworkRepository];

    // because it does not match the provision
}, static function (ArtworkRepository $artworkRepository, UserRepository $userRepository) {

    $artworks = $artworkRepository->getByUserId(1);
    $user = $userRepository->findByUserId(1);

    $userRepository->storeUser($user);
    foreach ($artworks as $artwork) {
        $artworkRepository->storeArtwork($artwork);
    }
});

print_r($resultIfAny);
