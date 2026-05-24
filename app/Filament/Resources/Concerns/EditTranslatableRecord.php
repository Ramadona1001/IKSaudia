<?php

namespace App\Filament\Resources\Concerns;

use App\Filament\Concerns\SyncsModelTranslations;
use App\Filament\Concerns\SyncsSeo;
use Filament\Resources\Pages\EditRecord;

abstract class EditTranslatableRecord extends EditRecord
{
    use SyncsModelTranslations;
    use SyncsSeo;

    /** @var array<string, array<string, mixed>> */
    protected array $cachedTranslations = [];

    abstract protected function translationModel(): string;

    abstract protected function translationForeignKey(): string;

    /** @return list<string> */
    abstract protected function translationFields(): array;

    protected function hasSeo(): bool
    {
        return true;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->record->loadMissing('translations');

        foreach (['ar', 'en'] as $locale) {
            $translation = $this->record->translationFor($locale);
            foreach ($this->translationFields() as $field) {
                $data['translations'][$locale][$field] = $translation?->{$field};
            }
        }

        if ($this->hasSeo()) {
            $data = $this->fillSeoFromRecord($this->record, $data);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        [$this->cachedTranslations, $data] = $this->extractTranslations($data);

        if ($this->hasSeo()) {
            $this->cachedSeo = $this->extractSeo($data);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $this->syncTranslations(
            $this->record,
            $this->translationModel(),
            $this->translationForeignKey(),
            $this->cachedTranslations,
            $this->translationFields(),
        );

        if ($this->hasSeo()) {
            $this->syncSeoForRecord($this->record, $this->cachedSeo);
        }
    }
}
