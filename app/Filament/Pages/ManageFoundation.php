<?php

namespace App\Filament\Pages;

use App\Filament\Navigation\NavigationGroup;
use App\Filament\Pages\Foundation\FoundationForm;
use App\Models\HomeSection;
use App\Services\FoundationSectionService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageFoundation extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFlag;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::CONTENT;

    protected static ?string $navigationLabel = null;

    protected static ?int $navigationSort = 1;

    protected static ?string $title = null;

    protected static ?string $slug = 'mission-vision-values';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public ?HomeSection $section = null;

    public static function getNavigationLabel(): string
    {
        return __('cms.sections.foundation');
    }

    public function getTitle(): string
    {
        return __('cms.sections.foundation');
    }

    public function mount(FoundationSectionService $foundation): void
    {
        $this->section = $foundation->section();
        $this->form->fill($foundation->formState($this->section));
    }

    public function save(FoundationSectionService $foundation): void
    {
        $state = $this->form->getState();
        $settings = is_array($state['settings'] ?? null) ? $state['settings'] : [];

        $foundation->persist(
            $this->section ?? $foundation->section(),
            $settings,
            (bool) ($state['is_active'] ?? true),
        );

        Notification::make()
            ->title(__('cms.sections.foundation_saved'))
            ->success()
            ->send();
    }

    public function defaultForm(Schema $schema): Schema
    {
        return FoundationForm::configure($schema)
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
                ->label(__('cms.actions.save'))
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
                                ->label(__('cms.actions.save'))
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ]);
    }
}
