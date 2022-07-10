<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Adapters\Repositories;

use SaatchiArt\BentoBoxDDD\Domain\Exceptions\UserNotFoundException;
use SaatchiArt\BentoBoxDDD\Domain\Repositories\UserRepository;
use SaatchiArt\BentoBoxDDD\Entities\Users\UserEntity;

final class UserRepositoryMysqlImpl extends AbstractSingleConnectionRepository implements UserRepository
{
    /** @inheritDoc */
    public function getConnectionName(): string
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


    /** @inheritDoc */
    public function beginTransaction()
    {
        $this->getConnection()->beginTransaction();
    }

    /** @inheritDoc */
    public function commitTransaction()
    {
        $this->getConnection()->commit();
    }

    /** @inheritDoc */
    public function rollbackTransaction()
    {
        $this->getConnection()->rollBack();
    }
}
