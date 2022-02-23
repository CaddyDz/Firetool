<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\TokenRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\{Request, Response};
use Illuminate\Validation\ValidationException;

class ApiController extends Controller
{
	/**
	 * details api
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function user(Request $request): Response
	{
		$user = $request->user();
		return response([
			'name' => $user->name,
			'phone' => $user->phone,
			'mode' => $user->mode,
		]);
	}

	/**
	 * login api
	 *
	 * @param \App\Http\Requests\TokenRequest $request
	 *
	 * @return \Illuminate\Http\Response|string usable Bearer token
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function login(TokenRequest $request): Response|string
	{
		$user = User::where('phone', $request->phone)
			->where('mode', $request->number)
			->first();

		if ($user && $user->tokens->isNotEmpty()) {
			return response([
				'status' => 'Already logged in',
			], 403);
		}

		if (!$user || !Hash::check($request->password, $user->password)) {
			throw ValidationException::withMessages([
				'phone' => ['The provided credentials are incorrect.'],
			]);
		}

		return $user->createToken($request->device_name)->plainTextToken;
	}

	/**
	 * Logout user
	 *
	 * Revoke all tokens of user
	 *
	 * @param \Illuminate\Http\Request $request Request object
	 *
	 * @return \Illuminate\Http\Response
	 **/
	public function logout(Request $request): Response
	{
		// Revoke all tokens...
		$request->user()->tokens()->delete();
		return response([
			'status' => 'Logged out',
		], 204);
	}
}
