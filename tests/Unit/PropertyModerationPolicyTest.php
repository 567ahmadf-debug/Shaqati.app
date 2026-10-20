<?php

namespace Tests\Unit;

use App\Models\Property;
use App\Models\User;
use App\Policies\PropertyPolicy;
use PHPUnit\Framework\TestCase;

class PropertyModerationPolicyTest extends TestCase
{
    public function test_admin_can_moderate_a_property(): void
    {
        $admin = new User();
        $admin->id = 1;
        $admin->role = User::ROLE_ADMIN;

        $property = new Property();
        $property->landlord_id = 2;

        $policy = new PropertyPolicy();

        $this->assertTrue(
            $policy->moderate($admin, $property)
        );
    }

    public function test_landlord_cannot_moderate_a_property(): void
    {
        $landlord = new User();
        $landlord->id = 2;
        $landlord->role = User::ROLE_LANDLORD;

        $property = new Property();
        $property->landlord_id = 2;

        $policy = new PropertyPolicy();

        $this->assertFalse(
            $policy->moderate($landlord, $property)
        );
    }
}
