<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\{Gate, Route};
use App\Http\Controllers\Nova\LoginController;
use Laravel\Nova\{Nova, NovaApplicationServiceProvider};

class NovaServiceProvider extends NovaApplicationServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		parent::boot();
		Nova::sortResourcesBy(fn ($resource) => $resource::$priority ?? 9999);
	}

	/**
	 * Register the Nova routes.
	 *
	 * @return void
	 */
	protected function routes(): void
	{
		$this->withAuthenticationRoutes();
		Nova::routes()
			->withPasswordResetRoutes()
			->register();
	}

	/**
	 * Register the Nova authentication routes.
	 *
	 * @param array $middleware
	 * @return $this
	 */
	public function withAuthenticationRoutes($middleware = ['web'])
	{
		Route::namespace('App\Http\Controllers')
			->middleware($middleware)
			->prefix(Nova::path())
			->group(function () {
				Route::get('/login', [LoginController::class, 'showLoginForm']);
				Route::post('/login', [LoginController::class, 'login'])->name('nova.login');
			});

		Route::namespace('Laravel\Nova\Http\Controllers')
			->middleware((array) config('nova.middleware'))
			->prefix(Nova::path())
			->group(function () {
				Route::get('/logout', [LoginController::class, 'logout'])->name('nova.logout');
			});

		return $this;
	}

	/**
	 * Register the Nova gate.
	 *
	 * This gate determines who can access Nova in non-local environments.
	 *
	 * @return void
	 */
	protected function gate(): void
	{
		Gate::define('viewNova', fn (): bool => true);
	}
}
