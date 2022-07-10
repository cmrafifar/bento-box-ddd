<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Adapters\Repositories;

use Illuminate\Database\DatabaseManager;

class ProvisioningTransaction extends AbstractSupportsTransactions
{
    private string $connectionName;

    public function __construct(DatabaseManager $databaseManager, string $connectionName)
    {
        $this->databaseManager = $databaseManager;
        $this->connectionName = $connectionName;
    }

    /** @inheritDoc */
    protected function getConnectionName(): string
    {
        return $this->connectionName;
    }

    /**
     * @return mixed|null|void
     *
     * @throws \Throwable
     */
    public function provisionAndSimpleExecute(callable $provisioning, callable $executable)
    {
        $this->validateProvisioning($provisioning);

        return $this->executeSimpleTransaction($executable);
    }

    /** @throws \RuntimeException */
    private function validateProvisioning(callable $provisioning): void
    {
        /** @var AbstractSupportsTransactions[] $supportsTransactions */
        $supportsTransactionsList = \array_values($provisioning());
        $count = \count($supportsTransactionsList);

        $connectionName = $this->getConnection()->getName();

        $filtered = \array_filter($supportsTransactionsList, static function (AbstractSupportsTransactions $supportsTransactions) use ($connectionName) {
            return $supportsTransactions->getConnectionName() === $connectionName;
        });
        $countOfFiltered = \count($filtered);

        if ($count !== $countOfFiltered) {
            throw new \RuntimeException('Poorly provisioned transaction cannot be executed because not all repositories support same connection');
        }
    }


    /**
     * @return mixed|null|void
     *
     * @throws \Throwable
     */
    private function executeSimpleTransaction(callable $callback)
    {
        $connection = $this->getConnection();
        $connection->beginTransaction();

        try {
            $resultIfAny = $callback();

        } catch (\Throwable $throwable) {

            $connection->rollBack();

            throw $throwable;
        }

        $connection->commit();

        return $resultIfAny;
    }
}
