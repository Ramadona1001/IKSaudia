<?php

namespace App\Filament\Concerns;

trait ResolvesHomeSectionRecordType
{
    protected function resolveRecordType(): ?string
    {
        if (! property_exists($this, 'record')) {
            return null;
        }

        $record = $this->record;

        return $record?->type;
    }
}
