<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function signIn($user = null)
    {
        // Use the $user passed in, or whip one up, as needed
        $user = $user ?: factory('App\User')->create();

        // Set the user as the "authenticated user"
        $this->actingAs($user);

        // Pass back the one we with created or used
        return $user;
    }
}
