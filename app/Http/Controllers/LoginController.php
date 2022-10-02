<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Laravel\Nova\Nova;
use Illuminate\Http\{RedirectResponse, Request};
use Laravel\Nova\Http\Controllers\LoginController as NovaLoginController;

class LoginController extends NovaLoginController
{
	/**
	 * Get the login username to be used by the controller.
	 *
	 * @return string
	 */
	public function username(): string
	{
		return 'phone';
	}

	/**
	 * Get the post register / login redirect path.
	 *
	 * @return string
	 */
	public function redirectPath(): string
	{
		return Nova::path() . '/resources/users';
	}

	/**
	 * Send login response
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function sendLoginResponse(Request $request): RedirectResponse
	{
		$request->session()->regenerate();

		$this->clearLoginAttempts($request);

		$redirectPath = $this->redirectPath();
		redirect()->setIntendedUrl($redirectPath);

		return redirect()->intended($redirectPath);
	}
}
