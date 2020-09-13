<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthTest extends TestCase
{
	use DatabaseMigrations, RefreshDatabase;

	private $user;

	public function setUp(): void
	{
		parent::setUp();
		$this->user = User::factory()->create();
	}

	/**
	 * Test user route.
	 *
	 * @return void
	 */
	public function test_that_we_cannot_fetch_user_unauthenticated(): void
	{
		$response = $this->get('/api/user');

		$response->assertUnauthorized();
	}

	/**
	 * Test index route.
	 *
	 * @return void
	 */
	public function test_index_route(): void
	{
		$response = $this->get('/');

		$response->assertNotFound();
	}

	/**
	 * Test login api route.
	 *
	 * @return void
	 */
	public function test_login_api_route(): void
	{
		$this->assertTrue(str_starts_with($this->getToken(), '1|'));
	}

	public function getToken(): string
	{
		$response = $this->post('/api/login', [
			'phone' => $this->user->phone,
			'password' => 'password',
			'device_name' => 'cc'
		]);
		return $response->getContent();
	}

	public function test_user_data_can_be_retrieved(): void
	{
		Sanctum::actingAs(
			$this->user,
			['*']
		);
		$response = $this->get('/api/user');
		$response->assertJson($this->user->toArray());
	}
}
