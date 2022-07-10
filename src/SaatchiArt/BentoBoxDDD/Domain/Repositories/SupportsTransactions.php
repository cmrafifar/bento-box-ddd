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
}
