<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Adapters\Repositories;

use SaatchiArt\BentoBoxDDD\Domain\Repositories\ArtworkRepository;
use SaatchiArt\BentoBoxDDD\Entities\Artworks\ArtworkEntity;
use SaatchiArt\BentoBoxDDD\Entities\Artworks\ArtworkImageValueObject;

final class ArtworkRepositoryMysqlImpl extends AbstractSupportsTransactions implements ArtworkRepository
{
    /** @inheritDoc */
    protected function getConnectionName(): string
    {
        return 'central1';
    }

    /** @return ArtworkEntity[] */
    public function getByUserId(int $userId): array
    {
        return $this->getConnection()
            ->table('artworks')
            ->where('user_id', '=', $userId)
            ->get()
            ->map(function (\stdClass $row): ArtworkEntity {
                $artworkImage = new ArtworkImageValueObject($row->relative_image_path);
                return new ArtworkEntity($row->id, $row->isForSale, $artworkImage);
            })
            ->toArray();
    }

    public function storeArtwork(ArtworkEntity $artwork): void
    {
        $this->getConnection()
            ->table('artworks')
            ->updateOrInsert([
                'id' => $artwork->getId(),
                'is_for_sale' => $artwork->isForSale(),
                'relative_image_path' => $artwork->getArtworkImageRelativePath(),
            ]);
    }
}
