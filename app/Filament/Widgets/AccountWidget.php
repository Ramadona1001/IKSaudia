<?php

namespace App\Filament\Widgets;

use BackedEnum;
use Filament\Facades\Filament;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget as BaseAccountWidget;

class AccountWidget extends BaseAccountWidget
{
    protected static ?int $sort = -3;

    protected int | string | array $columnSpan = 'full';

    /**
     * @var view-string
     */
    protected string $view = 'filament.widgets.account-widget';

    public static function canView(): bool
    {
        return Filament::auth()->check();
    }

    public function getProfileUrl(): ?string
    {
        return Filament::hasProfile() ? Filament::getProfileUrl() : null;
    }

    public function getProfileIcon(): string | BackedEnum
    {
        return Heroicon::OutlinedUserCircle;
    }
}
