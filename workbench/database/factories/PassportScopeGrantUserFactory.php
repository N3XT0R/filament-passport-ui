<?php

namespace Database\Factories;

use App\Models\PassportScopeGrantUser;

/**
 * @extends UserFactory<PassportScopeGrantUser>
 */
class PassportScopeGrantUserFactory extends UserFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<PassportScopeGrantUser>
     */
    protected $model = PassportScopeGrantUser::class;
}
