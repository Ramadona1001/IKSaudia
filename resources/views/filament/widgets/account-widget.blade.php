@php
    $user = filament()->auth()->user();
    $profileUrl = $this->getProfileUrl();
@endphp

<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <x-filament-panels::avatar.user
            size="lg"
            :user="$user"
            loading="lazy"
        />

        <div class="fi-account-widget-main">
            <h2 class="fi-account-widget-heading">
                {{ __('filament-panels::widgets/account-widget.welcome', ['app' => config('app.name')]) }}
            </h2>

            <p class="fi-account-widget-user-name">
                {{ filament()->getUserName($user) }}
            </p>

            @if ($user->email)
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $user->email }}
                </p>
            @endif
        </div>

        <div class="flex flex-wrap gap-2">
            @if ($profileUrl)
                <x-filament::button
                    :href="$profileUrl"
                    color="primary"
                    :icon="$this->getProfileIcon()"
                    tag="a"
                >
                    Edit profile
                </x-filament::button>
            @endif

            <form
                action="{{ filament()->getLogoutUrl() }}"
                method="post"
                class="fi-account-widget-logout-form"
            >
                @csrf

                <x-filament::button
                    color="gray"
                    :icon="\Filament\Support\Icons\Heroicon::ArrowLeftEndOnRectangle"
                    :icon-alias="\Filament\View\PanelsIconAlias::WIDGETS_ACCOUNT_LOGOUT_BUTTON"
                    labeled-from="sm"
                    tag="button"
                    type="submit"
                >
                    {{ __('filament-panels::widgets/account-widget.actions.logout.label') }}
                </x-filament::button>
            </form>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
