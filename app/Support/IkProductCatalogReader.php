<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;
use Smalot\PdfParser\XObject\Image;
use Throwable;

/**
 * Reads product datasheet PDFs from resources/ik_products and optional image maps.
 */
final class IkProductCatalogReader
{
    public function __construct(
        private readonly string $rootPath,
    ) {}

    public static function defaultRoot(): string
    {
        return resource_path('ik_products');
    }

    /**
     * @return Collection<int, array{
     *     slug: string,
     *     uuid: string,
     *     title_en: string,
     *     title_ar: string,
     *     summary_en: string,
     *     summary_ar: string,
     *     body_en: string,
     *     body_ar: string,
     *     pdf_absolute: string,
     *     pdf_basename: string,
     *     featured_source: ?string,
     *     gallery_sources: list<string>,
     *     icon: string,
     *     sort_order: int,
     *     warnings: list<string>
     * }>
     */
    public function products(): Collection
    {
        if (! is_dir($this->rootPath)) {
            Log::warning('IkProductCatalogReader: source folder missing.', ['path' => $this->rootPath]);

            return collect();
        }

        $imageMap = $this->loadImageMap();
        $pdfs = $this->discoverPdfs();
        $products = collect();
        $sort = 1;

        foreach ($pdfs as $pdfAbsolute) {
            $basename = basename($pdfAbsolute);
            $meta = $this->metaFromFilename($basename);
            if ($meta === null) {
                Log::warning('IkProductCatalogReader: skipped unrecognized PDF filename.', ['file' => $basename]);

                continue;
            }

            $warnings = [];
            $extracted = $this->extractPdfContent($pdfAbsolute, $meta['slug'], $warnings);

            $mappedImages = $this->resolveMappedImages($meta['slug'], $imageMap, $warnings);
            $matchedFolderImages = $mappedImages === []
                ? $this->matchImagesByKeywords($meta['slug'], $meta['keywords'], $warnings)
                : $this->slugFolderImages($meta['slug']);

            $gallerySources = array_values(array_unique(array_filter([
                ...$mappedImages,
                ...$matchedFolderImages,
                ...($extracted['embedded_images'] ?? []),
            ])));

            $featured = $gallerySources[0] ?? null;
            if ($featured === null) {
                $warnings[] = "No image matched for product [{$meta['slug']}].";
                Log::warning('IkProductCatalogReader: '.$warnings[array_key_last($warnings)], [
                    'pdf' => $basename,
                ]);
            }

            $bodyEn = $extracted['body_en'] !== ''
                ? $extracted['body_en']
                : $this->fallbackBodyHtml($meta['title_en'], $meta['summary_en']);

            $summaryEn = $extracted['summary_en'] !== ''
                ? $extracted['summary_en']
                : $meta['summary_en'];

            $products->push([
                'slug' => $meta['slug'],
                'uuid' => $this->deterministicUuid($meta['slug']),
                'title_en' => $meta['title_en'],
                'title_ar' => $meta['title_ar'],
                'summary_en' => Str::limit(strip_tags($summaryEn), 480),
                'summary_ar' => $meta['summary_ar'],
                'body_en' => $bodyEn,
                'body_ar' => $meta['body_ar'],
                'pdf_absolute' => $pdfAbsolute,
                'pdf_basename' => $basename,
                'featured_source' => $featured,
                'gallery_sources' => $gallerySources,
                'icon' => $meta['icon'],
                'sort_order' => $sort++,
                'warnings' => $warnings,
            ]);
        }

        return $products->values();
    }

    /**
     * @return list<string> absolute paths, preferred IK-Saudi_* first, duplicates skipped by slug
     */
    private function discoverPdfs(): array
    {
        $files = glob($this->rootPath.DIRECTORY_SEPARATOR.'*.pdf') ?: [];
        usort($files, function (string $a, string $b): int {
            $aPref = str_starts_with(basename($a), 'IK-Saudi') ? 0 : 1;
            $bPref = str_starts_with(basename($b), 'IK-Saudi') ? 0 : 1;

            return $aPref <=> $bPref ?: strcasecmp(basename($a), basename($b));
        });

        $seen = [];
        $unique = [];

        foreach ($files as $file) {
            $meta = $this->metaFromFilename(basename($file));
            if ($meta === null) {
                continue;
            }
            if (isset($seen[$meta['slug']])) {
                Log::warning('IkProductCatalogReader: duplicate PDF skipped (same product slug).', [
                    'kept' => basename($seen[$meta['slug']]),
                    'skipped' => basename($file),
                    'slug' => $meta['slug'],
                ]);

                continue;
            }
            $seen[$meta['slug']] = $file;
            $unique[] = $file;
        }

        return $unique;
    }

