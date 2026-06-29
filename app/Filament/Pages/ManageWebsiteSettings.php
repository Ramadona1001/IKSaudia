<?php

namespace App\Filament\Pages;

use App\Filament\Navigation\NavigationGroup;
use App\Filament\Pages\WebsiteSettings\WebsiteSettingsForm;
use App\Services\NavigationService;
use App\Services\SettingsService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class ManageWebsiteSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog8Tooth;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::SYSTEM;

    protected static ?string $navigationLabel = 'Website Settings';

    protected static ?int $navigationSort = 0;

    protected static ?string $title = 'Website Settings';

    protected static ?string $slug = 'website-settings';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user?->can('system.manage') ?? $user?->hasRole('super_admin') ?? false;
    }

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);

        $state = app(SettingsService::class)->toFormState();
        $state['navigation']['header_items'] = app(NavigationService::class)->reindexFormItems(
            app(NavigationService::class)->toFormState(),
        );
        $this->form->fill($state);
    }

    public function save(): void
    {
        abort_unless(static::canAccess(), 403);

        $this->validate([
            'data.general.default_locale' => ['required', 'in:ar,en'],
            'data.general.supported_locales' => ['required', 'array', 'min:1'],
        ]);

        $data = $this->form->getState();

        $navItems = $data['navigation']['header_items'] ?? [];
        unset($data['navigation']);

        app(SettingsService::class)->syncFromForm($data);
        app(NavigationService::class)->syncFromForm(
            app(NavigationService::class)->reindexFormItems(is_array($navItems) ? $navItems : []),
        );

        Notification::make()
            ->title('Website settings saved')
            ->success()
            ->send();
    }

    public function defaultForm(Schema $schema): Schema
    {
        return WebsiteSettingsForm::configure($schema)
            ->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save settings')
                ->action('save')
                ->keyBindings(['mod+s']),
        ];
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->extraAttributes(['novalidate' => true])
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Save settings')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ]);
    }
}
