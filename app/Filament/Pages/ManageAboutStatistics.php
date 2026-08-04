<?php

namespace App\Filament\Pages;

use App\Filament\Navigation\NavigationGroup;
use App\Filament\Pages\AboutStatistics\AboutStatisticsForm;
use App\Models\HomeSection;
use App\Services\AboutStatisticsSectionService;
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

class ManageAboutStatistics extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::HOMEPAGE;

    protected static ?string $navigationLabel = null;

    protected static ?int $navigationSort = 1;

    protected static ?string $title = null;

    protected static ?string $slug = 'about-statistics';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public ?HomeSection $section = null;

    public static function getNavigationLabel(): string
    {
        return __('cms.sections.about_stats');
    }

    public function getTitle(): string
    {
        return __('cms.sections.about_stats');
    }

    public function mount(AboutStatisticsSectionService $aboutStatistics): void
    {
        $this->section = $aboutStatistics->section();
        $this->form->fill($aboutStatistics->formState($this->section));
    }

    public function save(AboutStatisticsSectionService $aboutStatistics): void
    {
        $state = $this->form->getState();
        $settings = is_array($state['settings'] ?? null) ? $state['settings'] : [];

        $aboutStatistics->persist(
            $this->section ?? $aboutStatistics->section(),
            $settings,
        );

        Notification::make()
            ->title(__('cms.sections.about_stats_saved'))
            ->success()
            ->send();
    }

    public function defaultForm(Schema $schema): Schema
    {
        return AboutStatisticsForm::configure($schema)
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
