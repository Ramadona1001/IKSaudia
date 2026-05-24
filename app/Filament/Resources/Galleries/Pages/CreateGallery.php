<?php

namespace App\Filament\Resources\Galleries\Pages;

use App\Filament\Concerns\SyncsGalleryItems;
use App\Filament\Resources\Galleries\GalleryResource;
use App\Filament\Resources\Concerns\CreateTranslatableRecord;
use App\Models\GalleryTranslation;

class CreateGallery extends CreateTranslatableRecord
{
    use SyncsGalleryItems;

    protected static string $resource = GalleryResource::class;

    protected function translationModel(): string
    {
        return GalleryTranslation::class;
    }

    protected function translationForeignKey(): string
    {
        return 'gallery_id';
    }

    protected function translationFields(): array
    {
        return ['title', 'description'];
    }

    protected function hasSeo(): bool
    {
        return false;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        [$this->cachedItems, $data] = $this->extractGalleryItems($data);

        return parent::mutateFormDataBeforeCreate($data);
    }

    protected function afterCreate(): void
    {
        parent::afterCreate();
        $this->syncGalleryItems($this->record, $this->cachedItems);
    }
}
