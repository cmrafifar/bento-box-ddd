<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Domain\Repositories;

interface SingleConnection
{
    /** Get name of db connection */
    public function getConnectionName(): string;
}
