<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\AppServices;

class EdgeCacheInvalidator
{
    private $someProvider;

    public function invalidateCacheForUser(int $userId)
    {
        // invalidate some cache somehow via some provider.
    }
}
