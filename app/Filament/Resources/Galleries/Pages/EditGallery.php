<?php

namespace App\Filament\Resources\Galleries\Pages;

use App\Filament\Concerns\SyncsGalleryItems;
use App\Filament\Resources\Galleries\GalleryResource;
use App\Filament\Resources\Concerns\EditTranslatableRecord;
use App\Models\GalleryTranslation;
use Filament\Actions\DeleteAction;

class EditGallery extends EditTranslatableRecord
{
    use SyncsGalleryItems;

    protected static string $resource = GalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data = parent::mutateFormDataBeforeFill($data);
        $data['items'] = $this->mapItemsForForm($this->record);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        [$this->cachedItems, $data] = $this->extractGalleryItems($data);

        return parent::mutateFormDataBeforeSave($data);
    }

    protected function afterSave(): void
    {
        parent::afterSave();
        $this->syncGalleryItems($this->record, $this->cachedItems);
    }
}
