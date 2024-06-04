<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		VerifyEmail::toMailUsing(function (object $notifiable, string $url): MailMessage {
			$urlForFrontend = config('app.frontend_url') . '?verify_url=' . urlencode($url);
			return (new MailMessage)->subject('Please verify your email')->view(
				['email-verification', 'email-verification-plain'],
				['url' => $urlForFrontend, 'name' => $notifiable->name]
			);
		});

		ResetPassword::toMailUsing(function (object $notifiable, string $token): MailMessage {
			$frontendUrl = config('app.frontend_url');
			$url = $frontendUrl . '?password-reset-token=' . $token . '&email=' . $notifiable->email;
			return (new MailMessage)->subject('Reset Password')->view(
				['password-reset', 'password-reset-plain'],
				['url' => $url, 'name' => $notifiable->name]
			);
		});
	}
}