    /**
     * @return array{
     *     slug: string,
     *     title_en: string,
     *     title_ar: string,
     *     summary_en: string,
     *     summary_ar: string,
     *     body_ar: string,
     *     icon: string,
     *     keywords: list<string>
     * }|null
     */
    private function metaFromFilename(string $basename): ?array
    {
        $normalized = Str::of($basename)
            ->replaceMatches('/\.pdf$/i', '')
            ->replace(['IK-Saudi_', 'IK-Saudi', 'DATASHEET', '_'], ' ')
            ->squish()
            ->lower()
            ->toString();

        $catalog = [
            'foam scrapers' => [
                'slug' => 'foam-scrapers',
                'title_en' => 'Foam Scrapers',
                'title_ar' => 'كاشطات الرغوة',
                'summary_en' => 'Versatile polyurethane foam scrapers for pipeline cleaning, bore restrictions, and diameter changes.',
                'summary_ar' => 'كاشطات رغوة بولي يوريثان متعددة الاستخدامات لتنظيف خطوط الأنابيب والتعامل مع تضييقات القطر وتغيراته.',
                'body_ar' => '<p>كاشطات رغوة بولي يوريثان خفيفة ومرنة، مناسبة لأنظمة الأنابيب التي لم تُنظَّف سابقًا بالحالات التقليدية. تُصنع من رغوة خلوية مفتوحة وتُجهَّز بحبال سحب لسهولة الاستعادة.</p><p>تتوفر بكثافات منخفضة ومتوسطة وعالية، وبأحجام من ½ بوصة حتى 64 بوصة، مع خيارات طلاء وتعزيز بالفرش والكربيد والصفائح القياسية.</p>',
                'icon' => 'bi-droplet-half',
                'keywords' => ['foam', 'scrapers', 'scraper'],
            ],
            'bi directional brush scrapers' => [
                'slug' => 'bi-directional-brush-scrapers',
                'title_en' => 'Bi-directional Brush Scrapers',
                'title_ar' => 'كاشطات الفرشاة ثنائية الاتجاه',
                'summary_en' => 'Bi-directional brush scrapers for effective mechanical cleaning of pipeline walls in both flow directions.',
                'summary_ar' => 'كاشطات فرشاة ثنائية الاتجاه لتنظيف جدران خطوط الأنابيب بكفاءة في كلا اتجاهي التدفق.',
                'body_ar' => '<p>كاشطات فرشاة ثنائية الاتجاه مصممة لإزالة الرواسب والطين والمواد العالقة من الجدار الداخلي لخط الأنابيب مع الحفاظ على القدرة على الحركة في الاتجاهين.</p><p>تناسب أعمال التنظيف الميكانيكي والصيانة الدورية في قطاع النفط والغاز.</p>',
                'icon' => 'bi-brush',
                'keywords' => ['brush', 'bi-directional', 'bidirectional'],
            ],
            'bi directional disc scrapers' => [
                'slug' => 'bi-directional-disc-scrapers',
                'title_en' => 'Bi-directional Disc Scrapers',
                'title_ar' => 'كاشطات القرص ثنائية الاتجاه',
                'summary_en' => 'Bi-directional disc scrapers engineered for sealing, wiping, and debris removal in pipeline operations.',
                'summary_ar' => 'كاشطات قرص ثنائية الاتجاه مصممة للعزل والمسح وإزالة الرواسب في عمليات خطوط الأنابيب.',
                'body_ar' => '<p>كاشطات قرص ثنائية الاتجاه توفر قدرة إحكام ومسح فعالة داخل خط الأنابيب مع إمكانية التشغيل في الاتجاهين.</p><p>تُستخدم في التنظيف وإزالة الرواسب ودعم عمليات التفتيش والصيانة.</p>',
                'icon' => 'bi-record-circle',
                'keywords' => ['disc', 'disk', 'bi-directional', 'bidirectional'],
            ],
            'bi directional gauging scrapers' => [
                'slug' => 'bi-directional-gauging-scrapers',
                'title_en' => 'Bi-directional Gauging Scrapers',
                'title_ar' => 'كاشطات القياس ثنائية الاتجاه',
                'summary_en' => 'Bi-directional gauging scrapers used to verify pipeline bore integrity before inspection or cleaning runs.',
                'summary_ar' => 'كاشطات قياس ثنائية الاتجاه للتحقق من سلامة قطر خط الأنابيب قبل عمليات التفتيش أو التنظيف.',
                'body_ar' => '<p>كاشطات القياس ثنائية الاتجاه تساعد على التحقق من سلامة المقطع الداخلي لخط الأنابيب واكتشاف التضييقات قبل إرسال أدوات التفتيش أو التنظيف المتقدمة.</p>',
                'icon' => 'bi-rulers',
                'keywords' => ['gauging', 'gauge', 'bi-directional', 'bidirectional'],
            ],
        ];

        foreach ($catalog as $key => $meta) {
            if ($normalized === $key || Str::contains($normalized, $key)) {
                return $meta;
            }
        }

        // Fuzzy: foam / brush / disc / gauging
        if (str_contains($normalized, 'foam')) {
            return $catalog['foam scrapers'];
        }
        if (str_contains($normalized, 'brush')) {
            return $catalog['bi directional brush scrapers'];
        }
        if (str_contains($normalized, 'gaug')) {
            return $catalog['bi directional gauging scrapers'];
        }
        if (str_contains($normalized, 'disc') || str_contains($normalized, 'disk')) {
            return $catalog['bi directional disc scrapers'];
        }

        return null;
    }

