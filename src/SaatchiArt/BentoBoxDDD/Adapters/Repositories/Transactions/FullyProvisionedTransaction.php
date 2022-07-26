<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Adapters\Repositories\Transactions;

use Illuminate\Database\Connection;
use SaatchiArt\BentoBoxDDD\Domain\Repositories\SingleConnection;

class FullyProvisionedTransaction
{
    /** @var SingleConnection[] */
    private array $singleConnectionRepositories;

    /** @var callable $callback */
    private $callback;

    /** @var SingleConnection[] $singleConnectionRepositories */
    public function __construct(
        array $singleConnectionRepositories,
        callable $callbackWithStronglyTypedRepositories
    ) {
        if (empty($singleConnectionRepositories)) {
            $message = 'Cannot create ' . __CLASS__ . ' without at least one provisioned repository';
            throw new \RuntimeException($message);
        }

        $connectionName = \array_shift($singleConnectionRepositories)
            ->getConnectionName();

        foreach ($singleConnectionRepositories as $singleConnection) {
            if ($connectionName !== $singleConnection->getConnectionName()) {
                $message = 'Cannot create ' . __CLASS__ . ' when ALL provided repositories do not share the same connection';
                throw new \RuntimeException($message);
            }
        }

        $this->singleConnectionRepositories = \array_values($singleConnectionRepositories);

        $this->callback = $callbackWithStronglyTypedRepositories;
    }

    private function getConnection(): Connection
    {
        return $this->singleConnectionRepositories[0]->getConnection();
    }

    /**
     * @return mixed|null|void
     *
     * @throws \Throwable - this is what makes it simple. No special exception handling.
     */
    public function execute()
    {
        $connection = $this->getConnection();
        $connection->beginTransaction();

        $callback = $this->callback;
        try {
            // Order matters, as the callback should have an explicit and strongly
            // typed function interface
            $resultIfAny = $callback($this->singleConnectionRepositories);

        } catch (\Throwable $throwable) {

            $connection->rollBack();

            throw $throwable;
        }

        $connection->commit();

        return $resultIfAny;
    }
}
