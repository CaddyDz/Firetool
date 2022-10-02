<?php

declare(strict_types=1);

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\{ID, Number, Password, Text};

class User extends Resource
{
	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = \App\Models\User::class;

	/**
	 * The order in which to show the resource on the sidebar.
	 *
	 * @var int $priority
	 */
	public static $priority = 1;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'name';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'id', 'name', 'phone',
	];

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function fields(Request $request): array
	{
		return [
			ID::make()->sortable(),

			Text::make('Name')
				->sortable()
				->rules('required', 'max:255'),

			Text::make('Phone')
				->sortable()
				->rules('required', 'max:255')
				->creationRules('unique:users,phone')
				->updateRules('unique:users,phone,{{resourceId}}'),

			Password::make('Password')
				->onlyOnForms()
				->creationRules('required', 'string', 'min:8')
				->updateRules('nullable', 'string', 'min:8'),

			Number::make('Mode')->min(1)->max(2)->step(1)->required(),
		];
	}
}
