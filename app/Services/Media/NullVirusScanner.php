<?php

namespace App\Services\Media;

use App\Contracts\VirusScanner;

final class NullVirusScanner implements VirusScanner
{
    public function scan(string $absolutePath): bool
    {
        return true;
    }
}
