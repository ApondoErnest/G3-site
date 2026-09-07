<?php

namespace App\Filament\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    protected static string $layout = 'filament.components.layout.auth';

    public function getMaxWidth(): Width|string|null
    {
        return Width::Medium;
    }

    public function getHeading(): string|Htmlable|null
    {
        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return __('admin.auth.mfa_heading');
        }

        return __('admin.auth.login');
    }

    public function getSubheading(): string|Htmlable|null
    {
        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return __('admin.auth.mfa_subheading');
        }

        return __('admin.auth.login_subheading');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
            ]);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('admin.auth.email'))
            ->placeholder(__('admin.auth.email_placeholder'))
            ->email()
            ->required()
            ->markAsRequired(false)
            ->autocomplete('username')
            ->autofocus();
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('admin.auth.password'))
            ->placeholder(__('admin.auth.password_placeholder'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->markAsRequired(false)
            ->autocomplete('current-password');
    }

    protected function getAuthenticateFormAction(): Action
    {
        return Action::make('authenticate')
            ->label(__('admin.auth.submit'))
            ->submit('authenticate');
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }
}
