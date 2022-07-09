<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Ports\Console;

use SaatchiArt\BentoBoxDDD\AppServices\UserFacade;

class UserVacationsCommand
{
    /** @var UserFacade */
    private $userFacade;

    /**
     * @param UserFacade $userFacade
     */
    public function __construct(UserFacade $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    public function goOnVacation()
    {
        $userId = 1; // TODO: some kind of implementation

        // We still want edge cache cleared, so use the facade.
        // I'm thinking Facade is better name that Orchestrator.
        // Orchestrator may be confused with something else.
        $this->userFacade->putUserOnVacation($userId);
    }
}
