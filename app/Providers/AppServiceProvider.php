<?php

namespace App\Providers;

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
	}
}
