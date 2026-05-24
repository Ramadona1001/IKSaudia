<?php

namespace App\Services\Security;

use HTMLPurifier;
use HTMLPurifier_Config;

final class HtmlSanitizer
{
    private ?HTMLPurifier $purifier = null;

    public function clean(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        return $this->purifier()->purify($html);
    }

    /**
     * @param  list<string>  $fields
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function cleanFields(array $payload, array $fields): array
    {
        foreach ($fields as $field) {
            if (isset($payload[$field]) && is_string($payload[$field])) {
                $payload[$field] = $this->clean($payload[$field]);
            }
        }

        return $payload;
    }

    protected function purifier(): HTMLPurifier
    {
        if ($this->purifier !== null) {
            return $this->purifier;
        }

        $config = HTMLPurifier_Config::createDefault();
        $config->set('Cache.SerializerPath', storage_path('app/htmlpurifier'));
        $config->set('HTML.SafeIframe', false);
        $config->set('URI.DisableExternalResources', false);
        $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true]);
        $config->set('Attr.AllowedFrameTargets', ['_blank']);

        $this->purifier = new HTMLPurifier($config);

        return $this->purifier;
    }
}
