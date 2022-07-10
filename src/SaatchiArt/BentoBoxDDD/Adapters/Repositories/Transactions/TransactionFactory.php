<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Adapters\Repositories\Transactions;

use Illuminate\Database\DatabaseManager;
use SaatchiArt\BentoBoxDDD\Domain\Repositories\SingleConnection;

class TransactionFactory
{
    private DatabaseManager $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function makeTransaction(string $connectionName): Transaction
    {
        $this->databaseManager->connection($connectionName);
        // great the connection actually exists!

        return new Transaction($this->databaseManager, $connectionName);
    }

    /** @param SingleConnection[] $repositoriesInContext */
    public function makeFullyProvisionedTransaction(
        array $repositoriesInContext,
        callable $transactionalCallback
    ): FullyProvisionedTransaction {

        return new FullyProvisionedTransaction(
            \array_values($repositoriesInContext),
            $transactionalCallback
        );
    }
}
