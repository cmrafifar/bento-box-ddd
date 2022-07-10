<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\AppServices;

use Illuminate\Database\DatabaseManager;
use SaatchiArt\BentoBoxDDD\Adapters\Repositories\ProvisioningTransaction;
use SaatchiArt\BentoBoxDDD\Adapters\Repositories\Transaction;
use SaatchiArt\BentoBoxDDD\Domain\Repositories\SingleConnection;

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
        $this->databaseManager->connection($connectionName);
        // great the connection actually exists!

        return new Transaction($this->databaseManager, $connectionName);
    }

    /**
     * @param SingleConnection[] $context
     */
    public function makeTransactionFromContext(array $context): Transaction
    {
        $connectionName = \array_shift($context)->getConnectionName();

        foreach ($context as $singleConnection) {
            if ($connectionName !== $singleConnection->getConnectionName()) {
                throw new \RuntimeException('All connections must be the same blah blah');
            }
        }

        $this->databaseManager->connection($connectionName);
        // great the connection actually exists!

        return new Transaction($this->databaseManager, $connectionName);
    }
}
