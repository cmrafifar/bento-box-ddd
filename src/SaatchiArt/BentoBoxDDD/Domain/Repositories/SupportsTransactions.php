<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Domain\Repositories;

interface SupportsTransactions
{
    /** @return void */
    public function beginTransaction();

    /** @return void */
    public function commitTransaction();

    /** @return void */
    public function rollbackTransaction();

    /**
     * Simple meaning it catches \Throwable, rolls it back, and re-throws it
     *
     * @throws \Throwable
     *
     * @return mixed|void|null anything
     */
    public function executeSimpleTransaction(callable $callback);
}
