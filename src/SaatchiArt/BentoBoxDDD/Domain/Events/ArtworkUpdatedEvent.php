<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Domain\Events;

final class ArtworkUpdatedEvent
{
    /** @var int */
    private $artworkId;

    public function __construct(int $artworkId)
    {
        $this->artworkId = $artworkId;
    }

    public function getArtworkId(): int
    {
        return $this->artworkId;
    }
}
