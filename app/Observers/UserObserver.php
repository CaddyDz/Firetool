<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;
use App\Events\UserDeleted;

class UserObserver
{
	/**
	 * Handle the user "deleted" event.
	 *
	 * @param \App\Models\User $user
	 *
	 * @return void
	 */
	public function deleted(User $user): void
	{
		event(new UserDeleted($user->phone));
	}
}
