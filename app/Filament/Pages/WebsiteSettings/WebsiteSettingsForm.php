<?php

namespace App\Filament\Pages\WebsiteSettings;

use App\Models\PageTranslation;
use App\Services\NavigationService;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

final class WebsiteSettingsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Settings')->tabs([
                Tab::make('Navigation')->icon(Heroicon::OutlinedBars3)->schema(self::navigationTab()),
                Tab::make('General')->icon(Heroicon::OutlinedCog6Tooth)->schema(self::generalTab()),
                Tab::make('Branding')->icon(Heroicon::OutlinedSwatch)->schema(self::brandingTab()),
                Tab::make('Footer')->icon(Heroicon::OutlinedBars3BottomLeft)->schema(self::footerTab()),
                Tab::make('Contact')->icon(Heroicon::OutlinedPhone)->schema(self::contactTab()),
                Tab::make('Social')->icon(Heroicon::OutlinedShare)->schema(self::socialTab()),
                Tab::make('Newsletter')->icon(Heroicon::OutlinedEnvelopeOpen)->schema(self::newsletterTab()),
                Tab::make('SEO')->icon(Heroicon::OutlinedMagnifyingGlass)->schema(self::seoTab()),
                Tab::make('Advanced')->icon(Heroicon::OutlinedWrenchScrewdriver)->schema(self::advancedTab()),
            ])->columnSpanFull(),
        ]);
    }

    /** @return array<int, mixed> */
    protected static function navigationTab(): array
    {
        $pageSlugs = PageTranslation::query()
            ->where('locale', 'en')
            ->orderBy('title')
            ->pluck('title', 'slug')
            ->all();

        $routeOptions = [
            'home' => 'Home',
            'about' => 'About',
            'services.index' => 'Services',
            'products.index' => 'Products',
            'industries.index' => 'Industries',
            'projects.index' => 'Projects',
            'clients' => 'Clients',
            'partners' => 'Partners',
            'faq' => 'FAQ',
            'contact' => 'Contact',
            'page.show' => 'CMS page',
        ];

        return [
            Section::make('Main menu')
                ->description('Drag links to reorder the header menu. Order is saved when you click Save settings.')
                ->schema([
                    Repeater::make('navigation.header_items')
                        ->label('Menu links')
                        ->schema([
                            TextInput::make('label_ar')->label('Label (AR)')->maxLength(100),
                            TextInput::make('label_en')->label('Label (EN)')->maxLength(100),
                            Select::make('link_type')
                                ->label('Link type')
                                ->options([
                                    'route' => 'Internal route',
                                    'anchor' => 'Page anchor (#section)',
                                    'url' => 'External / custom URL',
                                ])
                                ->default('route')
                                ->live()
                                ->required(),
                            Select::make('route_name')
                                ->label('Route')
                                ->options($routeOptions)
                                ->visible(fn ($get) => $get('link_type') === 'route')
                                ->live(),
                            Select::make('page_slug')
                                ->label('Page')
                                ->options($pageSlugs)
                                ->searchable()
                                ->visible(fn ($get) => $get('link_type') === 'route' && $get('route_name') === 'page.show'),
                            TextInput::make('url')
                                ->label('URL or anchor')
                                ->helperText('For anchors use #process. For external links use full https:// URL.')
                                ->visible(fn ($get) => in_array($get('link_type'), ['anchor', 'url'], true)),
                            Toggle::make('is_mega_menu')
                                ->label('Mega menu (services dropdown)')
                                ->helperText('Enable for Services to show featured services panel.')
                                ->default(false),
                            Toggle::make('is_visible')->label('Visible')->default(true),
                            Hidden::make('id'),
                            Hidden::make('sort_order')->default(0),
                        ])
                        ->columns(2)
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => self::navigationItemLabel($state))
                        ->defaultItems(0)
                        ->reorderable()
                        ->reorderableWithDragAndDrop(true)
                        ->reorderableWithButtons()
                        ->live()
                        ->afterStateUpdated(function (?array $state, callable $set): void {
                            if (! is_array($state)) {
                                return;
                            }

                            $reindexed = app(NavigationService::class)->reindexFormItems($state);

                            $currentOrders = collect($state)
                                ->filter(fn ($row) => is_array($row))
                                ->values()
                                ->map(fn (array $row, int $index) => (int) ($row['sort_order'] ?? $index))
                                ->all();

                            $newOrders = collect($reindexed)
                                ->map(fn (array $row) => (int) ($row['sort_order'] ?? 0))
                                ->all();

                            if ($currentOrders !== $newOrders) {
                                $set('navigation.header_items', $reindexed);
                            }
                        })
                        ->dehydrateStateUsing(
                            fn (?array $state): array => app(NavigationService::class)->reindexFormItems(is_array($state) ? $state : []),
                        ),
                ]),
        ];
    }

    /** @return array<int, mixed> */
    protected static function generalTab(): array
    {
        return [
            Section::make('Identity')->columns(2)->schema([
                ...self::translatableText('general.site_name', 'Website name'),
                ...self::translatableText('general.site_tagline', 'Tagline'),
                Select::make('general.default_locale')
                    ->label('Default locale')
                    ->options(['ar' => 'Arabic', 'en' => 'English'])
                    ->native(false),
                Select::make('general.supported_locales')
                    ->label('Supported locales')
                    ->multiple()
                    ->options(['ar' => 'Arabic', 'en' => 'English'])
                    ->native(false),
            ]),
            Section::make('Logos & icons')->columns(2)->collapsed()->schema([
                self::imageUpload('general.favicon', 'Favicon', 'site-settings/favicons'),
                self::imageUpload('general.logo', 'Main logo', 'site-settings/logos'),
                self::imageUpload('general.logo_sticky', 'Sticky header logo', 'site-settings/logos'),
                self::imageUpload('general.logo_footer', 'Footer logo', 'site-settings/logos'),
                self::imageUpload('general.logo_dark', 'Dark mode logo', 'site-settings/logos'),
                self::imageUpload('general.seo_default_image', 'Default SEO image', 'site-settings/seo'),
            ]),
        ];
    }

    /** @return array<int, mixed> */
    protected static function brandingTab(): array
    {
        return [
            Section::make('Brand palette')
                ->description('Shared brand colors used for CTAs, cards, accents, and as defaults for derived mixes. These no longer force the header or footer to change.')
                ->columns(3)
                ->schema([
                    ColorPicker::make('branding.primary_color')->label('Primary color')->default('#0c1f38'),
                    ColorPicker::make('branding.secondary_color')->label('Secondary color')->default('#1a3d66'),
                    ColorPicker::make('branding.accent_color')->label('Accent color')->default('#c8922a'),
                ]),
            Section::make('Page & hero colors')
                ->description('Independent colors for the page background and hero text. Changing these does not affect the header or footer.')
                ->columns(3)
                ->collapsed()
                ->schema([
                    ColorPicker::make('branding.page_bg_color')
                        ->label('Page background')
                        ->helperText('Defaults to primary if empty.')
                        ->default('#0c1f38'),
                    ColorPicker::make('branding.hero_text_color')
                        ->label('Hero text')
                        ->default('#ffffff'),
                ]),
            Section::make('Header colors')
                ->description('Independent header colors. Changing brand primary no longer recolors the header bar or nav links automatically.')
                ->columns(2)
                ->collapsed()
                ->schema([
                    ColorPicker::make('branding.header_bg_color')->label('Header background')->default('#ffffff'),
                    ColorPicker::make('branding.header_text_color')->label('Nav link color')->default('#0c1f38'),
                    ColorPicker::make('branding.header_text_hover_color')->label('Nav link hover')->default('#1a3d66'),
                    ColorPicker::make('branding.header_icon_bg_color')->label('Icon / lang button background')->default('#1a3d66'),
                ]),
            Section::make('Footer colors')
                ->description('Independent footer colors.')
                ->columns(3)
                ->collapsed()
                ->schema([
                    ColorPicker::make('branding.footer_bg_color')->label('Footer background')->default('#030710'),
                    ColorPicker::make('branding.footer_text_color')->label('Footer text')->default('#ffffff'),
                    ColorPicker::make('branding.footer_accent_color')->label('Footer accent')->default('#c8922a'),
                ]),
            Section::make('Typography')->columns(2)->schema([
                TextInput::make('branding.font_latin')->label('Latin font')->default('Inter'),
                TextInput::make('branding.font_arabic')->label('Arabic font')->default('IBM Plex Sans Arabic'),
            ]),
            Section::make('Hero & loader')->columns(2)->schema([
                self::imageUpload('branding.hero_background_image', 'Hero background image', 'site-settings/hero'),
                TextInput::make('branding.hero_background_video')->label('Hero video URL')->url(),
                self::imageUpload('branding.loading_logo', 'Loading screen logo', 'site-settings/logos'),
            ]),
            Section::make('Site background pattern')
                ->description('Repeating pattern overlay across the whole website. Choose a preset or upload a custom seamless tile. Adjust opacity to control visibility over the page background.')
                ->columns(2)
                ->schema([
                    Select::make('branding.page_hero_pattern')
                        ->label('Pattern style')
                        ->options([
                            'hexagon' => 'Hexagon (brand)',
                            'grid' => 'Industrial grid',
                            'dots' => 'Dots',
                            'none' => 'None',
                            'custom' => 'Custom image',
                        ])
                        ->default('hexagon')
                        ->live(),
                    TextInput::make('branding.page_hero_pattern_size')
                        ->label('Pattern tile size (px)')
                        ->numeric()
                        ->default(60)
                        ->minValue(16)
                        ->maxValue(200)
                        ->helperText('Size of one repeating tile. Hexagon default: 60.'),
                    TextInput::make('branding.page_hero_pattern_opacity')
                        ->label('Pattern overlay opacity (%)')
                        ->numeric()
                        ->default(25)
                        ->minValue(0)
                        ->maxValue(100)
                        ->helperText('0 = hidden, 100 = fully visible. Recommended: 15–35.'),
                    self::imageUpload('branding.page_hero_pattern_image', 'Custom pattern image', 'site-settings/patterns')
                        ->visible(fn ($get) => $get('branding.page_hero_pattern') === 'custom')
                        ->helperText('Seamless PNG/SVG tile. Transparent patterns work best.'),
                ]),
        ];
    }

    /** @return array<int, mixed> */
    protected static function footerTab(): array
    {
        return [
            Section::make('Content')->schema([
                ...self::translatableTextarea('footer.description', 'Footer description'),
                ...self::translatableText('footer.copyright', 'Copyright text'),
            ]),
            Section::make('Footer CTA')->columns(2)->schema([
                Toggle::make('footer.cta_enabled')->label('Enable footer CTA')->default(true),
                ...self::translatableText('footer.cta_overline', 'CTA overline'),
                ...self::translatableText('footer.cta_title', 'CTA title'),
                ...self::translatableTextarea('footer.cta_subtitle', 'CTA subtitle'),
                self::imageUpload('footer.background_image', 'Footer background', 'site-settings/footer'),
            ]),
            Section::make('Link groups')->collapsed()->schema([
                self::linkRepeater('footer.quick_links', 'Quick links'),
                self::linkRepeater('footer.service_links', 'Service links'),
                self::linkRepeater('footer.industry_links', 'Industry links'),
                self::linkRepeater('footer.legal_links', 'Legal links'),
            ]),
            Section::make('Certifications')->schema([
                Repeater::make('footer.certification_badges')
                    ->label('Certification badges')
                    ->schema([
                        TextInput::make('code')->label('Code')->required(),
                        TextInput::make('label')->label('Label'),
                        Toggle::make('enabled')->label('Visible')->default(true),
                    ])
                    ->defaultItems(0)
                    ->collapsible(),
            ]),
        ];
    }

    /** @return array<int, mixed> */
    protected static function contactTab(): array
    {
        return [
            Section::make('Address & map')->schema([
                ...self::translatableTextarea('contact.address', 'Company address'),
                Textarea::make('contact.maps_embed')
                    ->label('Google Maps embed HTML')
                    ->rows(4)
                    ->helperText('Paste the iframe embed code from Google Maps.'),
            ]),
            Section::make('Channels')->columns(2)->schema([
                Repeater::make('contact.phones')
                    ->label('Phone numbers')
                    ->schema([
                        TextInput::make('label')->label('Label'),
                        TextInput::make('number')->label('Number')->tel()->required(),
                        Toggle::make('is_primary')->label('Primary'),
                    ])
                    ->columns(3)
                    ->collapsible(),
                Repeater::make('contact.emails')
                    ->label('Email addresses')
                    ->schema([
                        TextInput::make('label')->label('Label'),
                        TextInput::make('address')->label('Email')->email()->required(),
                        Toggle::make('is_primary')->label('Primary'),
                    ])
                    ->columns(3)
                    ->collapsible(),
                TextInput::make('contact.whatsapp')->label('WhatsApp')->tel(),
                TextInput::make('contact.emergency_phone')->label('Emergency contact')->tel(),
            ]),
            Section::make('Hours & notifications')->schema([
                ...self::translatableTextarea('contact.working_hours', 'Working hours'),
                Repeater::make('contact.form_recipients')
                    ->label('Contact form recipient emails')
                    ->schema([
                        TextInput::make('email')->label('Email')->email()->required(),
                    ])
                    ->defaultItems(1)
                    ->helperText('Notifications are sent to these addresses when the contact form is submitted.'),
            ]),
        ];
    }

    /** @return array<int, mixed> */
    protected static function socialTab(): array
    {
        return [
            Section::make('Social platforms')->description('Enable platforms and set profile URLs.')->schema([
                Repeater::make('social.links')
                    ->label('Social links')
                    ->schema([
                        Select::make('platform')
                            ->options([
                                'linkedin' => 'LinkedIn',
                                'x' => 'X (Twitter)',
                                'facebook' => 'Facebook',
                                'instagram' => 'Instagram',
                                'youtube' => 'YouTube',
                                'tiktok' => 'TikTok',
                                'snapchat' => 'Snapchat',
                                'behance' => 'Behance',
                                'custom' => 'Custom',
                            ])
                            ->required(),
                        TextInput::make('label')->label('Custom label'),
                        TextInput::make('url')->label('URL')->url(),
                        Toggle::make('enabled')->label('Enabled')->default(true),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['platform'] ?? null),
            ]),
        ];
    }

    /** @return array<int, mixed> */
    protected static function newsletterTab(): array
    {
        return [
            Section::make('Newsletter')->schema([
                Toggle::make('newsletter.enabled')->label('Enable newsletter')->default(false),
                ...self::translatableText('newsletter.title', 'Title'),
                ...self::translatableTextarea('newsletter.description', 'Description'),
                ...self::translatableText('newsletter.cta_text', 'CTA button text'),
            ]),
            Section::make('Mailchimp')->columns(2)->collapsed()->schema([
                TextInput::make('newsletter.mailchimp_api_key')->label('API key')->password()->revealable(),
                TextInput::make('newsletter.mailchimp_list_id')->label('Audience / list ID'),
                TextInput::make('newsletter.mailchimp_server')->label('Server prefix')->placeholder('us21'),
            ]),
        ];
    }

    /** @return array<int, mixed> */
    protected static function seoTab(): array
    {
        return [
            Section::make('Default meta')->schema([
                ...self::translatableText('seo.default_meta_title', 'Default meta title'),
                ...self::translatableTextarea('seo.default_meta_description', 'Default meta description'),
                ...self::translatableText('seo.default_keywords', 'Default keywords'),
                self::imageUpload('seo.og_image', 'OpenGraph image', 'site-settings/seo'),
            ]),
            Section::make('Twitter')->columns(2)->schema([
                Select::make('seo.twitter_card')
                    ->label('Twitter card type')
                    ->options([
                        'summary' => 'Summary',
                        'summary_large_image' => 'Summary large image',
                    ])
                    ->default('summary_large_image'),
                TextInput::make('seo.twitter_site')->label('Twitter @site'),
            ]),
            Section::make('Tracking & robots')->columns(2)->schema([
                TextInput::make('seo.robots')->label('Robots meta')->default('index, follow'),
                TextInput::make('seo.google_analytics_id')->label('Google Analytics ID'),
                TextInput::make('seo.google_tag_manager_id')->label('Google Tag Manager ID'),
                TextInput::make('seo.meta_pixel_id')->label('Meta Pixel ID'),
            ]),
            Section::make('Schema.org organization')->collapsed()->schema([
                KeyValue::make('seo.schema_organization')
                    ->label('Organization JSON-LD fields')
                    ->keyLabel('Property')
                    ->valueLabel('Value'),
            ]),
        ];
    }

    /** @return array<int, mixed> */
    protected static function advancedTab(): array
    {
        return [
            Section::make('Maintenance')->schema([
                Toggle::make('advanced.maintenance_enabled')->label('Maintenance mode'),
                ...self::translatableTextarea('advanced.maintenance_message', 'Maintenance message'),
                ...self::translatableTextarea('advanced.not_found_message', '404 page message'),
            ]),
            Section::make('SMTP reference')->description('Production mail is configured in .env; these fields document overrides for ops.')->columns(3)->collapsed()->schema([
                TextInput::make('advanced.smtp_host')->label('SMTP host'),
                TextInput::make('advanced.smtp_port')->label('SMTP port'),
                TextInput::make('advanced.smtp_encryption')->label('Encryption'),
            ]),
            Section::make('Performance')->schema([
                Toggle::make('advanced.cache_enabled')->label('Cache settings in memory')->default(true)
                    ->helperText('Disable only while debugging settings in development.'),
            ]),
        ];
    }

    protected static function imageUpload(string $name, string $label, string $directory): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->image()
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->nullable()
            ->maxSize(config('security.uploads.max_image_kb', 5120))
            ->acceptedFileTypes(config('security.uploads.allowed_mimes', ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml']))
            ->downloadable()
            ->openable()
            ->imagePreviewHeight('120')
            ->dehydrated();
    }

    protected static function linkRepeater(string $name, string $label): Repeater
    {
        return Repeater::make($name)
            ->label($label)
            ->schema([
                TextInput::make('label_ar')->label('Label (AR)'),
                TextInput::make('label_en')->label('Label (EN)'),
                TextInput::make('url')
                    ->label('URL')
                    ->required()
                    ->helperText('Full URL (https://…) or page slug (e.g. privacy-policy).'),
                Toggle::make('is_visible')->label('Visible')->default(true),
                TextInput::make('sort_order')->label('Sort')->numeric()->default(0),
            ])
            ->columns(2)
            ->collapsible()
            ->defaultItems(0);
    }

    /** @return array<int, mixed> */
    protected static function translatableText(string $path, string $label): array
    {
        return [
            TextInput::make("{$path}.ar")->label("{$label} (AR)")->maxLength(255),
            TextInput::make("{$path}.en")->label("{$label} (EN)")->maxLength(255),
        ];
    }

    /**
     * @param  array<string, mixed>  $state
     */
    protected static function navigationItemLabel(array $state): string
    {
        $label = $state['label_en'] ?? $state['label_ar'] ?? 'Menu link';
        $sort = isset($state['sort_order']) ? ((int) $state['sort_order'] + 1) : null;

        return $sort ? "#{$sort} · {$label}" : $label;
    }

    /** @return array<int, mixed> */
    protected static function translatableTextarea(string $path, string $label): array
    {
        return [
            Textarea::make("{$path}.ar")->label("{$label} (AR)")->rows(3),
            Textarea::make("{$path}.en")->label("{$label} (EN)")->rows(3),
        ];
    }
}
