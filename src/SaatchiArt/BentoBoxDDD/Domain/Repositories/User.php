<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Domain\Repositories;

use SaatchiArt\BentoBoxDDD\Domain\Exceptions\UserNotFoundException;
use SaatchiArt\BentoBoxDDD\Entities\Users\UserEntity;

interface User extends SingleConnection
{
    /** @throws UserNotFoundException */
    public function findByUserId(int $userId): UserEntity;

    public function storeUser(UserEntity $user): void;
}