    /**
     * @param  list<string>  $warnings
     * @return array{body_en: string, summary_en: string, embedded_images: list<string>}
     */
    private function extractPdfContent(string $pdfAbsolute, string $slug, array &$warnings): array
    {
        $embeddedImages = [];
        $bodyEn = '';
        $summaryEn = '';

        try {
            $parser = new Parser;
            $document = $parser->parseFile($pdfAbsolute);
            $text = $this->cleanExtractedText($document->getText() ?? '');

            if ($this->isUsefulProductText($text)) {
                $summaryEn = $this->summaryFromText($text);
                $bodyEn = $this->bodyHtmlFromText($text);
            } else {
                $warnings[] = "PDF text was sparse or unreadable for [{$slug}]; using curated fallback copy.";
                Log::warning('IkProductCatalogReader: sparse PDF text.', [
                    'slug' => $slug,
                    'file' => basename($pdfAbsolute),
                ]);
            }

            $embeddedImages = $this->extractEmbeddedImages($document, $slug, $warnings);
        } catch (Throwable $e) {
            $warnings[] = "Failed to parse PDF [{$slug}]: {$e->getMessage()}";
            Log::warning('IkProductCatalogReader: PDF parse failed.', [
                'slug' => $slug,
                'file' => basename($pdfAbsolute),
                'error' => $e->getMessage(),
            ]);
        }

        return [
            'body_en' => $bodyEn,
            'summary_en' => $summaryEn,
            'embedded_images' => $embeddedImages,
        ];
    }

