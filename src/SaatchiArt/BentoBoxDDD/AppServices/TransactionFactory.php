<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\AppServices;

use Illuminate\Database\DatabaseManager;
use SaatchiArt\BentoBoxDDD\Adapters\Repositories\ProvisioningTransaction;
use SaatchiArt\BentoBoxDDD\Adapters\Repositories\Transaction;

class TransactionFactory
{
    private DatabaseManager $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function makeProvisioningTransaction(string $connectionName): ProvisioningTransaction
    {
        $connection = $this->databaseManager->connection($connectionName);
        // great the connection actually exists!

        return new ProvisioningTransaction($this->databaseManager, $connectionName);
    }

    public function makeTransaction(string $connectionName): Transaction
    {
        $connection = $this->databaseManager->connection($connectionName);
        // great the connection actually exists!

        return new Transaction($this->databaseManager, $connectionName);
    }
}
