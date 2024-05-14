<?php

namespace Tests\Unit\Api;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Capsule\Manager as DB;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_user_register(): void
    {
        User::factory()->create();
    }
}