    /**
     * @param  list<string>  $warnings
     * @return list<string> absolute paths to temp extracted image files
     */
    private function extractEmbeddedImages(object $document, string $slug, array &$warnings): array
    {
        $tmpDir = storage_path('app/tmp/ik_products/'.$slug);
        if (! is_dir($tmpDir) && ! mkdir($tmpDir, 0775, true) && ! is_dir($tmpDir)) {
            $warnings[] = "Could not create temp image dir for [{$slug}].";

            return [];
        }

        $saved = [];
        $index = 0;

        foreach ($document->getObjects() as $obj) {
            if (! $obj instanceof Image) {
                continue;
            }

            $details = $obj->getDetails();
            $width = (int) ($details['Width'] ?? $details['W'] ?? 0);
            $height = (int) ($details['Height'] ?? $details['H'] ?? 0);
            if ($width < 200 || $height < 200) {
                // Skip logos / tiny assets
                continue;
            }

            $content = $obj->getContent();
            if ($content === '' || $content === null) {
                continue;
            }

            $index++;
            $filterJson = json_encode($details['Filter'] ?? null);
            $path = null;

            try {
                if (str_contains((string) $filterJson, 'DCT')) {
                    $path = $tmpDir.'/embedded_'.$index.'.jpg';
                    file_put_contents($path, $content);
                } elseif (function_exists('imagecreatetruecolor') && $width > 0 && $height > 0) {
                    $expected3 = $width * $height * 3;
                    if (strlen($content) < $expected3) {
                        continue;
                    }
                    $im = imagecreatetruecolor($width, $height);
                    $pos = 0;
                    $bpp = strlen($content) >= ($width * $height * 4) ? 4 : 3;
                    for ($y = 0; $y < $height; $y++) {
                        for ($x = 0; $x < $width; $x++) {
                            $r = ord($content[$pos++]);
                            $g = ord($content[$pos++]);
                            $b = ord($content[$pos++]);
                            if ($bpp === 4) {
                                $pos++;
                            }
                            imagesetpixel($im, $x, $y, imagecolorallocate($im, $r, $g, $b));
                        }
                    }
                    $path = $tmpDir.'/embedded_'.$index.'.jpg';
                    imagejpeg($im, $path, 88);
                    imagedestroy($im);
                }
            } catch (Throwable $e) {
                $warnings[] = "Failed extracting embedded image #{$index} for [{$slug}]: {$e->getMessage()}";
                Log::warning('IkProductCatalogReader: embedded image extract failed.', [
                    'slug' => $slug,
                    'error' => $e->getMessage(),
                ]);
                $path = null;
            }

            if ($path && is_file($path) && filesize($path) > 8_000) {
                $saved[] = $path;
            }
        }

        // Prefer larger files first (likely the product shot)
        usort($saved, fn (string $a, string $b): int => filesize($b) <=> filesize($a));

        return $saved;
    }

    /**
     * @return array<string, array{featured?: string, gallery?: list<string>}>
     */
    private function loadImageMap(): array
    {
        $path = $this->rootPath.DIRECTORY_SEPARATOR.'image-map.json';
        if (! is_file($path)) {
            return [];
        }

        try {
            $decoded = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

            return is_array($decoded) ? $decoded : [];
        } catch (Throwable $e) {
            Log::warning('IkProductCatalogReader: invalid image-map.json.', ['error' => $e->getMessage()]);

            return [];
        }
    }

