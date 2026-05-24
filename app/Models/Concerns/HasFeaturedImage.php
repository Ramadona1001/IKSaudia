<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

trait HasFeaturedImage
{
    public function initializeHasFeaturedImage(): void
    {
        if (! in_array('featured_image_url', $this->appends, true)) {
            $this->append('featured_image_url');
        }
    }

    protected function featuredImageUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            if (! $this->featured_image) {
                return null;
            }

            return Storage::disk('public')->url($this->featured_image);
        });
    }
}
