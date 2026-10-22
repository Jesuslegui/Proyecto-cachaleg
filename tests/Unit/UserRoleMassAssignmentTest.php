<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserRoleMassAssignmentTest extends TestCase
{
    public function test_role_can_be_assigned_to_a_user(): void
    {
        $user = new User();
        $user->fill(['role' => 'admin']);

        $this->assertSame('admin', $user->role);
    }
}