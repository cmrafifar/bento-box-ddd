<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Adapters\Repositories\Transactions;

use Illuminate\Database\DatabaseManager;
use SaatchiArt\BentoBoxDDD\Adapters\Repositories\AbstractSingleConnectionRepository;

class Transaction extends AbstractSingleConnectionRepository
{
    private string $connectionName;

    public function __construct(DatabaseManager $databaseManager, string $connectionName)
    {
        parent::__construct($databaseManager);
        $this->connectionName = $connectionName;
    }

    /** @inheritDoc */
    public function getConnectionName(): string
    {
        return $this->connectionName;
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function beginTransaction(): void
    {
        $this->getConnection()->beginTransaction();
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function commitTransaction(): void
    {
        $this->getConnection()->commit();
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function rollbackTransaction(): void
    {
        $this->getConnection()->rollBack();
    }


    /**
     * @return mixed|null|void
     *
     * @throws \Throwable
     */
    public function executeSimpleTransaction(callable $callback)
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
