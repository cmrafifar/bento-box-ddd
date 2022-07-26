<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\AppServices;

use SaatchiArt\BentoBoxDDD\Domain\Services\UserVacations;

/** Facade can be a replacement term for Orchestrators. */
class UserFacade
{
    private UserVacations $userVacations;
    private EdgeCacheInvalidator $edgeCacheInvalidator;

    public function __construct(UserVacations $userVacations, EdgeCacheInvalidator $edgeCacheInvalidator)
    {
        $this->userVacations = $userVacations;
        $this->edgeCacheInvalidator = $edgeCacheInvalidator;
    }

    /**
     * We still want edge cache cleared, so use the facade.
     * I'm thinking Facade is better name that Orchestrator.
     *  Orchestrator may be confused with something else.
     */
    public function putUserOnVacation(int $userId): void
    {
       $this->userVacations->goOnVacation($userId);

       $this->edgeCacheInvalidator->invalidateCacheForUser($userId);
    }
}
