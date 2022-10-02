<?php

declare(strict_types=1);

namespace App\Nova;

use Laravel\Nova\Resource as NovaResource;
use Titasgailius\SearchRelations\SearchesRelations;

abstract class Resource extends NovaResource
{
	use SearchesRelations;
}
