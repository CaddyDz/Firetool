<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\{RateLimiter, Route};
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
	/**
	 * The path to the "home" route for your application.
	 *
	 * This is used by Laravel authentication to redirect users after login.
	 *
	 * @var string
	 */
	public const HOME = '/home';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		$this->configureRateLimiting();

		$this->routes(function () {
			Route::prefix('api')
				->middleware('api')
				->group(base_path('routes/api.php'));

			Route::middleware('web')
				->group(base_path('routes/web.php'));

			if (!app()->isProduction()) {
				Route::middleware('web')
					->group(base_path('routes/dev.php'));
			}
		});
	}

	/**
	 * Configure the rate limiters for the application.
	 *
	 * @return void
	 */
	protected function configureRateLimiting(): void
	{
		RateLimiter::for('api', function (Request $request) {
			/** @var \App\Models\User|null $user The user from the request */
			$user = $request->user();
			$id = is_null($user) ? $request->ip() : $user->id;
			Limit::perMinute(60)->by((string) $id);
		});
	}
}
