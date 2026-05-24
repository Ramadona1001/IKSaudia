<?php

namespace App\Filament\Resources\Concerns;

use App\Filament\Concerns\SyncsModelTranslations;
use App\Filament\Concerns\SyncsSeo;
use Filament\Resources\Pages\CreateRecord;

abstract class CreateTranslatableRecord extends CreateRecord
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        [$this->cachedTranslations, $data] = $this->extractTranslations($data);

        if ($this->hasSeo()) {
            $this->cachedSeo = $this->extractSeo($data);
        }

        return $data;
    }

    protected function afterCreate(): void
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
