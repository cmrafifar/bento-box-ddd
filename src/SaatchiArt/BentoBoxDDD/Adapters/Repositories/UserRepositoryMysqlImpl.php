<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Adapters\Repositories;

use SaatchiArt\BentoBoxDDD\Domain\Repositories\UserRepository;
use SaatchiArt\BentoBoxDDD\Entities\Users\UserEntity;
use SaatchiArt\BentoBoxDDD\Exceptions\UserNotFoundException;

final class UserRepositoryMysqlImpl extends AbstractSupportsTransactions implements UserRepository
{
    /** @inheritDoc */
    protected function getConnectionName(): string
    {
        return 'central1';
    }

    /** @throws UserNotFoundException */
    public function findByUserId(int $userId): UserEntity
    {
        $row = $this->getConnection()
            ->table('users')
            ->where('id', '=', $userId)
            ->first();

        if ($row === null) {
            throw new UserNotFoundException("User {$userId} not found.");
        }

        return new UserEntity($userId, $row->isOnVacation);
    }

    public function storeUser(UserEntity $user): void
    {
        $this->getConnection()
            ->table('users')
            ->updateOrInsert([
                'id' => $user->getId(),
                'is_on_vacation' => $user->isOnVacation(),
            ]);
    }
}
