<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Domain\Repositories;

use SaatchiArt\BentoBoxDDD\Entities\Users\UserEntity;

interface UserRepository extends SupportsTransactions
{
    /** @throws \SaatchiArt\BentoBoxDDD\Exceptions\UserNotFoundException */
    public function findByUserId(int $userId): UserEntity;

    public function storeUser(UserEntity $user): void;
}
