<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Adapters\Repositories;

use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use SaatchiArt\BentoBoxDDD\Domain\Repositories\SupportsTransactions;

abstract class AbstractSupportsTransactions implements SupportsTransactions
{
    protected DatabaseManager $databaseManager;

    /**
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /** Get name of db connection */
    abstract protected function getConnectionName(): string;

    /** Connectino by connection name */
    protected function getConnection(): Connection
    {
        $connName = $this->getConnectionName();
        return $this->databaseManager->connection($connName);
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

    /** @inheritDoc */
    public function executeSimpleTransaction(callable $callback)
    {
        $conn = $this->getConnection();
        $conn->beginTransaction();
        try {
            $resultIfAny = $callback();
        } catch (\Throwable $throwable) {
            $conn->rollBack();
            throw $throwable;
        }

        $conn->commit();
        return $resultIfAny;
    }
}
