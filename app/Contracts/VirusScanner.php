<?php

namespace App\Contracts;

interface VirusScanner
{
  public function scan(string $absolutePath): bool;
}