    /**
     * @param  array<string, array{featured?: string, gallery?: list<string>}>  $imageMap
     * @param  list<string>  $warnings
     * @return list<string> absolute paths
     */
    private function resolveMappedImages(string $slug, array $imageMap, array &$warnings): array
    {
        if (! isset($imageMap[$slug]) || ! is_array($imageMap[$slug])) {
            return [];
        }

        $paths = [];
        $featured = $imageMap[$slug]['featured'] ?? null;
        if (is_string($featured) && $featured !== '') {
            $paths[] = $featured;
        }
        foreach ($imageMap[$slug]['gallery'] ?? [] as $rel) {
            if (is_string($rel) && $rel !== '') {
                $paths[] = $rel;
            }
        }

        $absolute = [];
        foreach (array_unique($paths) as $rel) {
            $full = $this->rootPath.DIRECTORY_SEPARATOR.ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $rel), DIRECTORY_SEPARATOR);
            if (! is_file($full)) {
                $warnings[] = "image-map entry not found for [{$slug}]: {$rel}";
                Log::warning('IkProductCatalogReader: mapped image missing.', [
                    'slug' => $slug,
                    'path' => $rel,
                ]);

                continue;
            }
            $absolute[] = $full;
        }

        return $absolute;
    }

    /**
     * Match images under known folders by filename keywords / per-slug folder.
     *
     * @param  list<string>  $keywords
     * @param  list<string>  $warnings
     * @return list<string>
     */
    private function matchImagesByKeywords(string $slug, array $keywords, array &$warnings): array
    {
        $matches = $this->slugFolderImages($slug);

        $searchRoots = [
            $this->rootPath.DIRECTORY_SEPARATOR.'Products with Background',
            $this->rootPath.DIRECTORY_SEPARATOR.'Products in Factory',
            $this->rootPath.DIRECTORY_SEPARATOR.'People in Action',
        ];

        foreach ($searchRoots as $root) {
            if (! is_dir($root)) {
                continue;
            }

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if (! $file->isFile()) {
                    continue;
                }
                $ext = strtolower($file->getExtension());
                if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                    continue;
                }

                $name = Str::lower($file->getFilename());

                foreach ($keywords as $keyword) {
                    $needle = Str::lower($keyword);
                    // Avoid ultra-generic accidental matches for common words like "scrapers"
                    if (in_array($needle, ['scrapers', 'scraper', 'bi-directional', 'bidirectional'], true)) {
                        continue;
                    }
                    if ($needle !== '' && str_contains($name, $needle)) {
                        $matches[] = $file->getPathname();
                        break;
                    }
                }
            }
        }

        return array_values(array_unique($matches));
    }

    /**
     * @return list<string>
     */
    private function slugFolderImages(string $slug): array
    {
        $dir = $this->rootPath.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$slug;
        if (! is_dir($dir)) {
            return [];
        }

        $files = [];
        foreach (scandir($dir) ?: [] as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                continue;
            }
            $files[] = $dir.DIRECTORY_SEPARATOR.$file;
        }

        sort($files);

        return $files;
    }

    private function cleanExtractedText(string $text): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = preg_replace("/[ \t]+/u", ' ', $text) ?? $text;
        $text = preg_replace("/\n{3,}/u", "\n\n", $text) ?? $text;

        return trim($text);
    }

    private function isUsefulProductText(string $text): bool
    {
        if (mb_strlen($text) < 280) {
            return false;
        }

        // Require a reasonable amount of Latin product prose (not only letterhead / garbled Arabic)
        $latinChars = preg_match_all('/[A-Za-z]/', $text) ?: 0;

        return $latinChars >= 180;
    }

    private function summaryFromText(string $text): string
    {
        $withoutHeader = $this->stripLetterhead($text);
        $paragraphs = preg_split('/\n{2,}/', $withoutHeader) ?: [];
        foreach ($paragraphs as $paragraph) {
            $candidate = trim(preg_replace('/\s+/u', ' ', $paragraph) ?? $paragraph);
            if (mb_strlen($candidate) >= 80 && preg_match('/[A-Za-z]{20,}/', $candidate)) {
                return Str::limit($candidate, 480);
            }
        }

        return Str::limit(trim(preg_replace('/\s+/u', ' ', $withoutHeader) ?? $withoutHeader), 480);
    }

    private function bodyHtmlFromText(string $text): string
    {
        $withoutHeader = $this->stripLetterhead($text);
        $parts = preg_split('/\n{2,}/', $withoutHeader) ?: [];
        $html = '';

        foreach ($parts as $part) {
            $line = trim(preg_replace('/\s+/u', ' ', $part) ?? $part);
            if ($line === '' || mb_strlen($line) < 40) {
                continue;
            }
            // Skip repeated company letterhead fragments
            if (preg_match('/IK Saudi For Industries|Dammam 2nd Industrial|contact-ik-saudi|sales@iksaudi/i', $line)
                && mb_strlen($line) < 180) {
                continue;
            }
            $html .= '<p>'.e($line).'</p>';
        }

        return $html !== '' ? $html : $this->fallbackBodyHtml('Product', $withoutHeader);
    }

    private function stripLetterhead(string $text): string
    {
        $lines = preg_split('/\n/', $text) ?: [];
        $kept = [];
        foreach ($lines as $line) {
            $trim = trim($line);
            if ($trim === '') {
                $kept[] = '';
                continue;
            }
            if (preg_match('/^(IK\s*Saudi|IKS FOR INDUSTRIES|Dammam 2nd|Road 122|Zip Code|Kingdom of Saudi|C\.R\s*:|Page \d+|Phone\s*:|www\.|contact-ik-saudi|sales@iksaudi|يآ|عينصتلل|مامدلاب)/iu', $trim)) {
                continue;
            }
            $kept[] = $trim;
        }

        return trim(implode("\n", $kept));
    }

    private function fallbackBodyHtml(string $title, string $summary): string
    {
        $safeTitle = e($title);
        $safeSummary = e(strip_tags($summary));

        return "<p><strong>{$safeTitle}</strong></p><p>{$safeSummary}</p><p>Full technical specifications are available in the product datasheet PDF.</p>";
    }

    /**
     * Deterministic UUID (v3-style) so reseeding stays idempotent and fits char(36).
     */
    private function deterministicUuid(string $slug): string
    {
        $hash = md5('ik-saudi.products.'.$slug);

        return sprintf(
            '%s-%s-%s-%s-%s',
            substr($hash, 0, 8),
            substr($hash, 8, 4),
            substr($hash, 12, 4),
            substr($hash, 16, 4),
            substr($hash, 20, 12),
        );
    }
}
