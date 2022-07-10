<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Domain\Repositories;

use SaatchiArt\BentoBoxDDD\Entities\Artworks\ArtworkEntity;

interface ArtworkRepository extends SingleConnection
{
    /** @return ArtworkEntity[] */
    public function getByUserId(int $userId): array;

    public function storeArtwork(ArtworkEntity $artwork): void;
}
