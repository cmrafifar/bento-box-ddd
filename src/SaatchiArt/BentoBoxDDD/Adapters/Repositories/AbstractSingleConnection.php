<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Adapters\Repositories;

use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use SaatchiArt\BentoBoxDDD\Domain\Repositories\SingleConnection;

abstract class AbstractSingleConnection implements SingleConnection
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
    abstract public function getConnectionName(): string;

    /** Connectino by connection name */
    protected function getConnection(): Connection
    {
        $connName = $this->getConnectionName();
        return $this->databaseManager->connection($connName);
    }
}
